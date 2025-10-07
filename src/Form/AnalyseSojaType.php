<?php

namespace App\Form;

use App\Entity\AnalyseSoja;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\OF;

class AnalyseSojaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('of', EntityType::class, [
                'class' => OF::class,
                'choice_label' => 'id',
                'label' => 'OF',
            ])
            ->add('litrage_decan', IntegerType::class)
            ->add('temperature_broyage', NumberType::class, ['required' => false])
            ->add('eau', IntegerType::class)
            ->add('matiere', IntegerType::class)
            ->add('es_av_decan', NumberType::class, ['required' => false])
            ->add('es_ap_decan', NumberType::class, ['required' => false])
            ->add('control_visuel', CheckboxType::class, ['required' => false])
            ->add('debit_bicar', NumberType::class, ['required' => false])
            ->add('vitesse_diff', NumberType::class, ['required' => false])
            ->add('couple', NumberType::class, ['required' => false])
            ->add('variponds', NumberType::class, ['required' => false])
            ->add('contre_pression', NumberType::class, ['required' => false])
            ->add('initial_pilote', TextType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnalyseSoja::class,
        ]);
    }
}
