<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enums\ActionEnum;
use App\Entity\Application;
use App\Entity\Portfolio;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Application1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity')
            ->add('price')
            ->add('action', ChoiceType::class, [
              'choices' => [
                  'Buy' => ActionEnum::BUY,
                  'Sell' => ActionEnum::SELL,
              ],
              'choice_value' => fn (?ActionEnum $enum) => $enum ? $enum->value : null, // Преобразование в строку
              'choice_label' => fn (ActionEnum $enum) => ucfirst($enum->value), // Отображаемый текст
              'expanded' => true, // Радио-кнопки (можно убрать для выпадающего списка)
              'multiple' => false, // Одиночный выбор
              'data' => $options['data']?->getAction()?->value ?? null, // Убедиться, что начальное значение передано как строка
          ])
            ->add('portfolio', EntityType::class, [
                'class' => Portfolio::class,
                'choice_label' => 'id',
            ])
            ->add('stock', EntityType::class, [
                'class' => Stock::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
