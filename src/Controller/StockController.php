<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\HelloService;
use App\Service\StockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class StockController extends AbstractController
{
    public function __construct(private readonly StockService $stockService)
    {

    }

    #[Route('/hello')]
    public function index(): Response
    {
        return new Response('Krovan83');
    }

    #[Route('/hello/{name}')]
    public function getHelloName(string $name): Response
    {
        return new Response('Krovan ' . $name);
    }

    #[Route(path: '/hello/creation/{number}/{cartrige}', methods: ['GET'])]
    public function getHelloCreation(int $number, int $cartrige): Response
    {
        $randomnumbers = $this->stockService->getRandomNumbers();
        $clip = $randomnumbers[1];
        $meHim = $randomnumbers[0];
        if ($meHim === $number) {
            if ($clip === $cartrige or $clip + 1 === $meHim or $clip - 2 === $meHim) {
                return new Response('User win!');
            } else {
                return new Response('please repeat');
            }
        } else {
            if ($clip === $cartrige or $clip + 1 === $meHim or $clip - 2 === $meHim) {
                return new Response('User died');
            } else {
                return new Response('please repeat');
            }
        }
    }

}