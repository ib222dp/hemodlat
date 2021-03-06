<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PMThreadType extends AbstractType
{
    private $friendArray;
    private $choiceList;

    public function __construct($friendArray)
    {
        $this->friendArray = $friendArray;
        $this->choiceList = new ObjectChoiceList($friendArray, 'username', array(), null, 'id');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $friendArray = $this->friendArray;
        $builder->add('subject', 'text');
        $builder->add('message', 'textarea');
        $builder->add('recipients', 'choice', array(
            'choice_list' => $this->choiceList,
            'choices' => $friendArray,
            'multiple' => 'true'
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }

    public function getName()
    {
        return 'PMThread';
    }

}