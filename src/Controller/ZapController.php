<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZapController extends AbstractController
{
    /**
     * @Route({
     *     "fr": "/le-zap",
     *     "en": "/the-zap",
     * }, methods={"GET", "HEAD"})
     *
     * @return Response
     */
    public function list(Request $request)
    {
        if ($request->get('zapIdFrom') > 0) {
            $html = '';
            $zaps = $this->getDoctrine()->getRepository('App:Zap')->getDescending(
                $request->get('type') ?? 'long',
                $request->get('limit'),
                $request->get('zapIdFrom')
            );
            foreach ($zaps as $zap) {
                $html .= $this->renderView('zap/_zap.html.twig', ['zap' => $zap]);
            }

            return new Response($html);
        }

        return $this->render('zap/list.html.twig', [
            'zaps' => $this->getDoctrine()->getRepository('App:Zap')->findBy([], ['createdAt' => 'DESC'], $request->get('limit') ?? 9),
        ]);
    }

    /**
     * @Route({
     *     "fr": "/le-zap/{zapId}",
     *     "en": "/the-zap/{zapId}",
     * }, methods={"GET","HEAD"})
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
     * @Route({
     *     "fr": "/le-zap/_/{type}/{zapIdFrom}",
     *     "en": "/the-zap/_/{type}/{zapIdFrom}",
     * }, methods={"GET","HEAD"})
     *
     * @param string $type
     * @param int|null $zapIdFrom
     * @return JsonResponse
     */
    public function infinite(string $type, int $zapIdFrom = null)
    {
        return new JsonResponse($this->getDoctrine()->getRepository('App:Zap')->getLatestByType($type, $zapIdFrom));
    }
}