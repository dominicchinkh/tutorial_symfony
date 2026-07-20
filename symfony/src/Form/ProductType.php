<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\CommentFormType;
use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('description', TextareaType::class, [
                // Symfony text fields "trim spaces" automatically. When your component re-renders, 
                
                // the space will disappear. To fix this, set the trim option of your field to false:
                'trim' => false,
            ])

            // By default, the PasswordType does not re-fill the <input type="password"> after a submit.
            // To fix this, set the always_empty option to false in your form:

            // ->add('plainPassword', PasswordType::class, [
            //     'always_empty' => false,
            // ])

            ->add('comments', LiveCollectionType::class, [
                'entry_type' => CommentFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
