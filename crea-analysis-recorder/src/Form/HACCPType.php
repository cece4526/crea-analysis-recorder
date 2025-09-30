<?php

namespace App\Form;

use App\Entity\HACCP;
use App\Entity\OF;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HACCPType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filtre_pasteurisateur_resultat', CheckboxType::class, [
                'label' => 'Filtre pasteurisateur OK ?',
                'required' => false,
            ])
            ->add('temperature_cible', IntegerType::class, [
                'label' => 'Température cible',
            ])
            ->add('temperature_indique', IntegerType::class, [
                'label' => 'Température indiquée',
            ])
            ->add('filtre_nep_resultat', CheckboxType::class, [
                'label' => 'Filtre NEP OK ?',
                'required' => false,
            ])
            ->add('of', EntityType::class, [
                'class' => OF::class,
                'choice_label' => 'id',
                'label' => 'OF associé',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HACCP::class,
        ]);
    }
}
