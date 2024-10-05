<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\PublicSermon;
use App\Repository\EventRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicSermonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title')
            ->add('Speaker')
            ->add(
                'Event',
                EntityType::class,
                ['class' => Event::class, 'query_builder' => fn (EventRepository $er) => $er->createQueryBuilder('u')
                    ->where('u.id = :id')
                    ->setParameter(':id', $options['event']->getId()->toString())
                    ->orderBy('u.Date', 'DESC')
                    ->setMaxResults(100), 'choice_label' => fn (Event $event) => $event->getDate()->format('d-m-Y') . " " .
                    $event->getApm() . "  " .
                    $event->getReading() . "  " .
                    $event->getTitle()  . " - " .
                    $event->getSpeaker()->getName()]
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PublicSermon::class,
            'event' => null,
        ]);
    }
}
