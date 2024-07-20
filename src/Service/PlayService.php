<?php

namespace App\Service;

use App\Repository\WinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WinRepository $winRepository,
        private readonly array $options
    ) {
        $this->resolveOptions();
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
            'max_number_of_wins' => $maxNumberOfWins ?? $this->options['max_number_of_wins'],
        ]);

        shuffle($players);

        return $players;
    }

    public function getWins(\DateTimeInterface $date)
    {
        return $this->winRepository->findByDate($date);
    }

    private function resolveOptions(): void {
        (new OptionsResolver())
            ->setRequired('max_number_of_wins')
            ->setAllowedTypes('max_number_of_wins', 'int')
            ->resolve($this->options);
    }

}
