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

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('dueDate')
            ->add('category', EntityType::class, ['class'=>Category::class, 'choice_label'=>'name', 'constraints'=>(new Type('App\Entity\Category', 'Categoria nu este valida.'))])
            ->add('submit', SubmitType::class, [
                'label' => 'Salveaza',
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
