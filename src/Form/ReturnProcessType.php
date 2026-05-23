<?php

namespace App\Form;

use App\Entity\RentalProcess;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ReturnProcessType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder

            ->add('returnFuelLevel', ChoiceType::class, [

                'choices' => [

                    'Empty' => 'empty',
                    '1/4' => '1/4',
                    'Half' => 'half',
                    '3/4' => '3/4',
                    'Full' => 'full',
                ],

                'label' => 'Return Fuel Level',
            ])

            ->add('returnKilometers', IntegerType::class, [

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
