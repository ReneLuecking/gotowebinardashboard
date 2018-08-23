<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganizerRepository")
 */
class Organizer
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $organizerkey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $consumerkey;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $consumersecret;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $accesstoken;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $expiresin;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $refreshtoken;

    /**
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $accountkey;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $refreshexpiresin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Webinar", mappedBy="organizer", orphanRemoval=true)
     */
    private $webinars;

    private $responsekey;

    public function __construct()
    {
        $this->webinars = new ArrayCollection();
    }

    public function getOrganizerkey(): ?int
    {
        return $this->organizerkey;
    }

    public function setOrganizerkey(int $organizerkey): self
    {
        $this->organizerkey = $organizerkey;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getConsumerkey(): ?string
    {
        return $this->consumerkey;
    }

    public function setConsumerkey(string $consumerkey): self
    {
        $this->consumerkey = $consumerkey;

        return $this;
    }

    public function getConsumersecret(): ?string
    {
        return $this->consumersecret;
    }

    public function setConsumersecret(string $consumersecret): self
    {
        $this->consumersecret = $consumersecret;

        return $this;
    }

    public function getAccesstoken(): ?string
    {
        return $this->accesstoken;
    }

    public function setAccesstoken(string $accesstoken): self
    {
        $this->accesstoken = $accesstoken;

        return $this;
    }

    public function getExpiresin(): ?int
    {
        return $this->expiresin;
    }

    public function setExpiresin(int $expiresin): self
    {
        $this->expiresin = $expiresin;

        return $this;
    }

    public function getRefreshtoken(): ?string
    {
        return $this->refreshtoken;
    }

    public function setRefreshtoken(string $refreshtoken): self
    {
        $this->refreshtoken = $refreshtoken;

        return $this;
    }

    public function getAccountkey(): ?int
    {
        return $this->accountkey;
    }

    public function setAccountkey(int $accountkey): self
    {
        $this->accountkey = $accountkey;

        return $this;
    }

    public function getRefreshexpiresin(): ?int
    {
        return $this->refreshexpiresin;
    }

    public function setRefreshexpiresin(int $refreshexpiresin): self
    {
        $this->refreshexpiresin = $refreshexpiresin;

        return $this;
    }

    public function getResponsekey(): ?string
    {
        return $this->responsekey;
    }

    public function setResponsekey(string $responsekey): self
    {
        $this->responsekey = $responsekey;

        return $this;
    }

    /**
     * @return Collection|Webinar[]
     */
    public function getWebinars(): Collection
    {
        return $this->webinars;
    }

    public function addWebinar(Webinar $webinar): self
    {
        if (!$this->webinars->contains($webinar)) {
            $this->webinars[] = $webinar;
            $webinar->setOrganizer($this);
        }

        return $this;
    }

    public function removeWebinar(Webinar $webinar): self
    {
        if ($this->webinars->contains($webinar)) {
            $this->webinars->removeElement($webinar);
            // set the owning side to null (unless already changed)
            if ($webinar->getOrganizer() === $this) {
                $webinar->setOrganizer(null);
            }
        }

        return $this;
    }
}
