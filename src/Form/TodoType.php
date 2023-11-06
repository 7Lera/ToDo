<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', null, [
            'constraints' => [
                new NotBlank(['message' => 'Title cannot be blank.']),
                new Length(['max' => 255, 'maxMessage' => 'Title cannot be longer than {{ limit }} characters.']),
            ],
        ])
        ->add('description', null, [
            'constraints' => [
                new NotBlank(['message' => 'Description cannot be blank.']),
                new Length(['max' => 1000, 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
            ],
        ])
        ->add('dueDate')
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'constraints' => [
                new Type(['type' => 'App\Entity\Category', 'message' => 'Categoria nu este valida.']),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Salveaza',
            'attr' => [
                'class' => 'btn-save',
            ],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}



