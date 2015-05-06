<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SURegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('statusUpdate', new StatusUpdateType());
        $builder->add('Skicka', 'submit', array('attr' => array('class' => 'btn btn-primary'), ));
    }

    public function getName()
    {
        return 'suRegistration';
    }
}