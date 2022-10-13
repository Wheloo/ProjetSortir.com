<?php

namespace App\Entity;

use App\Repository\AnnulerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnulerRepository::class)
 */
class Annuler
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $motif;

    /**
     * @ORM\OneToOne(targetEntity=Sortie::class, cascade={"persist", "remove"})
     */
    private $Sortie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAnnulation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getSortie(): ?Sortie
    {
        return $this->Sortie;
    }

    public function setSortie(?Sortie $Sortie): self
    {
        $this->Sortie = $Sortie;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }
}
