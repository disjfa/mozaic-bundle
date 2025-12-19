<?php

namespace Disjfa\MozaicBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AdminDateType.
 */
class AdminDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateType::class, [
            'data' => new \DateTime(),
            'days' => [1],
        ]);
    }
}
