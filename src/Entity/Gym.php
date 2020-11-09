<?php

namespace App\Entity;

use App\Repository\GymRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GymRepository::class)
 */
class Gym
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Serializer\Groups({"list_gyms", "show_membership"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Membership", mappedBy="gym")
     * @Assert\NotBlank()
     * @Assert\Collection()
     * @Serializer\Groups("list_gyms")
     */
    private $memberships;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $dateCreated;

    public function __construct()
    {
        $this->memberships = new ArrayCollection();
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

    public function getMemberships(): ?Collection
    {
        return $this->memberships;
    }

    public function setMemberships(ArrayCollection $memberships): Gym
    {
        $this->memberships = $memberships;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
}
