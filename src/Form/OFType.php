use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Production;
<?php

namespace App\Form;

use App\Entity\OF;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OFType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('numero', IntegerType::class, [
                'label' => 'Numéro',
            ])
            ->add('nature', TextType::class, [
                'label' => 'Nature',
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('production', EntityType::class, [
                'class' => Production::class,
                'choice_label' => 'name',
                'label' => 'Production associée',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OF::class,
        ]);
    }
}
