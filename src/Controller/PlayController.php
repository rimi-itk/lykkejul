<?php

namespace App\Controller;

use App\Entity\Win;
use App\Form\Type\PlayType;
use App\Repository\WinRepository;
use App\Service\PlayService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="play_")
 */
class PlayController extends AbstractController
{
    /**
     * @Route(name="play")
     */
    public function play(Request $request, PlayService $playService, EntityManagerInterface $entityManager)
    {
        $all = null !== $request->get('all');
        $players = $playService->getActivePlayers($all ? 1000 : null);
        $name = 'Mikkel';
        if (0 === strcasecmp($name, $request->get('all'))) {
            foreach ($players as $player) {
                if (0 === strcasecmp($name, $player->getName())) {
                    $players = array_fill(0, count($players), $player);
                    break;
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

        return $this->render('play/play.html.twig', [
            'form' => $form->createView(),
            'players' => $players,
            'wins_today' => $playService->getWins(new \DateTime()),
        ]);
    }

    /**
     * @Route("/wins", name="wins")
     */
    public function wins(Request $request, WinRepository $repository)
    {
        $wins = $repository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('play/wins.html.twig', [
            'wins' => $wins,
        ]);
    }
}
