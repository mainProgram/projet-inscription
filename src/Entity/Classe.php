<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $libelle;

    #[ORM\Column(type: 'string', length: 50)]
    private $filiere;

    #[ORM\Column(type: 'string', length: 20)]
    private $niveau;

    #[ORM\Column(type: 'boolean')]
    private $etat = 1;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Inscription::class)]
    private $inscriptions;

    #[ORM\ManyToMany(targetEntity: Professeur::class, mappedBy: 'classes')]
    private $professeurs;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: ProfesseurClasses::class)]
    private $professeurClasses;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->professeurs = new ArrayCollection();
        $this->professeurClasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getFiliere(): ?string
    {
        return $this->filiere;
    }

    public function setFiliere(string $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setClasse($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getClasse() === $this) {
                $inscription->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Professeur>
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function addProfesseur(Professeur $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) {
            $this->professeurs[] = $professeur;
            $professeur->addClass($this);
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        if ($this->professeurs->removeElement($professeur)) {
            $professeur->removeClass($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }

    /**
     * @return Collection<int, ProfesseurClasses>
     */
    public function getProfesseurClasses(): Collection
    {
        return $this->professeurClasses;
    }

    public function addProfesseurClass(ProfesseurClasses $professeurClass): self
    {
        if (!$this->professeurClasses->contains($professeurClass)) {
            $this->professeurClasses[] = $professeurClass;
            $professeurClass->setClasse($this);
        }

        return $this;
    }

    public function removeProfesseurClass(ProfesseurClasses $professeurClass): self
    {
        if ($this->professeurClasses->removeElement($professeurClass)) {
            // set the owning side to null (unless already changed)
            if ($professeurClass->getClasse() === $this) {
                $professeurClass->setClasse(null);
            }
        }

        return $this;
    }
}
