<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Win;
use App\Repository\WinRepository;
use App\Settings\AppSettings;
use Doctrine\ORM\EntityManagerInterface;

class PlayService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WinRepository $winRepository,
        private readonly AppSettings $settings,
    ) {
    }

    /**
     * @return Player[]
     */
    public function getActivePlayers(?int $maxNumberOfWins = null): array
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
            'max_number_of_wins' => $maxNumberOfWins ?? $this->settings->maxNumberOfWins,
        ]);

        shuffle($players);

        return $players;
    }

    /**
     * @return Win[]
     */
    public function getWins(\DateTimeInterface $date): array
    {
        return $this->winRepository->findByDate($date);
    }

    public function getGrandPrizeDay(): int
    {
        return $this->settings->grandPrizeDay;
    }
}
