<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('homepage/index.html.twig', [
            'latestZap'   => $this->getDoctrine()->getRepository('App:Zap')->getLatest(),
            'latestImage' => null,
        ]);
    }
}