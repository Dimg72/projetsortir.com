<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Veuillez inscrire 2 caractères minimum, s'il vous plait",
     *     maxMessage="Veuillez inscrire 255 caractères maximum, s'il vous plait"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     *  @Assert\Length(
     *     min=5,
     *     max=5,
     *     minMessage="Veuillez inscrire 5 caractères minimum, s'il vous plait",
     *     maxMessage="Veuillez inscrire 5 caractères maximum, s'il vous plait"
     * )
     * @ORM\Column(type="string", length=5)
     */
    private $codePostal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }
}
