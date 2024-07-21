<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Win;
use App\Form\PlayType;
use App\Repository\WinRepository;
use App\Service\PlayService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: 'play_')]
class PlayController extends AbstractController
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private readonly array $options
    ) {
        $this->resolveOptions();
    }

    #[Route(name: 'play')]
    public function play(Request $request, PlayService $playService, EntityManagerInterface $entityManager): Response
    {
        $all = null !== $request->get('all');
        $players = $playService->getActivePlayers($all ? 1000 : null);

        $name = 'Mikkel';
        if (0 === strcasecmp($name, (string) $request->get('all'))) {
            $names = ['Mikkel', 'Lene'];
            $candidates = array_filter(
                array_map(static function ($name) use ($players) {
                    $matches = array_filter(
                        $players,
                        static fn (Player $player) => 0 === strcasecmp($name, (string) $player->getName())
                    );

                    return reset($matches);
                }, $names)
            );

            if (\count($candidates) > 0) {
                foreach ($players as $index => &$player) {
                    $player = $candidates[$index % \count($candidates)];
                }
            }
        }

        $win = new Win();
        $form = $this->createForm(PlayType::class, $win, [
            'players' => $players,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($win);
            $entityManager->flush();

            return $this->redirectToRoute('play_play');
        }

        $playOptions = $this->options['play'] ?? [];
        if ($date = $request->get('date')) {
            try {
                $playOptions['date'] = (new \DateTimeImmutable($date))->format(\DateTimeImmutable::ATOM);
            } catch (\Exception) {
            }
        }

        return $this->render('play/play.html.twig', [
            'form' => $form->createView(),
            'players' => $players,
            'wins_today' => $playService->getWins(new \DateTime()),
            'messages' => $this->options['messages'] ?? null,
            'play_options' => $playOptions,
        ]);
    }

    #[Route(path: '/wins', name: 'wins')]
    public function wins(WinRepository $repository): Response
    {
        $wins = $repository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('play/wins.html.twig', [
            'wins' => $wins,
        ]);
    }

    private function resolveOptions(): void
    {
        (new OptionsResolver())
            ->setRequired('messages')
            ->setAllowedTypes('messages', 'array')
            ->setRequired('play')
            ->setAllowedTypes('play', 'array')
            ->resolve($this->options);
    }
}
