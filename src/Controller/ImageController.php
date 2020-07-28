<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/image/show/{imageId}", methods={"GET","HEAD"})
     *
     * @param int $imageId
     * @return Response
     */
    public function show(int $imageId)
    {
        return $this->render('image/show.html.twig', [
            'image' => $this->getDoctrine()->getRepository('App:Image')->find($imageId),
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