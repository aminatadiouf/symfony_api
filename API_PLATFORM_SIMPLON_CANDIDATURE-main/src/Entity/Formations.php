<?php

namespace App\Entity;

use App\Repository\FormationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormationsRepository::class)]
class Formations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getFormation"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getFormation"])]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getFormation"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["getFormation"])]
    private ?int $duree = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Candidatures::class)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection<int, Candidatures>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidatures $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setFormation($this);
        }

        return $this;
    }

    public function removeCandidature(Candidatures $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getFormation() === $this) {
                $candidature->setFormation(null);
            }
        }

        return $this;
    }
}
