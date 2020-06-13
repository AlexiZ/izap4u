<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Zap;
use App\Service\Import;
use League\Csv\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    /**
     * @Route("/import/{type}/{token}")
     *
     * @param $type
     * @param $token
     * @param Request $request
     * @param Import $import
     * @return Response
     * @throws Exception
     */
    public function import($type, $token, Request $request, Import $import)
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
            $entity = '';
            $directory = '';
            switch ($type) {
                case 'zap':
                    $entity = Zap::class;
                    $directory = $this->getParameter('zaps_thumbnails');
                    break;
                case 'image':
                    $entity = Image::class;
                    $directory = $this->getParameter('images_directory');
                    break;
            }
            $import->import($form->get('file')->getData(), $directory, $entity);
        }

        return $this->render('import/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
