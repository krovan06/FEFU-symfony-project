<?php

namespace App\Entity;

use App\Enums\ActionEnum;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    // ID заявки
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Портфель пользователя
    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Portfolio $portfolio = null;

    // Ценная бумага в заявке
    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stock $stock = null;

    // Количество
    #[ORM\Column]
    private ?int $quantity = null;

    // Цена за единицу
    #[ORM\Column]
    private ?float $price = null;

    // Статус заявки (enum)
    #[ORM\Column(type: 'string', length: 10)]
    private ?string $action = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolio;
    }

    public function setPortfolio(?Portfolio $portfolio): static
    {
        $this->portfolio = $portfolio;
        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    // Методы для работы с enum ActionEnum
    public function getAction(): ?ActionEnum
    {
        return $this->action ? ActionEnum::tryFrom($this->action) : null;
    }

    public function setAction(ActionEnum|string|null $action): static
    {
        $this->action = is_string($action) ? $action : $action?->value;
        return $this;
    }
}
