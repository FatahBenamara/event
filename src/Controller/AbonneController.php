<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Entity\Event;
use App\Form\AbonneType;
use App\Entity\Participe;
use App\Form\FormEventType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventRepository;
use App\Repository\AbonneRepository;
use App\Repository\ParticipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AbonneController extends AbstractController
{
    /**
     * @Route("/abonne", name="abonne_index", methods={"GET"})
     */
    public function index(AbonneRepository $abonneRepository): Response
    {
        return $this->render('abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/abonne/nouveau", name="abonne_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $abonne->setPassword( $passwordEncoder->encodePassword($abonne, $form->get("password")->getData()) );
  
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($abonne);
              $entityManager->flush();
  
              return $this->redirectToRoute('abonne_index');
          }

        return $this->render('abonne/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/abonne/{id}", name="abonne_show", methods={"GET"})
     */
    public function show(Abonne $abonne): Response
    {
        return $this->render('abonne/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    /**
     * @Route("/abonne/{id}/modifier", name="abonne_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Abonne $abonne): Response
    {
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('abonne_index');
        }

        return $this->render('abonne/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/abonne/{id}", name="abonne_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Abonne $abonne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('abonne_index');
    }


   /**
     * @Route("/profil", name="profil")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function profil(){
        return $this->render("abonne/profil.html.twig");
    }



    /**
     * @Route("/profil/reserver-event", name="reserver")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */

    public function reserver(ParticipeRepository $pr, EventRepository $er, Request $rq, EntityManagerInterface $em){
        $events = $er->findAll();
        $participeEventsNonFinis = $pr->findByNonFinis();
        $eventsFinis = [];
        
        foreach($events as $event){
            $nonFinis = false;

            foreach($participeEventsNonFinis as $participe){
                if($event->getId() == $participe->getEvent()->getId()){
                    $nonFinis = true; 
                }
            }

            if(!$nonFinis){
                $eventsFinis[] = $event;
            }
        }

        if($rq->isMethod("POST")){
            $events = $rq->request->get("events");
            if($events){
                foreach ($events as $id_event) {
                    $participe = new Participe;
                    $participe->setAbonne($this->getUser());
                    
                    $participe->setEvent($er->find($id_event));
                    $participe->setDateAt(new \DateTime("now"));
                    $em->persist($participe);
                }
                $em->flush();
                return $this->redirectToRoute("profil");
            }
        }



        return $this->render("abonne/reservation.html.twig", compact("eventsFinis"));
    }    



    /**
     * @Route("/profil/ajouter-event", name="event_ajouter", methods={"GET", "POST"})
     */
    public function ajouter(EventRepository $er, EntityManagerInterface $em, Request $rq){
            $nouveauEvent = new Event;
            
            $formAjouter = $this->createForm(FormEventType::class, $nouveauEvent); 
            $formAjouter->handleRequest($rq);



        if ($formAjouter->isSubmitted()) {
        
            if ($formAjouter->isValid()){

                $fichier = $formAjouter->get("couverture")->getData();
                if($fichier){
                    $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomFichier .= uniqid();
                    $nomFichier .= "." . $fichier->getExtension();
                    $nomFichier = str_replace(" ", "_", $nomFichier);
                    // on enregistre le fichier téléchargé dans le dossier images
                    $fichier->move($this->getParameter("dossier_images"), $nomFichier);
                    $nouveauEvent->setCouverture($nomFichier);
                }





            $em->persist($nouveauEvent);
            $em->flush();
            $this->addFlash("success", "Nouveau event : <i>" . $nouveauEvent->getNom() .  "</i> ajouté");

            return $this->redirectToRoute("accueil");
            }
            else {
                $this->addFlash("danger", "Le formulaire n'est pas valide");
            }
        
        }

            
            $formAjouter = $formAjouter->createView();
            return $this->render("event/form_ajouter.html.twig", compact("formAjouter"));
    }
 
















}
