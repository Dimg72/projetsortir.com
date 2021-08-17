<?php

namespace App\Entity;

use App\Repository\ProfilePhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfilePhotoRepository::class)
 */
class ProfilePhoto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Participant::class, inversedBy="profilePhoto", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $participant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photoProfileTag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getPhotoProfileTag(): ?string
    {
        return $this->photoProfileTag;
    }

    public function setPhotoProfileTag(string $photoProfileTag): self
    {
        $this->photoProfileTag = $photoProfileTag;

        return $this;
    }
}
