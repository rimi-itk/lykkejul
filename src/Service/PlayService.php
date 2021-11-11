<?php

namespace App\Service;

use App\Repository\WinRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private WinRepository $winRepository,
        private array $playConfiguration
    ) {
    }

    public function getActivePlayers(int $maxNumberOfWins = null)
    {
        $query = $this->entityManager->createQuery(
            <<<'DQL'
SELECT player
  FROM App\Entity\Player player
 WHERE player.enabled = true
   AND player.id NOT IN (
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
