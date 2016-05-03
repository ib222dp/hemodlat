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
    private $recipientList;
    private $prevSenderAndRecipients;
    private $choiceList;

    public function __construct($recipientList, $prevSenderAndRecipients)
    {
        $this->recipientList = $recipientList;
        $this->prevSenderAndRecipients = $prevSenderAndRecipients;
        $this->choiceList = new ObjectChoiceList($recipientList, 'username', array(), null, 'id');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', 'textarea');
        $builder->add('recipients', 'choice', array(
            'choice_list' => $this->choiceList,
            'choices' => $this->recipientList,
            'multiple' => 'true',
            'data' => $this->prevSenderAndRecipients
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