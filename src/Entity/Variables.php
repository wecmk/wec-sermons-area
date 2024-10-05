<?php

namespace App\Entity;

use App\Repository\VariablesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Index(columns: ['name'])]
#[ORM\Entity(repositoryClass: VariablesRepository::class)]
class Variables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT)]
    private ?string $value;

    public function __construct($name = null)
    {
        if ($name != null) {
            $this->name = $name;
        }
        $this->value = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue($default = null): ?string
    {
        return $this->value ?? $default;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
