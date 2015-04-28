<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FshipRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('friendship', new FshipType());
        $builder->add('Register', 'submit');
    }

    public function getName()
    {
        return 'fshipregistration';
    }
}