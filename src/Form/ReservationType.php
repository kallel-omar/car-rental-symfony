<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Car;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReservationType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder

            ->add('car', EntityType::class, [
                'class' => Car::class,

                'choice_label' => function (Car $car) {

                    return
                        $car->getBrand().' '
                        .$car->getModel();
                },

                'placeholder' => 'Choose a car',
            ])

            // USER PROFILE FIELDS

            ->add('fullName', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])

            ->add('phoneNumber', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])

            // RESERVATION DATES

            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
            ])

            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
            ])

            // USER LICENSE INFO

            ->add('licenseIssueDate', DateType::class, [
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
            ])

            ->add('cinImage', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])

            ->add('licenseImage', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])

        ;
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {

        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
