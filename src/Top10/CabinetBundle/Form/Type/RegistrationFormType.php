<?php

namespace Top10\CabinetBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder
        //->add('location', null, array('label' => 'Город:', "required" => false))
        //->add('plainPassword', 'hidden', array('data' => 'abcdef'))
        ->add('new', 'hidden', array('data' => true))
        ;
    }

    public function getName()
    {
    	return 'top10_user_registration';
    }
    
}