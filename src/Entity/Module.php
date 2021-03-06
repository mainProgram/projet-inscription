<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'boolean')]
    private $etat = 1;

    #[ORM\ManyToMany(targetEntity: Professeur::class, mappedBy: 'modules')]
    private $professeurs;

    #[ORM\ManyToOne(targetEntity: RP::class, inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private $rp;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: ProfesseurClasses::class)]
    private $professeurClasses;

    public function __construct()
    {
        $this->professeurs = new ArrayCollection();
        $this->professeurClasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $professeur->addModule($this);
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        if ($this->professeurs->removeElement($professeur)) {
            $professeur->removeModule($this);
        }

        return $this;
    }

    public function getRp(): ?RP
    {
        return $this->rp;
    }

    public function setRp(?RP $rp): self
    {
        $this->rp = $rp;

        return $this;
    }

    /**
     * @return Collection<int, ProfesseurClasse>
     */
    public function getProfesseurClasses(): Collection
    {
        return $this->professeurClasses;
    }

    public function addProfesseurClass(ProfesseurClasses $professeurClass): self
    {
        if (!$this->professeurClasses->contains($professeurClass)) {
            $this->professeurClasses[] = $professeurClass;
            $professeurClass->setModule($this);
        }

        return $this;
    }

    public function removeProfesseurClass(ProfesseurClasses $professeurClass): self
    {
        if ($this->professeurClasses->removeElement($professeurClass)) {
            // set the owning side to null (unless already changed)
            if ($professeurClass->getModule() === $this) {
                $professeurClass->setModule(null);
            }
        }

        return $this;
    }
}
