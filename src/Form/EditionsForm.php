<?php

namespace App\Form;

use App\Entity\Editions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, ['label' => 'Edition start date'])
            ->add('endDate', DateType::class, [
                'label' => 'Edition end date'
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $edition = $event->getData();
            if($edition->getStartDate() && $edition->getEndDate() && $edition->getStartDate() > $edition->getEndDate()) {
                $event->getForm()->get('endDate')->addError(new FormError('End date cannot be after the start date'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Editions::class,
        ]);
    }
}
