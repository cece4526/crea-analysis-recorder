use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\OF;
<?php

namespace App\Form;

use App\Entity\Okara;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OkaraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('of', EntityType::class, [
                'class' => OF::class,
                'choice_label' => 'id',
                'label' => 'OF associé',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Okara::class,
        ]);
    }
}
