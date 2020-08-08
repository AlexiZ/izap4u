<?php

namespace App\Controller\Admin;

use App\Entity\Zap;
use App\Form\ZapType;
use App\Repository\ZapRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/zaps")
 */
class ZapController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @param ZapRepository $zapRepository
     * @return Response
     */
    public function list(ZapRepository $zapRepository): Response
    {
        return $this->render('admin/zap/list.html.twig', [
            'zaps' => $zapRepository->findAll(),
            'breadcrumb' => [
                0 => [
                    'url' => $this->generateUrl('app_admin_dashboard_index'),
                    'name' => 'Tableau de bord',
                ],
                1 => [
                    'url' => $this->generateUrl('app_admin_zap_list'),
                    'name' => 'Liste des zaps',
                ],
            ],
        ]);
    }

    /**
     * @Route(".json", methods={"GET"})
     * @param ZapRepository $zapRepository
     * @return JsonResponse
     */
    public function jsonList(ZapRepository $zapRepository): JsonResponse
    {
        return new JsonResponse($zapRepository->findAllAsArray());
    }

    /**
     * @Route("/nouveau", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $zap = new Zap();
        $form = $this->createForm(ZapType::class, $zap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($zap);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_zap_list');
        }

        return $this->render('admin/zap/new.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => [
                0 => [
                    'url' => $this->generateUrl('app_admin_dashboard_index'),
                    'name' => 'Tableau de bord',
                ],
                1 => [
                    'url' => $this->generateUrl('app_admin_zap_list'),
                    'name' => 'Liste des zaps',
                ],
                2 => [
                    'url' => $this->generateUrl('app_admin_zap_new'),
                    'name' => 'Créer un zap',
                ],
            ],
        ]);
    }

    /**
     * @Route("/{id}/modifier", methods={"GET","POST"})
     * @param Request $request
     * @param Zap $zap
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function edit(Request $request, Zap $zap, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ZapType::class, $zap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $filename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('zaps_thumbnails'),
                        $filename
                    );
                } catch (FileException $e) {}

                $zap->setImage($filename);
            }

            $zap->setUpdatedAt(new \DateTime());
            $zap->setUpdatedBy($this->getUser()->getUsername());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_admin_zap_list');
        }

        return $this->render('admin/zap/edit.html.twig', [
            'zap' => $zap,
            'form' => $form->createView(),
            'breadcrumb' => [
                0 => [
                    'url' => $this->generateUrl('app_admin_dashboard_index'),
                    'name' => 'Tableau de bord',
                ],
                1 => [
                    'url' => $this->generateUrl('app_admin_zap_list'),
                    'name' => 'Liste des zaps',
                ],
                2 => [
                    'url' => $this->generateUrl('app_admin_zap_edit', ['id' => $request->get('id')]),
                    'name' => 'Modifier ' . $zap->getTitle(),
                ],
            ],
        ]);
    }
}