<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CreationType;
use App\Form\SearchForm;

use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SortieRepository;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="app_sortie")
     */
    public function list(SortieRepository $sortieRepository, ParticipantRepository $participantRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $data = new SearchData();
        $data->campus= $this->getUser()->getCampus();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $sorties = $sortieRepository->findSearch($data, $this->getUser());
        return $this->render('sortie/accueil.html.twig',[
            "sorties" => $sorties,
            "form" => $form->createView()
        ]);

    }

    /**
     * @Route("/sortie/{sortieID}/{action}", name="app_action")
     */
    public function inscrire(EntityManagerInterface $entityManager, $action, Request $request,EtatRepository $etatRepository, ParticipantRepository $participantRepository, SortieRepository $sortieRepository, $sortieID, SearchData $data)
    {

        $sortie = $sortieRepository->find($sortieID); // sortie en question
        $participant = $participantRepository->find($this->getUser()->getId()); // Participant à ajouter
        //si il click sur s'inscrire a la sortie
        if($action == 1){
            $sortie->addParticipant($participant);
            $this->addFlash('success', "Vous etes inscrit à la sortie !");
            $nbParticipant = $sortie->getParticipants();
            if(count($nbParticipant) === $sortie->getNbInscriptionsMax()){
                $sortie->setEtat($etatRepository->findOneBy(['libelle' => 'Cloturee']));
            }
        }
        //si il click sur se desinscrire a la sortie
        elseif($action == 0){
            $sortie->removeParticipant($participant);
            $this->addFlash('warning', "Vous etes desinscrit à la sortie !");
           if($sortie->getEtat()->getLibelle() == 'Cloturee'){
                $etat = $etatRepository->findOneBy(['libelle' => 'Ouvert']);
                $sortie->setEtat($etat);
            }
        }
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('app_sortie');
    }

    /**
     * @Route("/creation", name="app_creation")
     * @Route("/modif/{sortieID}", name="app_modif")
     */
    public function creationSortie($sortieID = null, EntityManagerInterface $entityManager, SortieRepository $sortieRepository, Request $request, EtatRepository $etatRepository, LieuRepository $lieuRepository,CreationType $creationType, ManagerRegistry $doctrine)
    {

        $sortie = new Sortie();

        if(!$sortieID){
            $sortie = new Sortie();
        }else{
            $sortie = $sortieRepository->find($sortieID);
        }


        $form = $this->createForm(CreationType::class, $sortie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $sortie = $form->getData();
            //modifie etat a creee dans bdd
            if ($form->get("Enregistrer")->isClicked()) {
                //recuperer l'etat En modification
                $sortie->setEtat($etatRepository->findOneBy(["libelle" => "Creee"]));
            } elseif ($form->get("Publier")->isClicked()) {
                //recuperer l'etat Ouvert
                $sortie->setEtat($etatRepository->findOneBy(["libelle" => "Ouvert"]));
            }else {
                $sortieRepository->remove($sortieRepository->find($sortieID));
                $entityManager->flush();
                return $this->redirectToRoute('app_sortie');
            }


            //on ajoute l'organisateur
            $sortie->setOrganisateur($this->getUser());
            $sortie->setCampusOrganisateur($this->getUser()->getCampus());
            dump($sortie);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie est bien enregistrée !');

            return $this->redirectToRoute('app_sortie');

        }

        return $this->render('sortie/creation.html.twig', [
            "creationForm" => $form->createView(),
            'modifSortie' => $sortie->getId() !==null
        ]);
    }

    /**
     * @Route("/affichageSortie/{sortieID}", name="app_afficher_sortie")
     */
    public function afficherSortie(SortieRepository $sortieRepository, $sortieID): Response
    {
        $sortie = $sortieRepository->find($sortieID);

        return $this->render('sortie/affichageSortie.html.twig', [
            "sortie" => $sortie
        ]);
    }

    /**
     * @Route("/creationAPI/{ville}", name="app_creationAPI")
     * */
    public function findAll(LieuRepository $lieuRepo, Ville $ville)
    {
        $lieuxListe = $lieuRepo->getLieuxByVille($ville);

        dump($lieuxListe);

       return $this->json($lieuxListe, Response::HTTP_OK, [], ['groups' =>"id"]);
    }


}
