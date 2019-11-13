<?php

namespace App\Service;

use App\Repository\WinRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var WinRepository */
    private $winRepository;

    /** @var array */
    private $playConfiguration;

    public function __construct(EntityManagerInterface $entityManager, WinRepository $winRepository, array $playConfiguration)
    {
        $this->entityManager = $entityManager;
        $this->winRepository = $winRepository;
        $this->playConfiguration = $playConfiguration;
    }

    public function getActivePlayers(int $maxNumberOfWins = null)
    {
        $query = $this->entityManager->createQuery(<<<'DQL'
SELECT player
FROM App\Entity\Player player
WHERE player.id
NOT IN (
SELECT p.id
FROM App\Entity\Player p
JOIN p.wins w
GROUP BY w.player
HAVING COUNT(w.id) >= :max_number_of_wins
)
DQL
        );

        $players = $query->execute([
            'max_number_of_wins' => $maxNumberOfWins ?? $this->playConfiguration['max_number_of_wins'],
        ]);

        shuffle($players);

        return $players;
    }

    public function getWins(\DateTimeInterface $date)
    {
        return $this->winRepository->findByDate($date);
    }
}
