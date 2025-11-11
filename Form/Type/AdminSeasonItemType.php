<?php

namespace Disjfa\MozaicBundle\Form\Type;

use Disjfa\MozaicBundle\Entity\UnsplashSeasonItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminSeasonItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'form.admin.season_item.label.title',
            'constraints' => new NotBlank(),
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'form.admin.season_item.label.description',
            'required' => false,
        ]);

        $builder->add('seqnr', NumberType::class, [
            'label' => 'form.admin.season_item.label.seqnr',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UnsplashSeasonItem::class,
            'translation_domain' => 'mozaic',
        ]);
    }
}
