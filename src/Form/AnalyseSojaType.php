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
                'choice_label' => function(OF $of) {
                    return $of->getName() . ' - ' . $of->getNumero();
                },
                'label' => 'Ordre de Fabrication',
            ])
            ->add('litrageDecan', IntegerType::class, [
                'label' => 'Litrage décantation',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('temperatureBroyage', NumberType::class, [
                'required' => false,
                'label' => 'Température broyage (°C)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('eau', IntegerType::class, [
                'label' => 'Quantité d\'eau (L)',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('matiere', IntegerType::class, [
                'label' => 'Matière (kg)',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('esAvDecan', NumberType::class, [
                'required' => false,
                'label' => 'ES avant décantation (%)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('esApDecan', NumberType::class, [
                'required' => false,
                'label' => 'ES après décantation (%)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('controlVisuel', CheckboxType::class, [
                'required' => false,
                'label' => 'Contrôle visuel',
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('debitBicar', NumberType::class, [
                'required' => false,
                'label' => 'Débit bicarbonate',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('vitesseDiff', NumberType::class, [
                'required' => false,
                'label' => 'Vitesse différentielle',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('couple', NumberType::class, [
                'required' => false,
                'label' => 'Couple',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('variponds', NumberType::class, [
                'required' => false,
                'label' => 'Variponds',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('contrePression', NumberType::class, [
                'required' => false,
                'label' => 'Contre-pression',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ],
            ])
            ->add('initialPilote', TextType::class, [
                'required' => false,
                'label' => 'Initial pilote',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnalyseSoja::class,
        ]);
    }
}
