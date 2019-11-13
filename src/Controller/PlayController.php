<?php

namespace App\Controller;

use App\Entity\Win;
use App\Form\Type\PlayType;
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
        $players = $playService->getActivePlayers();

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
}
