<?php

namespace App\Entity;

use App\Repository\MembershipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MembershipRepository::class)
 */
class Membership
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
     * @Serializer\Groups("list_gyms")
     */
    private $hash;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     * @Assert\Currency()
     * @Serializer\Groups({"list_gyms", "show_membership"})
     *
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="MembershipType")
     * @Assert\NotBlank()
     * @Serializer\Groups({"list_gyms", "show_membership"})
     */
    private $membershipType;

    /**
     * @ORM\ManyToOne(targetEntity="Gym", inversedBy="memberships")
     * @Assert\NotBlank()
     * @Serializer\Groups("show_membership")
     */
    private $gym;

    /**
     * @ORM\OneToMany(targetEntity="MemberMembership", mappedBy="membership")
     * @Assert\NotBlank()
     */
    private $members;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreated;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getGym(): ?Gym
    {
        return $this->gym;
    }

    public function setGym($gym): self
    {
        $this->gym = $gym;

        return $this;
    }

    public function getMembers(): ?Collection
    {
        return $this->members;
    }

    public function setMembers(ArrayCollection $members): Membership
    {
        $this->members = $members;

        return $this;
    }

    public function getMembershipType(): ?MembershipType
    {
        return $this->membershipType;
    }

    public function setMembershipType(MembershipType $membershipType): self
    {
        $this->membershipType = $membershipType;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash($hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
