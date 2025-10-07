<?php

namespace App\Form;

use App\Entity\DecanteurCereales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\OF;

class DecanteurCerealesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('of', EntityType::class, [
                'class' => OF::class,
                'choice_label' => 'id',
                'label' => 'OF',
            ])
            ->add('es_av_decan', NumberType::class, ['required' => false, 'label' => 'ES avant décantation'])
            ->add('es_ap_decan', NumberType::class, ['required' => false, 'label' => 'ES après décantation'])
            ->add('vitesse_diff', NumberType::class, ['required' => false, 'label' => 'Vitesse différentielle'])
            ->add('variponds', NumberType::class, ['required' => false, 'label' => 'Variponds'])
            ->add('couple', NumberType::class, ['required' => false, 'label' => 'Couple'])
            ->add('contre_pression', NumberType::class, ['required' => false, 'label' => 'Contre pression'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DecanteurCereales::class,
        ]);
    }
}
