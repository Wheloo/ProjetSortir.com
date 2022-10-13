<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AppFixtures extends Fixture
{
    private $encoder;
    public const SORTIE_REFERENCE = 'sortie-';
    public const PARTICIPANT_REFERENCE = 'participant-';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        //Utilisation de Faker
        $faker = Factory::create('fr_FR');

        //Test déclaration de campus
        for($nbc=1; $nbc < 6 ; $nbc++) {
            $campus = new Campus();
            $campus->setNom($faker->company);
            $this->addReference('campus_' . $nbc, $campus);
            $manager->persist($campus);
        }

        //Creation d'utilisateur en dur dans la base de donnée
        $Hello = new Participant();
        $motPasse = $this->encoder->encodePassword($Hello, "password");
        $Hello->setPassword($motPasse);
        $Hello->setNom( "WORLD" );
        $Hello->setPrenom( "Hello" );
        $Hello->setPseudo('Helloworld');
        $Hello->setTelephone( "06010051012" );
        $Hello->setEmail("helloworld@gmail.com");
        $Hello->setAdministrateur( 0 );
        $Hello->setActif( 1 );
        $Hello->setCampus($campus);
        $manager->persist($Hello);

        //Creation d'utilisateurs
        for($nbp=1; $nbp < 16 ; $nbp++) {
            $participant = new Participant();

            $this->addReference(self::PARTICIPANT_REFERENCE.$nbp, $participant);

            $campus = $this->getReference('campus_'.$faker->numberBetween(1,5));
            $participant->setEmail($faker->email)
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setPseudo($faker->firstName())
                ->setTelephone($faker->phoneNumber())
                ->setActif(true)
                ->setAdministrateur(true)
                ->setCampus($campus);



            $motPasse = $this->encoder->encodePassword($participant, 'password');
            $participant->setPassword($motPasse);

            $this->addReference('participant_' . $nbp, $participant);

            $manager->persist($participant);

        }


    //creation des villes
    for($nbville=1; $nbville < 6 ; $nbville++){
        $ville = new Ville();
        $ville->setNom($faker->city);
        $ville->setCodePostal($faker->numberBetween(10000, 99999));
        $this->addReference('ville_'. $nbville ,$ville);
        $manager->persist($ville);
    }

    //Creation des lieux
    for($nblieu=1; $nblieu<12;$nblieu++){
        $ville = $this->getReference('ville_'.$faker->numberBetween(1,5));
        $lieu= new Lieu();
        $lieu->setNom($faker->company);
        $lieu->setRue($faker->streetAddress);
        $lieu->setVille($ville);
        $this->addReference('lieu_'. $nblieu ,$lieu);
        $manager->persist($lieu);
    }


    //Creation des etats
        $etatCreee =new Etat();
        $etatOuvert =new Etat();
        $etatCloturee =new Etat();
        $etatActivite =new Etat();
        $etatPassee =new Etat();
        $etatAnnulee =new Etat();
        $etatArchive = new Etat();

        $etatCreee->setLibelle('Creee');
        $etatOuvert->setLibelle('Ouvert');
        $etatCloturee->setLibelle('Cloturee');
        $etatActivite->setLibelle('Activite en cours');
        $etatPassee->setLibelle('Passee');
        $etatAnnulee->setLibelle('Annulee');
        $etatArchive->setLibelle('Archive');
        $manager->persist($etatAnnulee);
        $manager->persist($etatCloturee);
        $manager->persist($etatOuvert);
        $manager->persist($etatCreee);
        $manager->persist($etatActivite);
        $manager->persist($etatPassee);
        $manager->persist($etatArchive);

//Creation de sorties
        for ($i = 0; $i < 20; $i++) {
            $sortie = new Sortie();

            $number = $faker->randomDigitNotZero();
            $this->addReference(self::SORTIE_REFERENCE.$i, $sortie);
            for ($j=0; $j < $number; $j++){
                $sortie->addParticipant($this->getReference('participant_'.$faker->numberBetween(1, 15)));

            }
            $lieu = $this->getReference('lieu_' . $faker->numberBetween(1, 11));
            $campus = $this->getReference('campus_' . $faker->numberBetween(1, 5));
            $organisateur = $this->getReference('participant_' . $faker->numberBetween(1, 15));
            $sortie->setNom($faker->words(3, true))
                ->setDateHeureDebut($faker->dateTimeBetween('+ 1 month', '+ 2 month'))
                ->setDuree($faker->randomDigitNotZero())
                ->setDateLimiteInscription($faker->dateTimeBetween('now', '+ 1 month'))
                ->setNbInscriptionsMax($number)
                ->setInfosSortie($faker->sentence(20, true))
                ->setOrganisateur($organisateur)
                ->setCampusOrganisateur($campus)

                ->setLieu($lieu);
            if(count($sortie->getParticipants()) === $sortie->getNbInscriptionsMax()){
                $sortie->setEtat($etatCloturee);
            }else{
                $sortie->setEtat($etatOuvert);
            }
            $manager->persist($sortie);
        }

        $manager->flush();
    }

}

