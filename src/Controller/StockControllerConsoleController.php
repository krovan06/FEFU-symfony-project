<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\StockRepository;
use App\Repository\UserRepository; // Добавьте это
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;

#[Route('/stock/controller/console')]
#[IsGranted('ROLE_ADMIN')]
final class StockControllerConsoleController extends AbstractController
{
    #[Route(name: 'app_stock_controller_console_index', methods: ['GET'])]
    public function index(StockRepository $stockRepository): Response
    {
        return $this->render('stock_controller_console/index.html.twig', [
            'stocks' => $stockRepository->findAll(),
        ]);
    }

    // Ваши другие методы...

    #[Route('/admin/users', name: 'admin_users')]
    public function adminUsers(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        $admins = [];
        $regularUsers = [];

        foreach ($users as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                $admins[] = $user;
            } else {
                $regularUsers[] = $user;
            }
        }

        if ($request->isMethod('POST')) {
            $adminIds = $request->request->all('admins', []); // Получаем ID админов из формы

            foreach ($users as $user) {
                if (in_array($user->getId(), $adminIds)) {
                    $user->setRoles(['ROLE_ADMIN']);
                } else {
                    $user->setRoles(['ROLE_USER']);
                }
                $entityManager->persist($user);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Роли обновлены!');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'admins' => $admins,
            'regularUsers' => $regularUsers,
        ]);
    }
}
