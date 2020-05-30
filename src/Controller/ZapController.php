<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}