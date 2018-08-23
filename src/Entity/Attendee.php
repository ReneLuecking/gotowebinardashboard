<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttendeeRepository")
 */
class Attendee
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Session", inversedBy="attendees")
     * @ORM\JoinColumn(nullable=false, name="sessionkey", referencedColumnName="sessionkey")
     */
    private $session;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Registrant", inversedBy="attendees")
     * @ORM\JoinColumn(nullable=false, name="registrantkey", referencedColumnName="registrantkey")
     */
    private $registrant;

    /**
     * @ORM\Column(type="integer")
     */
    private $attendance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $jointime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $leavetime;

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getRegistrant(): ?Registrant
    {
        return $this->registrant;
    }

    public function setRegistrant(?Registrant $registrant): self
    {
        $this->registrant = $registrant;

        return $this;
    }

    public function getAttendance(): ?int
    {
        return $this->attendance;
    }

    public function setAttendance(int $attendance): self
    {
        $this->attendance = $attendance;

        return $this;
    }

    public function getJointime(): ?\DateTimeInterface
    {
        return $this->jointime;
    }

    public function setJointime(\DateTimeInterface $jointime): self
    {
        $this->jointime = $jointime;

        return $this;
    }

    public function getLeavetime(): ?\DateTimeInterface
    {
        return $this->leavetime;
    }

    public function setLeavetime(\DateTimeInterface $leavetime): self
    {
        $this->leavetime = $leavetime;

        return $this;
    }
}
