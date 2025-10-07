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
            ->add('date', null, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('tank', null, [
                'label' => 'Tank',
            ])
            ->add('eauAjouter', null, [
                'label' => 'Eau ajoutée',
            ])
            ->add('matiere', null, [
                'label' => 'Matière',
            ])
            ->add('produitFini', null, [
                'label' => 'Produit fini',
            ])
            ->add('esTank', null, [
                'label' => 'ES Tank',
            ])
            ->add('culot', null, [
                'label' => 'Culot',
            ])
            ->add('ph', null, [
                'label' => 'pH',
            ])
            ->add('densiter', null, [
                'label' => 'Densité',
            ])
            ->add('proteine', null, [
                'label' => 'Protéine',
            ])
            ->add('initialPilote', null, [
                'label' => 'Initial Pilote',
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
