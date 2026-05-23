<?php

namespace App\Form;

use App\Entity\RentalProcess;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalProcessType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder

            // PICKUP

            ->add('pickupType', ChoiceType::class, [

                'choices' => [

                    'Agency Pickup' => 'pickup',

                    'Delivery' => 'delivery',
                ],

                'required' => false,

                'label' => 'Pickup Method',
            ])

            ->add('deliveryAddress', TextType::class, [

                'required' => false,

                'label' => 'Delivery Address',
            ])

            ->add('pickupFuelLevel', ChoiceType::class, [

                'choices' => [

                    'Empty' => 'empty',
                    '1/4' => '1/4',
                    'Half' => 'half',
                    '3/4' => '3/4',
                    'Full' => 'full',
                ],

                'required' => false,

                'label' => 'Pickup Fuel Level',
            ])

            ->add('pickupKilometers', IntegerType::class, [

                'required' => false,

                'label' => 'Pickup Kilometers',
            ])

            ->add('pickupTime', TimeType::class, [

                'widget' => 'single_text',

                'required' => false,

                'label' => 'Pickup Time',
            ])

            // RETURN

            ->add('returnFuelLevel', ChoiceType::class, [

                'choices' => [

                    'Empty' => 'empty',
                    '1/4' => '1/4',
                    'Half' => 'half',
                    '3/4' => '3/4',
                    'Full' => 'full',
                ],

                'required' => false,

                'label' => 'Return Fuel Level',
            ])

            ->add('returnKilometers', IntegerType::class, [

                'required' => false,

                'label' => 'Return Kilometers',
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {

        $resolver->setDefaults([

            'data_class' => RentalProcess::class,
        ]);
    }
}
