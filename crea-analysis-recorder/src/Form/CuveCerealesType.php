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
                'choice_label' => 'id',
                'label' => 'OF',
            ])
            ->add('cuve', IntegerType::class)
            ->add('debit_enzyme', NumberType::class, ['required' => false])
            ->add('temperature_hydrolise', NumberType::class, ['required' => false])
            ->add('quantite_enzyme2', NumberType::class, ['required' => false])
            ->add('matiere', NumberType::class, ['required' => false])
            ->add('control_verre', CheckboxType::class, ['required' => false])
            ->add('initial_pilote', TextType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CuveCereales::class,
        ]);
    }
}
