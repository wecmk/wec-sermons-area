<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PublishQuestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Number', HiddenType::class)
            ->add('publishDate', DateTimeType::class, ['data' => new \DateTime()])
            ->add('scheduleSubsequentQuestions', CheckboxType::class, ['mapped' => false, 'data' => false, 'required' => false]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults(['data_class' => 'Wec\QuestionsBundle\Entity\Question']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wec_questionsbundle_question';
    }
}
