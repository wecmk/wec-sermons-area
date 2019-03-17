<?php

namespace App\Form;

use App\Entity\ContactUsFormResults;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name')
            ->add('Email')
            ->add('Message', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class)
            ->add('Submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactUsFormResults::class,
        ]);
    }
}
