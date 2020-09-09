<?php

namespace App\Form;

use App\Entity\ChecklistItem;
use App\Entity\Residence;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserTypeChecklist extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('checklistItems', EntityType::class, [
                'class' => ChecklistItem::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'group_by' => 'category',
                'disabled' => !$options['write_right'],

            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'write_right' => false,
        ]);
    }
}
