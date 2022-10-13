<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\GererProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class GererProfilController extends AbstractController implements PasswordAuthenticatedUserInterface
{
    private $password;

    /**
     * @return string the hashed password for this user
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @Route("/gerer/profil/{id}", name="app_gerer_profil")
     *
     */
    public function create(Request $request, EntityManagerInterface $entityManager, Participant $participant, UserPasswordHasherInterface $passwordHasher): Response
    {

        $connectedUser = $this->getUser();

        // If not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute("app_login");
        }

        //If not user != userConnected
        if ($this->getUser() !== $participant) {
            return $this->redirectToRoute("app_login");
        }

        $gererProfil = $this->createForm(GererProfilType::class, $participant);

        $gererProfil->handleRequest($request);

        if ($gererProfil->isSubmitted() && $gererProfil->isValid()) {

            $connectedUser = $gererProfil->getData();

            //hash password
            $hashedPassword = $passwordHasher->hashPassword(
                $connectedUser,
                $connectedUser->getPassword()
            );

            $connectedUser->setPassword($hashedPassword);

            $this->addFlash('success', "Modification du profil reussi");

            $entityManager->persist($connectedUser);
            $entityManager->flush();
            return $this->redirectToRoute('app_sortie');
        }

        return $this->render('sortie/gererProfil.html.twig', [
            'gererProfil' => $gererProfil->createView(),
            'participant' => $connectedUser

        ]);
    }

    /**
     * @Route("/gerer/afficher/{idUtilisateur}", name="app_afficher_profil")
     */
    public function afficherProfil(ParticipantRepository $participantRepository, $idUtilisateur): Response
    {
        $participant = $participantRepository->find($idUtilisateur);

        return $this->render('profil/afficher.html.twig', [
            'participant' => $participant
    ]);

    }
}
