<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use App\Entity\Win;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator
    )
    {
    }

    #[\Override]
    #[Route(path: '/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->adminUrlGenerator;

        return $this->redirect($routeBuilder->setController(PlayerCrudController::class)->generateUrl());
    }

    #[\Override]
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->getParameter('site_name'));
    }

    #[\Override]
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Players', 'icon class', Player::class);
        yield MenuItem::linkToCrud('Wins', 'icon class', Win::class);
        yield MenuItem::linktoRoute('Play', 'icon class', 'play_play');
    }

    #[\Override]
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setMenuItems([
                // Remove the logout menu item.
            ]);
    }
}
