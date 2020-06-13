<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_homepage_index');
        }

        return $this->render('profile/index.html.twig');
    }
}
