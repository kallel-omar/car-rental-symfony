<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EditProfileType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder
            ->add('email')
            ->add('fullName', TextType::class)

            ->add('phoneNumber', TextType::class)

            ->add('licenseIssueDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])

            ->add('cinImage', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])

            ->add('licenseImage', FileType::class, [
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {

        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
