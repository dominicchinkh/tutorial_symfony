<?php

namespace App\Entity;

use App\Repository\TableProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableProjectRepository::class)]
class TableProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $marking = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
    
    public function getMarking(): array
    {
        return $this->marking;
    }

    public function setMarking(array $marking): static
    {
        $this->marking = $marking;

        return $this;
    }
}
