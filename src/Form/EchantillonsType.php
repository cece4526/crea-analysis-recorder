<?php

namespace App\Form;

use App\Entity\Echantillons;
use App\Entity\Okara;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EchantillonsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poids_0', NumberType::class, [
                'label' => 'Poids 0',
                'required' => false,
                'scale' => 2,
            ])
            ->add('poids_1', NumberType::class, [
                'label' => 'Poids 1',
                'required' => false,
                'scale' => 2,
            ])
            ->add('poids_2', NumberType::class, [
                'label' => 'Poids 2',
                'required' => false,
                'scale' => 2,
            ])
            ->add('okara', EntityType::class, [
                'class' => Okara::class,
                'choice_label' => 'id',
                'label' => 'Okara associé',
            ])
            ->add('extrait_sec', NumberType::class, [
                'required' => false,
                'label' => 'Extrait sec',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Echantillons::class,
        ]);
    }
}
