<?php

namespace App\Form;

use App\Entity\Festival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Festival name',
                'constraints' => [
                    new  NotBlank(
                        message: 'This field cannot be empty',
                    ),
                    new Length(
                        min : 3,
                        max : 100,
                        minMessage: 'This field cannot be less than 3 characters',
                        maxMessage: 'This field cannot be greater than 100 characters',
                    ),
    ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Festival location',
                'constraints' => [
                    new NotBlank(
                        message: 'This field cannot be empty',
                    ),
                    new Length(
                        min : 3,
                        max : 100,
                        minMessage: 'This field cannot be less than 3 characters',
                        maxMessage: 'This field cannot be greater than 100 characters',
                    ),
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class
        ]);
    }
}
