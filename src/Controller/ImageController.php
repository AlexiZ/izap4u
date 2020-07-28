<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route({
     *     "fr": "/les-images",
     *     "en": "/the-images",
     * }, methods={"GET", "HEAD"})
     *
     * @param int $limit
     * @return Response
     */
    public function list(int $limit = 10)
    {
        return $this->render('image/list.html.twig', [
            'images' => $this->getDoctrine()->getRepository('App:Image')->findBy([], null, $limit),
        ]);
    }

    /**
     * @Route({
     *     "fr": "/les-images/{imageId}",
     *     "en": "/the-images/{imageId}",
     * }, methods={"GET","HEAD"})
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
     * @Route({
     *     "fr": "/les-images/_/{imageIdFrom}",
     *     "en": "/the-images/_/{imageIdFrom}",
     * }, methods={"GET","HEAD"})
     *
     * @param int|null $imageIdFrom
     * @return JsonResponse
     */
    public function infinite(int $imageIdFrom = null)
    {
        return new JsonResponse($this->getDoctrine()->getRepository('App:Image')->getLatestFromId($imageIdFrom));
    }
}