<?php

namespace App\Form;

use App\Entity\ApCorrectSoja;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DecimalType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApCorrectSojaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ph', DecimalType::class, [
                'label' => 'pH',
                'scale' => 2,
                'required' => false,
            ])
            ->add('urease', DecimalType::class, [
                'label' => 'Uréease',
                'scale' => 2,
                'required' => false,
            ])
            ->add('humidite', DecimalType::class, [
                'label' => 'Humidité',
                'scale' => 2,
                'required' => false,
            ])
            ->add('proteine', DecimalType::class, [
                'label' => 'Protéine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('cellulose', DecimalType::class, [
                'label' => 'Cellulose',
                'scale' => 2,
                'required' => false,
            ])
            ->add('matiereMinerale', DecimalType::class, [
                'label' => 'Matière minérale',
                'scale' => 2,
                'required' => false,
            ])
            ->add('matiereGrasse', DecimalType::class, [
                'label' => 'Matière grasse',
                'scale' => 2,
                'required' => false,
            ])
            ->add('lysine', DecimalType::class, [
                'label' => 'Lysine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('methionine', DecimalType::class, [
                'label' => 'Méthionine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('tryptophane', DecimalType::class, [
                'label' => 'Tryptophane',
                'scale' => 2,
                'required' => false,
            ])
            ->add('cystine', DecimalType::class, [
                'label' => 'Cystine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('threonine', DecimalType::class, [
                'label' => 'Thréonine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('valine', DecimalType::class, [
                'label' => 'Valine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('arginine', DecimalType::class, [
                'label' => 'Arginine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('histidine', DecimalType::class, [
                'label' => 'Histidine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('isoleucine', DecimalType::class, [
                'label' => 'Isoleucine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('leucine', DecimalType::class, [
                'label' => 'Leucine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('phenylalanine', DecimalType::class, [
                'label' => 'Phénylalanine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('tyrosine', DecimalType::class, [
                'label' => 'Tyrosine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('alanine', DecimalType::class, [
                'label' => 'Alanine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('aspartate', DecimalType::class, [
                'label' => 'Aspartate',
                'scale' => 2,
                'required' => false,
            ])
            ->add('glutamate', DecimalType::class, [
                'label' => 'Glutamate',
                'scale' => 2,
                'required' => false,
            ])
            ->add('glycine', DecimalType::class, [
                'label' => 'Glycine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('proline', DecimalType::class, [
                'label' => 'Proline',
                'scale' => 2,
                'required' => false,
            ])
            ->add('serine', DecimalType::class, [
                'label' => 'Sérine',
                'scale' => 2,
                'required' => false,
            ])
            ->add('acideGlutamique', DecimalType::class, [
                'label' => 'Acide glutamique',
                'scale' => 2,
                'required' => false,
            ])
            ->add('acideAspartique', DecimalType::class, [
                'label' => 'Acide aspartique',
                'scale' => 2,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApCorrectSoja::class,
        ]);
    }
}
