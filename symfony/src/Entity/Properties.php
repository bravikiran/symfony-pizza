<?php

namespace App\Entity;

use App\Repository\PropertiesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertiesRepository::class)
 */
class Properties
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity=Pizzas::class, inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pizza;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(string $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getPizza(): ?Pizzas
    {
        return $this->pizza;
    }

    public function setPizza(?Pizzas $pizza): self
    {
        $this->pizza = $pizza;

        return $this;
    }
}
