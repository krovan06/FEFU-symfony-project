<?php

namespace App\Controller;

use App\Entity\Application;
use App\Enums\ActionEnum;
use App\Form\Application1Type;
use App\Repository\ApplicationRepository;
use App\Repository\DepositaryRepository;
use App\Repository\PortfolioRepository;
use App\Repository\StockRepository;
use App\Repository\UserRepository;
use App\Service\DealService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/application')]
final class ApplicationController extends AbstractController
{
    public function __construct(
        private readonly StockRepository $stockRepository,
        private readonly UserRepository $userRepository,
        private readonly ApplicationRepository $applicationRepository,
        private readonly PortfolioRepository $portfolioRepository,
        private readonly DepositaryRepository $depositoryRepository,
        private readonly DealService $dealService
    ) {}

    #[Route(name: 'app_application_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('application/index.html.twig', [
            'applications' => $this->applicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_application_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $application = new Application();
        $form = $this->createForm(Application1Type::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $action = $form->get('action')->getData();
            if (is_string($action)) {
                $application->setAction(ActionEnum::from($action));
            }

            $this->applicationRepository->saveApplication($application);
            return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('application/new.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_application_show', methods: ['GET'])]
    public function show(Application $application): Response
    {
        return $this->render('application/show.html.twig', [
            'application' => $application,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_application_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Application $application): Response
    {
        $form = $this->createForm(Application1Type::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $action = $form->get('action')->getData();
            if (is_string($action)) {
                $application->setAction(ActionEnum::from($action));
            }

            $this->applicationRepository->saveApplication($application);
            return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('application/edit.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_application_delete', methods: ['POST'])]
    public function delete(Request $request, Application $application): Response
    {
        if ($request->request->has('_token') && 
            $this->isCsrfTokenValid('delete'.$application->getId(), $request->get('_token'))) {
            $this->applicationRepository->removeApplication($application);
        }

        return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/my/view', name: 'app_view_my_application', methods: ['GET'])]
    public function viewMyApplications(Request $request): Response
    {
        $depositors = $this->depositoryRepository->findAll();
        $user = $this->getUser();
        if ($user == null) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $userPortfolios = $user->getPortfolios();
        if (empty($userPortfolios)) {
            return $this->json(['message' => 'No portfolios found for this user.'], Response::HTTP_NOT_FOUND);
        }

        $userApplications = [];
        foreach ($this->applicationRepository->findAll() as $application) {
            foreach ($userPortfolios as $userPortfolio) {
                if ($application->getPortfolio()->getId() == $userPortfolio->getId()) {
                    $userApplications[] = $application;
                }
            }
        }

        return $this->render('glass/stock_glass_my_application.html.twig', [
            'stocks' => $this->stockRepository->findAll(),
            'applications' => $userApplications,
            'depositories' => $depositors, // Или отфильтровать по пользователю
            'portfolios' => $userPortfolios,
            'BUY' => ActionEnum::BUY,
            'SELL' => ActionEnum::SELL,
        ]);
    }

    #[Route('/applications', name: 'app_stock_glass_view', methods: ['GET'])]

    public function viewApplications(): Response
    {
        // Получаем список всех акций через StockRepository
        $application = $this->applicationRepository->findAll();
        $stocks = $this->stockRepository->findAll();

        // Если ничего не найдено, возвращаем сообщение
        if (empty($stocks)) {
            return $this->json([
                'message' => 'No stocks found in the database.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Возвращаем данные в Twig-шаблон
        return $this->render('glass/stock_glass_view.html.twig', [
            'stocks' => $stocks,
            'application' => $application,
            'BUY' => ActionEnum::BUY,
            'SELL' => ActionEnum::SELL,
        ]);
    }
}
