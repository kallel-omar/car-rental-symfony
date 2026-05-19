<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder

            ->add('plainPassword', RepeatedType::class, [

                'type' => PasswordType::class,

                'options' => [

                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],

                'first_options' => [

                    'label' => 'New Password',

                    'constraints' => [

                        new NotBlank([
                            'message' =>
                                'Please enter a password',
                        ]),

                        new Length([
                            'min' => 8,

                            'minMessage' =>
                                'Your password must be at least 8 characters',

                            'max' => 4096,
                        ]),

                        new Regex([

                            'pattern' =>
                                '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',

                            'message' =>
                                'Password must contain uppercase, lowercase and a number',
                        ]),
                    ],
                ],

                'second_options' => [

                    'label' => 'Confirm Password',
                ],

                'invalid_message' =>
                    'Passwords do not match.',

                'mapped' => false,
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {

        $resolver->setDefaults([]);
    }
}
