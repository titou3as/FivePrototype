<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DecisionRepository")
 */
class Decision
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTaken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="decisions")
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contributor", inversedBy="decisions")
     */
    private $contributor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $deposit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsTaken(): ?bool
    {
        return $this->isTaken;
    }

    public function setIsTaken(bool $isTaken): self
    {
        $this->isTaken = $isTaken;

        return $this;
    }

    public function getName() {
        return "Decision-" . $this->getId();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getContributor(): ?Contributor
    {
        return $this->contributor;
    }

    public function setContributor(?Contributor $contributor): self
    {
        $this->contributor = $contributor;

        return $this;
    }

    public function getDeposit(): ?string
    {
        return $this->deposit;
    }

    public function setDeposit(?string $deposit): self
    {
        $this->deposit = $deposit;

        return $this;
    }
}
