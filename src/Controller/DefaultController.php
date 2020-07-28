<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route({
     *     "fr": "/les-restes-du-monde",
     *     "en": "/the-leftovers",
     * })
     */
    public function leftOvers()
    {
        return $this->render('default/leftovers.html.twig');
    }

    /**
     * @Route({
     *     "fr": "/un-peu-de-bon-son-pour-chiller",
     *     "en": "/good-vibes-to-chill",
     * })
     */
    public function goodVibes()
    {
        return $this->render('default/goodvibes.html.twig');
    }

    /**
     * @Route({
     *     "fr": "/les-videos-maison",
     *     "en": "/homemade-videos",
     * })
     */
    public function homemade()
    {
        return $this->render('default/homemade.html.twig');
    }

    /**
     * @Route({
     *     "fr": "/documentaires",
     *     "en": "/documentaries",
     * })
     */
    public function documentary()
    {
        return $this->render('default/documentaries.html.twig');
    }

    /**
     * @Route({
     *     "fr": "/faire-un-don",
     *     "en": "/make-a-donation",
     * })
     */
    public function donate()
    {
        return $this->render('default/donate.html.twig');
    }
}
