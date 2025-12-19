<?php

namespace Disjfa\MozaicBundle\Form\Type;

use Disjfa\MozaicBundle\Entity\UnsplashSeason;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminSeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'form.admin.season.label.title',
            'constraints' => new NotBlank(),
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'form.admin.season.label.description',
            'required' => false,
        ]);

        $builder->add('dateSeason', DateTimeType::class, [
            'label' => 'form.admin.season.label.date',
            'empty_data' => new \DateTime(),
        ]);

        $builder->add('public', CheckboxType::class, [
            'label' => 'form.admin.season.label.public',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UnsplashSeason::class,
            'translation_domain' => 'mozaic',
        ]);
    }
}
