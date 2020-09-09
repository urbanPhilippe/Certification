<?php

namespace App\Form;

use App\Entity\IntegrationStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntegrationStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', IntegerType::class, ['label' => 'Position'])
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description')
            ->add('duration', TextType::class, ['label' => 'Durée (en jours)'])
            ->add('fontAwesome', TextType::class, ['label' => 'Icône FontAwesome  (fas-fa-xxxx)'])
            ->add('color', TextType::class, ['label' => 'Couleur hexadécimale  (#xxxxxx)'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IntegrationStep::class,
        ]);
    }
}
