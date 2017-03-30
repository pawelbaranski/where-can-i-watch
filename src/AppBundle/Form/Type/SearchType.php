<?php

namespace WhereCanIWatch\AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SearchType as SfSearchType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('query', SfSearchType::class, [
            'constraints' => [
                new Length(['min' => 3])
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        /** http://stackoverflow.com/a/34278097 */
        return 'broadcasts_search';
    }
}