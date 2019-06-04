<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Category;

class CategoryType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', CheckboxType::class, [
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label_attr' => [
                    'class' => 'custom-control-label float-right',
                ],
                'required' => false,
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
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'editor',
                ]
            ])
            ->add('meta_title')
            ->add('meta_description');
    }
}
