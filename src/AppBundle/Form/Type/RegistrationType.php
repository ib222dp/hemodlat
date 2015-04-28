<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('appUser', new AppUserType());
        $builder->add('Register', 'submit');
    }

    public function getName()
    {
        return 'registration';
    }
}