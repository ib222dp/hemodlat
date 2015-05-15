<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\AppUser;
use AppBundle\Entity\County;
use AppBundle\Entity\Location;

class AppUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('firstName', 'text');
        $builder->add('lastName', 'text');
        $builder->add('email', 'email');
        $builder->add('password', 'repeated', array(
            'first_name'  => 'password',
            'second_name' => 'confirm',
            'type'        => 'password'
        ));
        $builder->add('county', 'entity', array(
                'class'       => 'AppBundle:County',
                'property' => 'name',
                'placeholder' => ''
        ));
        $builder->add('Skicka', 'submit', array(
            'attr' => array('class' => 'btn btn-primary')
        ));

        $formModifier = function (FormInterface $form, County $county = null) {
            $locations = null === $county ? array() : $county->getLocations();

            $form->add('location', 'entity', array(
                'class'       => 'AppBundle:Location',
                'property' => 'name',
                'placeholder' => '',
                'choices'     => $locations,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use($formModifier)
            {
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getCounty());
            }
        );

        $builder->get('county')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {

                $county = $event->getForm()->getData();

                $formModifier($event->getForm()->getParent(), $county);
            }
        );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AppUser'
        ));
    }

    public function getName()
    {
        return 'appUser';
    }
}