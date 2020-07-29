<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        if ($request->get('offset') > 0) {
            $html = '';
            $images = $this->getDoctrine()->getRepository('App:Image')->getDescending(
                $request->get('limit'),
                $request->get('offset')
            );
            foreach ($images as $image) {
                $html .= $this->renderView('image/_image.html.twig', ['image' => $image]);
            }

            return new Response($html);
        }

        return $this->render('image/list.html.twig', [
            'images' => $this->getDoctrine()->getRepository('App:Image')->findBy([], ['createdAt' => 'DESC'], $request->get('limit') ?? 9),
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
            'previousImage' => $this->getDoctrine()->getRepository('App:Image')->find($imageId - 1),
            'currentImage' => $this->getDoctrine()->getRepository('App:Image')->find($imageId),
            'nextImage' => $this->getDoctrine()->getRepository('App:Image')->find($imageId + 1),
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