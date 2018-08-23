<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegistrantRepository")
 */
class Registrant
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $registrantkey;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Webinar", inversedBy="registrants")
     * @ORM\JoinColumn(nullable=false, name="webinarkey", referencedColumnName="webinarkey")
     */
    private $webinar;

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
     * @ORM\Column(type="datetime")
     */
    private $registrationdate;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $timezone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attendee", mappedBy="registrant", orphanRemoval=true)
     */
    private $attendees;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
    }

    public function getregistrantkey(): ?int
    {
        return $this->registrantkey;
    }

    public function setregistrantkey(int $registrantkey): self
    {
        $this->registrantkey = $registrantkey;

        return $this;
    }

    public function getWebinar(): ?Webinar
    {
        return $this->webinar;
    }

    public function setWebinar(?Webinar $webinar): self
    {
        $this->webinar = $webinar;

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

    public function getRegistrationdate(): ?\DateTimeInterface
    {
        return $this->registrationdate;
    }

    public function setRegistrationdate(\DateTimeInterface $registrationdate): self
    {
        $this->registrationdate = $registrationdate;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return Collection|Attendee[]
     */
    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    public function addAttendee(Attendee $attendee): self
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees[] = $attendee;
            $attendee->setRegistrant($this);
        }

        return $this;
    }

    public function removeAttendee(Attendee $attendee): self
    {
        if ($this->attendees->contains($attendee)) {
            $this->attendees->removeElement($attendee);
            // set the owning side to null (unless already changed)
            if ($attendee->getRegistrant() === $this) {
                $attendee->setRegistrant(null);
            }
        }

        return $this;
    }
}
