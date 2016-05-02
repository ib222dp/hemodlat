<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PMType extends AbstractType
{
    private $friendArray;
    private $oldRecipients;
    private $choiceList;

    public function __construct($friendArray, $oldRecipients)
    {
        $this->friendArray = $friendArray;
        $this->oldRecipients = $oldRecipients;
        $this->choiceList = new ObjectChoiceList($friendArray, 'username', array(), null, 'id');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $friendArray = $this->friendArray;
        $oldRecipients = $this->oldRecipients;
        $builder->add('message', 'textarea');
        $builder->add('recipients', 'choice', array(
            'choice_list' => $this->choiceList,
            'choices' => $friendArray,
            'multiple' => 'true',
            'data' => $oldRecipients
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }

    public function getName()
    {
        return 'PM';
    }

}