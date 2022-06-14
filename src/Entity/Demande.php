<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $motif;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'boolean')]
    private $etat = 1;

    #[ORM\Column(type: 'integer')]
    private $traitement = 0;

    #[ORM\Column(type: 'text', nullable: true)]
    private $detail;

    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $etudiant;

    #[ORM\ManyToOne(targetEntity: RP::class, inversedBy: 'demandes')]
    private $rp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMotif(): ?bool
    {
        return $this->motif;
    }

    public function setMotif(bool $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function isTraitement(): ?bool
    {
        return $this->traitement;
    }

    public function setTraitement(int $traitement): self
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

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
}
