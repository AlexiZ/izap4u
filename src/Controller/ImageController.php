<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Zap;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageController extends AbstractController
{
    /**
     * @Route("/zap/show/{zapId}", methods={"GET","HEAD"})
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
     * @Route("/image/infinite/{imageIdFrom}", methods={"GET","HEAD"})
     *
     * @param int|null $imageIdFrom
     * @return JsonResponse
     */
    public function infinite(int $imageIdFrom = null)
    {
        return new JsonResponse($this->getDoctrine()->getRepository('App:Image')->getLatestFromId($imageIdFrom));
    }

    /**
     * @Route("/import/images/{token}")
     *
     * @param $token
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
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid().'.csv';

                // Move the file to the directory where brochures are stored
                try {
                    $csvFile->move(
                        $this->getParameter('csv_uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {}

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $csv = Reader::createFromPath($this->getParameter('csv_uploads') . '/' . $newFilename, 'r');
                $csv->setDelimiter(';');
                $csv->setHeaderOffset(0);
                $stmt = (new Statement());

                $records = $stmt->process($csv);
                foreach ($records as $record) {
                    // Download thumbnail
                    if ($record['thumbnail']) {
                        $thumbnail = $slugger->slug($record['title']) . '.jpg';
                        try {
                            file_put_contents($this->getParameter('images_directory') . '/' . $thumbnail, fopen($record['thumbnail'], 'r'));
                        } catch (\Exception $e) {
                            continue;
                        }
                        $record['image'] = $thumbnail;
                    }

                    $image = new Image($record);

                    $this->getDoctrine()->getManager()->persist($image);
                }
                $this->getDoctrine()->getManager()->flush();

                unlink($this->getParameter('csv_uploads') . '/' . $newFilename);
            }
        }

        return $this->render('zap/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}