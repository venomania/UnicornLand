<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('title')
            ->add('subTitle')
            ->add('image')
            ->add('articleContent')
            ->add('visibility')
            ->add('category',EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                ])
            ->add('user')
            ->add('likes')
            ->add('shares')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
