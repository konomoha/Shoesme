<?php

namespace App\Entity;

use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TailleRepository::class)
 */
class Taille
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $pointure;

    /**
     * @ORM\ManyToMany(targetEntity=Couleur::class, inversedBy="tailles")
     */
    private $couleur;

    /**
     * @ORM\OneToMany(targetEntity=DetailsCommande::class, mappedBy="taille")
     */
    private $detailsCommandes;

    /**
     * @ORM\ManyToMany(targetEntity=Stock::class, mappedBy="taille")
     */
    private $stocks;

    public function __construct()
    {
        $this->couleur = new ArrayCollection();
        $this->detailsCommandes = new ArrayCollection();
        $this->stocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointure(): ?float
    {
        return $this->pointure;
    }

    public function setPointure(float $pointure): self
    {
        $this->pointure = $pointure;

        return $this;
    }

    /**
     * @return Collection|Couleur[]
     */
    public function getCouleur(): Collection
    {
        return $this->couleur;
    }

    public function addCouleur(Couleur $couleur): self
    {
        if (!$this->couleur->contains($couleur)) {
            $this->couleur[] = $couleur;
        }

        return $this;
    }

    public function removeCouleur(Couleur $couleur): self
    {
        $this->couleur->removeElement($couleur);

        return $this;
    }

    /**
     * @return Collection|DetailsCommande[]
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->detailsCommandes;
    }

    public function addDetailsCommande(DetailsCommande $detailsCommande): self
    {
        if (!$this->detailsCommandes->contains($detailsCommande)) {
            $this->detailsCommandes[] = $detailsCommande;
            $detailsCommande->setTaille($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): self
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getTaille() === $this) {
                $detailsCommande->setTaille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->addTaille($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            $stock->removeTaille($this);
        }

        return $this;
    }
}
