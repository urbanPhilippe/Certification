<?php

namespace App\Form;

use App\Entity\Position;
use App\Entity\Role;
use App\Entity\UserSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('position', EntityType::class, [
                'class' => Position::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Fonction',
            ])

            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Rôle',
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
