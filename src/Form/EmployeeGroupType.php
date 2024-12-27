<?php

namespace App\Form;

use App\Entity\EmployeeGroup;
use App\Entity\Film;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
            ])
            ->add('film', EntityType::class, [
                'class' => Film::class,
                'choice_label' => 'name',
                'expanded' => true,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label'
                => function(User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'bg-teal-500 text-white p-2 rounded hover:bg-teal-800',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeGroup::class,
        ]);
    }
}
