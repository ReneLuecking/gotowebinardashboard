<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebinarRepository")
 */
class Webinar
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $webinarkey;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organizer", inversedBy="webinars")
     * @ORM\JoinColumn(nullable=false, name="organizerkey", referencedColumnName="organizerkey")
     */
    private $organizer;

    /**
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $webinarid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Session", mappedBy="webinar", orphanRemoval=true)
     */
    private $sessions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registrant", mappedBy="webinar", orphanRemoval=true)
     */
    private $registrants;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->registrants = new ArrayCollection();
    }

    public function getwebinarkey(): ?int
    {
        return $this->webinarkey;
    }

    public function setwebinarkey(int $webinarkey): self
    {
        $this->webinarkey = $webinarkey;

        return $this;
    }

    public function getOrganizer(): ?Organizer
    {
        return $this->organizer;
    }

    public function setOrganizer(?Organizer $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getwebinarid(): ?int
    {
        return $this->webinarid;
    }

    public function setwebinarid(int $webinarid): self
    {
        $this->webinarid = $webinarid;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setWebinar($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->contains($session)) {
            $this->sessions->removeElement($session);
            // set the owning side to null (unless already changed)
            if ($session->getWebinar() === $this) {
                $session->setWebinar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Registrant[]
     */
    public function getRegistrants(): Collection
    {
        return $this->registrants;
    }

    public function addRegistrant(Registrant $registrant): self
    {
        if (!$this->registrants->contains($registrant)) {
            $this->registrants[] = $registrant;
            $registrant->setWebinar($this);
        }

        return $this;
    }

    public function removeRegistrant(Registrant $registrant): self
    {
        if ($this->registrants->contains($registrant)) {
            $this->registrants->removeElement($registrant);
            // set the owning side to null (unless already changed)
            if ($registrant->getWebinar() === $this) {
                $registrant->setWebinar(null);
            }
        }

        return $this;
    }
}
