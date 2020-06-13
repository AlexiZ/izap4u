<?php

namespace App\Service;

use App\Entity\Image;
use Doctrine\Persistence\ManagerRegistry;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class Import
{
    private $csvDirectory;
    private $slugger;
    private $manager;

    public function __construct(ParameterBagInterface $params, SluggerInterface $slugger, ManagerRegistry $managerRegistry)
    {
        $this->csvDirectory = $params->get('csv_uploads');
        $this->slugger = $slugger;
        $this->manager = $managerRegistry;
    }

    /**
     * @param $csvFile
     * @param $uploadDirectory
     * @param $entity
     * @throws Exception
     */
    public function import($csvFile, $uploadDirectory, $entity)
    {
        // this condition is needed because the 'brochure' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($csvFile) {
            // this is needed to safely include the file name as part of the URL
            $newFilename = uniqid().'.csv';

            // Move the file to the directory where brochures are stored
            try {
                $csvFile->move(
                    $this->csvDirectory,
                    $newFilename
                );
            } catch (FileException $e) {}

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $csv = Reader::createFromPath($this->csvDirectory . '/' . $newFilename, 'r');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);

            $records = (new Statement())->process($csv);
            foreach ($records as $record) {
                if (isset($record['duration'])) {
                    // Convert string duration XXHXX to minutes as integer
                    $duration = 0;
                    if ('' !== $record['duration']) {
                        list($hour, $minute) = explode('H', $record['duration']);
                        $duration = ((int)$hour * 60) + $minute;
                    }
                    $record['duration'] = $duration;
                }

                // Download thumbnail
                if ($record['thumbnail']) {
                    $thumbnail = $this->slugger->slug($record['title']) . '.jpg';
                    try {
                        file_put_contents($uploadDirectory . '/' . $thumbnail, fopen($record['thumbnail'], 'r'));
                    } catch (\Exception $e) {
                        continue;
                    }
                    $record['image'] = $thumbnail;
                }

                // Remove unnecessary text in title
                preg_match('/Zap #\d+/', $record['title'], $matches);
                $record['title'] = $matches[0] ?? $record['title'];

                $image = new $entity($record);

                $this->manager->getManager()->persist($image);
            }
            $this->manager->getManager()->flush();

            unlink($this->csvDirectory . '/' . $newFilename);
        }
    }
}