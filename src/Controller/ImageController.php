<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}