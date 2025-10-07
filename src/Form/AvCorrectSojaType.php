<?php

namespace App\Form;

use App\Entity\AvCorrectSoja;
use App\Entity\OF;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvCorrectSojaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('tank', IntegerType::class)
            ->add('eau', IntegerType::class)
            ->add('matiere', IntegerType::class)
            ->add('produitFini', IntegerType::class)
            ->add('esTank', NumberType::class, [
                'scale' => 2,
            ])
            ->add('initialPilote', TextType::class)
            ->add('_of', EntityType::class, [
                'class' => OF::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvCorrectSoja::class,
        ]);
    }
}
