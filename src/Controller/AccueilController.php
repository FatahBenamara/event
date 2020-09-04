<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\ParticipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(EventRepository $er)
    {
        $liste_events = $er->findAll();
        return $this->render("base.html.twig", [
            "liste_events" => $liste_events
        ]);

    }



    /**
     * @Route("/recherche", name="recherche")
     */
    public function recherche(EventRepository $er, Request $rq)
    {
        $mot = $rq->query->get("mot_recherche");

        $liste_events = $er->findBySearch($mot);
        return $this->render("base.html.twig", compact("liste_events"));

    }






}
