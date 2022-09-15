<?php

namespace App\Entity;

use App\Repository\LocalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocalRepository::class)
 */
class Local
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $instruments = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstruments(): ?array
    {
        return $this->instruments;
    }

    public function setInstruments(array $instruments): self
    {
        $this->instruments = $instruments;

        return $this;
    }
}
