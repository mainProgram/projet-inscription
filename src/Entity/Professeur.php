<?php

namespace App\Entity;

use App\Repository\ProfesseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfesseurRepository::class)]
class Professeur extends Personne
{
    #[ORM\Column(type: 'string', length: 50)]
    private $grade;

    #[ORM\ManyToMany(targetEntity: Classe::class, inversedBy: 'professeurs')]
    private $classes;

    #[ORM\ManyToMany(targetEntity: Module::class, inversedBy: 'professeurs')]
    private $modules;

    #[ORM\ManyToOne(targetEntity: RP::class, inversedBy: 'professeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private $rp;

    #[ORM\OneToMany(mappedBy: 'professeur', targetEntity: ProfesseurClasses::class)]
    private $professeurClasses;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->modules = new ArrayCollection();
        $this->professeurClasses = new ArrayCollection();
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        $this->classes->removeElement($class);

        return $this;
    }

    /**
     * @return Collection<int, Module>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        $this->modules->removeElement($module);

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
            $professeurClass->setProfesseur($this);
        }

        return $this;
    }

    public function removeProfesseurClass(ProfesseurClasses $professeurClass): self
    {
        if ($this->professeurClasses->removeElement($professeurClass)) {
            // set the owning side to null (unless already changed)
            if ($professeurClass->getProfesseur() === $this) {
                $professeurClass->setProfesseur(null);
            }
        }

        return $this;
    }

}
