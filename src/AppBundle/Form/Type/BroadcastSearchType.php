<?php

namespace WhereCanIWatch\AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Validator\Constraints\NotBlank;

class BroadcastSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('query', SearchType::class, [
            'constraints' => [
                new Length(['min' => 3]),
                new NotBlank()
            ]
        ]);
    }
}