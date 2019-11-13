<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WinRepository")
 * @ApiResource(
 *     collectionOperations={"GET"},
 *     itemOperations={"GET"},
 *     normalizationContext={"groups"={"win_read"}}
 * )
 */
class Win
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups("win_read")
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="wins")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("win_read")
     */
    private $player;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("win_read")
     */
    private $prizeCollected = false;

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
