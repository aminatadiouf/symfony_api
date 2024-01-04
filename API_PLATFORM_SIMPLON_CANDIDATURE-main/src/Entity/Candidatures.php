<?php

namespace App\Entity;

use App\Repository\CandidaturesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CandidaturesRepository::class)]
class Candidatures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCandidature"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    private ?Formations $formation = null;

    #[ORM\Column]
    #[Groups(["getCandidature"])]
    private ?bool $is_accepted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFormation(): ?Formations
    {
        return $this->formation;
    }

    public function setFormation(?Formations $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    public function isIsAccepted(): ?bool
    {
        return $this->is_accepted;
    }

    public function setIsAccepted(bool $is_accepted): static
    {
        $this->is_accepted = $is_accepted;

        return $this;
    }
}
