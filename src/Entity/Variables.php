<?php

namespace App\Entity;

use App\Repository\VariablesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VariablesRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"name"})})
 */
class Variables
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
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
