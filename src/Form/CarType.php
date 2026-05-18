<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CarType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder

            ->add('brand')

            ->add('model')

            ->add('registrationNumber')

            ->add('pricePerDay')

            ->add('image', FileType::class, [

                'mapped' => false,

                'required' => false,
            ])

            ->add('year')

            ->add('transmission', ChoiceType::class, [

                'choices' => [

                    'Automatic' => 'Automatic',

                    'Manual' => 'Manual',
                ],
            ])

            ->add('fuelType', ChoiceType::class, [

                'choices' => [

                    'Petrol' => 'Petrol',

                    'Diesel' => 'Diesel',

                    'Hybrid' => 'Hybrid',

                    'Electric' => 'Electric',
                ],
            ])

            ->add('seats')

            ->add('description', TextareaType::class, [

                'required' => false,
            ])

            ->add('status', ChoiceType::class, [

                'choices' => [

                    '🟢 Available' => 'available',

                    '🔴 Unavailable' => 'unavailable',
                ],

                'expanded' => true,

                'multiple' => false,
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {

        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
