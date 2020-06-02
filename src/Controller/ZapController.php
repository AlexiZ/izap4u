<?php

namespace App\Controller;

use App\Entity\Zap;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ZapController extends AbstractController
{
    /**
     * @Route("/zap/{zapId}", methods={"GET","HEAD"})
     *
     * @param int $zapId
     * @return Response
     */
    public function show(int $zapId)
    {
        return $this->render('zap/show.html.twig', [
            'zap' => $this->getDoctrine()->getRepository('App:Zap')->find($zapId),
        ]);
    }

    /**
     * @Route("/import/{token}")
     *
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @throws Exception
     */
    public function import($token, Request $request, SluggerInterface $slugger)
    {
        // Temporary "security" to prevent bots from spamming database
        if ('motdepasse' !== $token) {
            return $this->redirect('/');
        }

        $form = $this
            ->createFormBuilder()
            ->add('file', FileType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            $csvFile = $form->get('file')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($csvFile) {
                $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid().'.csv';

                // Move the file to the directory where brochures are stored
                try {
                    $csvFile->move(
                        $this->getParameter('csv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {}

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $csv = Reader::createFromPath($this->getParameter('csv_directory') . '/' . $newFilename, 'r');
                $csv->setDelimiter(';');
                $csv->setHeaderOffset(0);
                $stmt = (new Statement());

                $records = $stmt->process($csv);
                foreach ($records as $record) {
                    // Convert string duration XXHXX to minutes as integer
                    $duration = 0;
                    if ($record['duration']) {
                        list($hour, $minute) = explode('H', $record['duration']);
                        $duration = ((int) $hour * 60) + $minute;
                    }
                    $record['duration'] = $duration;

                    // Download thumbnail
                    if ($record['thumbnail']) {
                        $thumbnail = $slugger->slug($record['title']) . '.jpg';
                        try {
                            file_put_contents($this->getParameter('thumbnail_directory') . '/' . $thumbnail, fopen($record['thumbnail'], 'r'));
                        } catch (\Exception $e) {
                            continue;
                        }
                        $record['thumbnail'] = $thumbnail;
                    }

                    $zap = new Zap($record);

                    $this->getDoctrine()->getManager()->persist($zap);
                }
                $this->getDoctrine()->getManager()->flush();

                unlink($this->getParameter('csv_directory') . '/' . $newFilename);
            }
        }

        return $this->render('zap/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}