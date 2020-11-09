<?php

namespace App\Entity;

use App\Repository\MemberMembershipRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MemberMembershipRepository::class)
 */
class MemberMembership
{
    const STATUS_PENDING = 1;
    const STATUS_ACTIVATED = 2;
    const STATUS_REMOVED = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $hash;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="memberships")
     * @Serializer\Groups("show_membership")
     * @Assert\NotBlank()
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Membership")
     * @Serializer\Groups("show_membership")
     * @Assert\NotBlank()
     */
    private $membership;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Groups("show_membership")
     */
    private $expiryDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Groups("show_membership")
     */
    private $startDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @Assert\DateTime()
     */
    private $dateCreated;

    public function __construct()
    {
        $this->dateCreated = date_create();
        $this->status = self::STATUS_PENDING;

        try {
            $this->hash = bin2hex(random_bytes(8));
        } catch (\Exception $e) {
            $this->hash = uniqid('', true);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(\DateTimeInterface $expiryDate): self
    {
        $this->expiryDate = $expiryDate;

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

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember($member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getMembership(): ?Membership
    {
        return $this->membership;
    }

    public function setMembership($membership): self
    {
        $this->membership = $membership;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
