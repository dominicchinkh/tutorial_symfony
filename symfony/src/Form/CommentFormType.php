<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class, [

                /*
                 * 'mapped' => false tells the form component that the field does not 
                 * correspond to a property on the underlying data class (i.e. the 
                 * Comment entity).
                 *
                 *   1. There is no setId() method
                 *   2. Doctrine handles the ID, not the user
                 * 
                 * People usually add a hidden, unmapped id field to a form for client-
                 * side reasons
                 * 
                 *   1. JavaScript/AJAX: Your front-end JavaScript might need to know 
                 *      the ID of the comment to perform DOM manipulations, dynamic reloads,
                 *      or API calls
                 *   2. Nested Forms / Collections: If you are rendering multiple comment 
                 *      forms on a single page, the hidden ID helps your controller or 
                 *      JavaScript figure out exactly which comment is being interacted with
                 * 
                 */
                'mapped' => false,
            ])
            ->add('comment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
