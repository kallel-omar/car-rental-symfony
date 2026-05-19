<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'You should agree to our terms.',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,

                'attr' => [
                    'autocomplete' => 'new-password',
                ],

                'constraints' => [

                    new Assert\NotBlank([
                        'message' => 'Please enter a password',
                    ]),

                    new Assert\Length([
                        'min' => 8,
                        'minMessage' =>
                            'Your password must be at least 8 characters',
                    ]),

                    new Assert\Regex([
                        'pattern' =>
                            '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',

                        'message' =>
                            'Password must contain uppercase, lowercase and a number',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
