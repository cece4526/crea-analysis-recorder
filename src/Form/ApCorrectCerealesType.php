<?php

namespace App\Form;

use App\Entity\ApCorrectCereales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApCorrectCerealesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('tank', IntegerType::class)
            ->add('eauAjouter', IntegerType::class)
            ->add('produitFini', IntegerType::class)
            ->add('esTank', NumberType::class, [
                'scale' => 2,
            ])
            ->add('culot', NumberType::class, [
                'scale' => 2,
            ])
            ->add('ph', NumberType::class, [
                'scale' => 2,
            ])
            ->add('densiter', NumberType::class, [
                'scale' => 2,
            ])
            ->add('proteine', NumberType::class, [
                'scale' => 2,
            ])
            ->add('initialPilote', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApCorrectCereales::class,
        ]);
    }
}
