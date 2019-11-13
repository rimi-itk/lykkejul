<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 * @UniqueEntity("name", message="Name already used")
 * @ApiResource(
 *     collectionOperations={"GET"},
 *     itemOperations={"GET"},
 *     normalizationContext={"groups"={"player_read"}}
 * )
 */
class Player
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"player_read", "win_read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Win", mappedBy="player", orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "ASC"})
     * @Groups({"player_read"})
     */
    private $wins;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"player_read"})
     */
    private $enabled = true;

    public function __construct()
    {
        $this->wins = new ArrayCollection();
    }

    public function getId(): ?string
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

    /**
     * @return Collection|Win[]
     */
    public function getWins(): Collection
    {
        return $this->wins;
    }

    public function addWin(Win $win): self
    {
        if (!$this->wins->contains($win)) {
            $this->wins[] = $win;
            $win->setPlayer($this);
        }

        return $this;
    }

    public function removeWin(Win $win): self
    {
        if ($this->wins->contains($win)) {
            $this->wins->removeElement($win);
            // set the owning side to null (unless already changed)
            if ($win->getPlayer() === $this) {
                $win->setPlayer(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name ?? static::class;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
