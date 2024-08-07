<?php

namespace App\Entity;

use App\Repository\WinRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WinRepository::class)]
class Win
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'wins')]
    private ?Player $player;

    #[ORM\Column(type: 'boolean')]
    private bool $prizeCollected = false;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getPrizeCollected(): ?bool
    {
        return $this->prizeCollected;
    }

    public function setPrizeCollected(bool $prizeCollected): self
    {
        $this->prizeCollected = $prizeCollected;

        return $this;
    }
}
