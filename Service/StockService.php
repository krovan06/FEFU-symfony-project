<?php

namespace App\Service;

class StockService
{
    public function getStock(): string {
        return 'I am stock';
    }

    public function getRandomNumbers(): array {
        $firstNumber = rand(1, 2);
        $secondNumber = rand(1, 8);
        return [$firstNumber, $secondNumber];
    }
}