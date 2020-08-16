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
            'latestZap'     => $this->getDoctrine()->getRepository('App:Zap')->getLatest(),
            'latestImages'  => $this->getDoctrine()->getRepository('App:Image')->getLatest(2),
            'latestSociety' => $this->getDoctrine()->getRepository('App:Zap')->getLatestByType('society'),
        ]);
    }

    /**
     * @Route("/horizontal")
     *
     * @return Response
     */
    public function index2()
    {
        return $this->render('homepage/index_horizontal.html.twig', [
            'latestZap' => $this->getDoctrine()->getRepository('App:Zap')->getLatest(),
            'zaps'      => $this->getDoctrine()->getRepository('App:Zap')->findBy([], ['publishedAt' => 'DESC'], 10),
            'images'    => $this->getDoctrine()->getRepository('App:Image')->getLatest(10),
            'societies' => $this->getDoctrine()->getRepository('App:Zap')->findBy([], ['publishedAt' => 'DESC'], 10),
        ]);
    }
}