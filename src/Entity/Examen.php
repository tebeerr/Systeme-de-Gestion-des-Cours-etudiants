<?php

namespace App\Entity;

use App\Repository\ExamenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenRepository::class)]
class Examen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $DateExamen = null;

    #[ORM\Column]
    private ?float $Note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): static
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getDateExamen(): ?\DateTime
    {
        return $this->DateExamen;
    }

    public function setDateExamen(\DateTime $DateExamen): static
    {
        $this->DateExamen = $DateExamen;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->Note;
    }

    public function setNote(float $Note): static
    {
        $this->Note = $Note;

        return $this;
    }
}
