<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', TextType::class, [
            'attr' => [
                'placeholder' => 'user@mail.com',
            ],
            'constraints' => [
                new NotBlank(),
                new Email(),
                new Length(['min' => 5, 'max' => 100])
            ]
        ]);

        if (!$options['is_edit']) {
            $builder->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Password',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 36])

                ]
            ]);
        }

        if ($options['is_edit']) {
            $builder->add('role', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Role',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false
        ]);
    }
}
