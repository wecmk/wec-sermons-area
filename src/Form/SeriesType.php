<?php

namespace App\Form;

use App\Entity\Series;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uuid', TextType::class, ['disabled' => true])
            ->add('name')
            ->add('description', TextareaType::class, ['required' => false])
            ->add('complete')
            ->add('isPublic')
            ->add('deletedAt', DateTimeType::class, ['disabled' => true])
            ->add('createdAt', DateTimeType::class, ['disabled' => true])
            ->add('updatedAt', DateTimeType::class, ['disabled' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Series::class,
        ]);
    }
}
