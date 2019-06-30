<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', HiddenType::class, [
                'block_prefix' => 'status',
            ])
            ->add('lang', ChoiceType::class, [
                'choices' => [
                    'Select Language' => '',
                    'English' => 'en',
                    'Arabic' => 'ar',
                ],
                'label' => 'Language',
                'attr' => [
                    'class' => 'language-value',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'placeholder' => 'Select Category',
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'for-slug',
                ],
            ])
            ->add('slug', TextType::class, [
                'attr' => [
                    'class' => 'slug',
                ],
            ])
            ->add('brief')
            ->add('photo', HiddenType::class, [
                'block_prefix' => 'photo',
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'editor',
                ],
                'required' => false,
            ])
            ->add('meta_title')
            ->add('meta_description')
            ->add('date');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
