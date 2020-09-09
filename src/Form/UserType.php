<?php

namespace App\Form;

use App\Entity\Position;
use App\Entity\Residence;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('email')
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
            ])
            ->add('mentor', EntityType::class, [
                'label' => 'Parrain',
                'required' => false,
                'class' => User::class,
                'placeholder' => 'Choisir un parrain',
                'choice_label' => function (User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                }])
            ->add('referent', EntityType::class, [
                'label' => 'Référent',
                'required' => false,
                'class' => User::class,
                'placeholder' => 'Choisir un référent métier',
                'choice_label' => function (User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                }])
            ->add('startDate', DateType::class, [
                'format' => 'dd-MM-yyyy',
                'label' => 'Date d\'entrée',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('position', EntityType::class, [
                'class' => Position::class,
                'required' => false,
                'placeholder' => 'Choisir un métier',
                'choice_label' => 'name',
                'label' => 'Métier',
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'label' => 'Rôle',
            ])
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'required' => false,
                'placeholder' => 'Choisir un manager',
                'choice_label' => function (User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'label' => 'Manager',
            ])
            ->add('residence', EntityType::class, [
                'class' => Residence::class,
                'required' => false,
                'placeholder' => 'Choisir une résidence',
                'choice_label' => function (Residence $residence) {
                    return $residence->getName() . ' - ' . $residence->getCity();
                },
                'label' => 'Résidence',
            ])
            ->add('residencePilote', EntityType::class, [
                'class' => Residence::class,
                'required' => false,
                'placeholder' => 'Choisir une résidence pilote',
                'choice_label' => function (Residence $residence) {
                    return $residence->getName() . ' - ' . $residence->getCity();
                },
                'label' => 'Résidence pilote',
            ]);
        if (!$options['password_disabled']) {
            $builder->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password_disabled' => false,
        ]);
    }
}
