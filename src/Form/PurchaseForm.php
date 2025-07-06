<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Festival; // Assuming you have a Festival entity
use Doctrine\ORM\EntityManagerInterface;

class PurchaseForm extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $festivals = $this->entityManager->getRepository(Festival::class)->findAll();
        $festivalChoices = [];

        $tickets = $this->entityManager->getRepository(Ticket::class)->findAll();
        $ticketChoices = [];

        foreach ($festivals as $festival) {
            $festivalChoices[$festival->getName()] = $festival;
        }

        foreach ($tickets as $ticket) {
            $ticketChoices[sprintf('%s - $%.2f', $ticket->getType(), $ticket->getPrice())] = $ticket;
        }

        $builder
            ->add('festival_id', ChoiceType::class, [
                'label' => 'Festival',
                'choices' => $festivalChoices,
                'placeholder' => 'Select a festival',
                'required' => true
            ])
            ->add('type_id', ChoiceType::class, [
                'label' => 'Ticket',
                'choices' => $ticketChoices,
                'placeholder' => 'Select a ticket type',
                'required' => true
            ])
        ;
    }
}
