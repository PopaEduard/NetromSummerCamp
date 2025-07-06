<?php

namespace App\Form;

use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class FestivalArtistForm extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $artists = $this->entityManager->getRepository(Artist::class)->findAll();
        $artistsChoices = [];

        foreach ($artists as $artist) {
            $artistsChoices[$artist->getName()] = $artist;
        }

        $builder
            ->add('artist_id', EntityType::class, [
                'label' => 'Artists',
                'choices' => $artistsChoices,
                'multiple' => true,
                'placeholder' => 'Select artists',
                'required' => true
            ])
        ;
    }
}
