<?php

namespace App\Form;

use App\Entity\QuantiteEnzyme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuantiteEnzymeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pourcentage', NumberType::class, [
                'label' => 'Pourcentage',
                'required' => false,
            ])
            ->add('quantite', NumberType::class, [
                'label' => 'Quantité',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuantiteEnzyme::class,
        ]);
    }
}
