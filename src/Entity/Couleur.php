<?php

namespace App\Entity;

use App\Repository\CouleurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouleurRepository::class)
 */
class Couleur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomCouleur;

    /**
     * @ORM\ManyToMany(targetEntity=Taille::class, mappedBy="couleur")
     */
    private $tailles;

    /**
     * @ORM\ManyToMany(targetEntity=Chaussure::class, inversedBy="couleurs")
     */
    private $chaussure;

    public function __construct()
    {
        $this->tailles = new ArrayCollection();
        $this->chaussure = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCouleur(): ?string
    {
        return $this->nomCouleur;
    }

    public function setNomCouleur(string $nomCouleur): self
    {
        $this->nomCouleur = $nomCouleur;

        return $this;
    }

    /**
     * @return Collection|Taille[]
     */
    public function getTailles(): Collection
    {
        return $this->tailles;
    }

    public function addTaille(Taille $taille): self
    {
        if (!$this->tailles->contains($taille)) {
            $this->tailles[] = $taille;
            $taille->addCouleur($this);
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        if ($this->tailles->removeElement($taille)) {
            $taille->removeCouleur($this);
        }

        return $this;
    }

    /**
     * @return Collection|Chaussure[]
     */
    public function getChaussure(): Collection
    {
        return $this->chaussure;
    }

    public function addChaussure(Chaussure $chaussure): self
    {
        if (!$this->chaussure->contains($chaussure)) {
            $this->chaussure[] = $chaussure;
        }

        return $this;
    }

    public function removeChaussure(Chaussure $chaussure): self
    {
        $this->chaussure->removeElement($chaussure);

        return $this;
    }

}
