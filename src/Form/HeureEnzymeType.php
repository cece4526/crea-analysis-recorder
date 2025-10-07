<?php

namespace App\Form;

use App\Entity\HeureEnzyme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\OF;

class HeureEnzymeType extends AbstractType
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
                'required' => true,
            ])
            ->add('heureDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de début',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('heureFin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de fin',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('typeEnzyme', ChoiceType::class, [
                'label' => 'Type d\'enzyme',
                'choices' => [
                    'α-amylase' => 'α-amylase',
                    'β-glucanase' => 'β-glucanase',
                    'xylanase' => 'xylanase',
                    'cellulase' => 'cellulase',
                    'protéase' => 'protéase',
                    'lipase' => 'lipase',
                    'autre' => 'autre',
                ],
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('quantiteEnzyme', NumberType::class, [
                'label' => 'Quantité d\'enzyme',
                'required' => false,
                'scale' => 3,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.001',
                    'min' => '0',
                ],
            ])
            ->add('temperatureHydrolyse', NumberType::class, [
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
            ->add('phInitial', NumberType::class, [
                'required' => false,
                'label' => 'pH initial',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'max' => '14',
                ],
            ])
            ->add('phFinal', NumberType::class, [
                'required' => false,
                'label' => 'pH final',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'max' => '14',
                ],
            ])
            ->add('dureeHydrolyse', IntegerType::class, [
                'required' => false,
                'label' => 'Durée hydrolyse (minutes)',
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                ],
            ])
            ->add('efficaciteHydrolyse', NumberType::class, [
                'required' => false,
                'label' => 'Efficacité hydrolyse (%)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'max' => '100',
                ],
            ])
            ->add('conformite', CheckboxType::class, [
                'label' => 'Conformité',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('observations', TextareaType::class, [
                'label' => 'Observations',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Observations sur l\'hydrolyse enzymatique...',
                ],
            ])
            ->add('operateur', TextType::class, [
                'label' => 'Opérateur',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'opérateur',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeureEnzyme::class,
        ]);
    }
}
