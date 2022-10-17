<?php

namespace App\Entity;

use App\Repository\InstrumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstrumentRepository::class)
 */
class Instrument
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
    private $instrument;

    /**
     * @ORM\ManyToOne(targetEntity=Local::class)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Owner::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->instrument;
    }

    public function setName(string $instrument): self
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getLieu(): ?Local
    {
        return $this->lieu;
    }

    public function setLieu(?Local $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
