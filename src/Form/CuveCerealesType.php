<?php

namespace App\Form;

use App\Entity\CuveCereales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\OF;

class CuveCerealesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('of', EntityType::class, [
                'class' => OF::class,
                'choice_label' => function(OF $of) {
                    return $of->getName() . ' - ' . $of->getNumero();
                },
                'label' => 'Ordre de Fabrication',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('cuve', IntegerType::class, [
                'label' => 'Numéro de cuve',
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                    'placeholder' => 'Ex: 1, 2, 3...',
                ],
            ])
            ->add('debitEnzyme', NumberType::class, [
                'required' => false,
                'label' => 'Débit enzyme (L/h)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'placeholder' => '0.00',
                ],
            ])
            ->add('temperatureHydrolise', NumberType::class, [
                'required' => false,
                'label' => 'Température hydrolyse (°C)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'max' => '100',
                    'placeholder' => '60.00',
                ],
            ])
            ->add('matiere', NumberType::class, [
                'required' => false,
                'label' => 'Matière (kg)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'placeholder' => '0.00',
                ],
            ])
            ->add('quantiteEnzyme', NumberType::class, [
                'required' => false,
                'label' => 'Quantité enzyme (ml)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'placeholder' => '0.00',
                ],
            ])
            ->add('controlVerre', CheckboxType::class, [
                'required' => false,
                'label' => 'Contrôle verre effectué',
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('initialPilote', TextType::class, [
                'required' => false,
                'label' => 'Initiales pilote',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 10,
                    'placeholder' => 'Ex: J.D.',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CuveCereales::class,
        ]);
    }
}
