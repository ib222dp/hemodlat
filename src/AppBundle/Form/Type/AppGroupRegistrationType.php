<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AppGroupRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('appGroup', new AppGroupType());
        $builder->add('Skicka', 'submit', array('attr' => array('class' => 'btn btn-primary'), ));
    }

    public function getName()
    {
        return 'appGroupRegistration';
    }
}