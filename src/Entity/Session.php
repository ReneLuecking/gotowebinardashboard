<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Session
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $sessionkey;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Webinar", inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false, name="webinarkey", referencedColumnName="webinarkey")
     */
    private $webinar;

    /**
     * @ORM\Column(type="datetime")
     */
    private $starttime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endtime;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $timezone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attendee", mappedBy="session", orphanRemoval=true)
     */
    private $attendees;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
    }

    public function getsessionkey(): ?int
    {
        return $this->sessionkey;
    }

    public function setsessionkey(int $sessionkey): self
    {
        $this->sessionkey = $sessionkey;

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

    public function getStarttime(): ?\DateTimeInterface
    {
        return $this->starttime;
    }

    public function setStarttime(\DateTimeInterface $starttime): self
    {
        $this->starttime = $starttime;

        return $this;
    }

    public function getEndtime(): ?\DateTimeInterface
    {
        return $this->endtime;
    }

    public function setEndtime(\DateTimeInterface $endtime): self
    {
        $this->endtime = $endtime;

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
            $attendee->setSession($this);
        }

        return $this;
    }

    public function removeAttendee(Attendee $attendee): self
    {
        if ($this->attendees->contains($attendee)) {
            $this->attendees->removeElement($attendee);
            // set the owning side to null (unless already changed)
            if ($attendee->getSession() === $this) {
                $attendee->setSession(null);
            }
        }

        return $this;
    }
}
