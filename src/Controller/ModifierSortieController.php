<?php

namespace App\Controller;

use App\Entity\Annuler;
use App\Form\AnnulerForm;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ModifierSortieController extends AbstractController
{
    /**
     * @Route("/publier/{sortieID}", name="app_publier")
     */
    public function publier($sortieID, SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager){

      $sortie = $sortieRepository->find($sortieID);
      $sortie->setEtat($etatRepository->findOneBy(["libelle" => "Ouvert"]));
      $entityManager->persist($sortie);
      $entityManager->flush();
      return $this->redirectToRoute('app_sortie');
    }

    /**
     * @Route("/annuler/{sortieID}", name="app_annuler")
     */
    public function AnnulerSortie($sortieID, SortieRepository $sortieRepository, Request $request, EtatRepository $etatRepository, EntityManagerInterface $entityManager){
        $sortie = $sortieRepository->find($sortieID);
        $sortieAnnule = new Annuler();
        $sortieAnnule->setSortie($sortie);
        $form = $this->createForm(AnnulerForm::class, $sortieAnnule);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if ($form->get("Enregistrer")->isClicked()) {
                $sortie->setEtat($etatRepository->findOneBy(["libelle" => "Annulee"]));
                $sortieAnnule->setSortie($sortie);
                $sortieAnnule->setDateAnnulation(new \DateTime());
                $entityManager->persist($sortieAnnule);
                $entityManager->flush();
                $this->addFlash("success","Votre sortie a bien été annulée");
                return $this->redirectToRoute('app_sortie');
            }else {
                return $this->redirectToRoute('app_sortie');
            }
        }
        return $this->render('sortie/annuler.html.twig', [
            "sortie" => $sortieAnnule,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/modifier/{sortieID}", name="app_modifier")
     */
    public function ModifierSortie($sortieID, SortieRepository $sortieRepository, Request $request, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {

        $sortie = $sortieRepository->find($sortieID);

    }
}
