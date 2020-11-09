<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 * @ORM\Table(name="member", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="email_unique", columns={"email"})
 * })
 */
class Member
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
     * @Serializer\Groups("show_member")
     */
    private $givenNames;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Serializer\Groups("show_member")
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Serializer\Groups("show_member")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank()
     * @Serializer\Groups("show_member")
     */
    private $mobileNumber;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank()
     * @Serializer\Groups("show_member")
     */
    private $postcode;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @Serializer\Groups("show_member")
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     * @Assert\IsTrue()
     */
    private $termsAccepted;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $newsletterAccepted;

    /**
     * @ORM\OneToMany(targetEntity="MemberMembership", mappedBy="member")
     * @Assert\NotBlank()
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
        $this->dateCreated = date_create();
        $this->termsAccepted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGivenNames(): ?string
    {
        return $this->givenNames;
    }

    public function setGivenNames(string $givenNames): self
    {
        $this->givenNames = $givenNames;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getTermsAccepted(): ?bool
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted(?bool $termsAccepted): self
    {
        $this->termsAccepted = $termsAccepted;

        return $this;
    }

    public function getNewsletterAccepted(): ?bool
    {
        return $this->newsletterAccepted;
    }

    public function setNewsletterAccepted(?bool $newsletterAccepted): self
    {
        $this->newsletterAccepted = $newsletterAccepted;

        return $this;
    }

    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function getPendingMemberships(): Collection
    {
        return $this->memberships->filter(function ($membership) {
            /** @var MemberMembership $membership */
            return $membership->getStatus() === MemberMembership::STATUS_PENDING;
        });
    }

    public function getActiveMemberships(): Collection
    {
        $currentDate = date_create();

        return $this->memberships->filter(function ($membership) use ($currentDate) {
            /** @var MemberMembership $membership */
            $status = $membership->getStatus();
            $expiryDate = $membership->getExpiryDate();

            return $status === MemberMembership::STATUS_ACTIVATED && $expiryDate > $currentDate;
        });
    }

    public function setMemberships(ArrayCollection $memberships): Member
    {
        $this->memberships = $memberships;

        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
}
