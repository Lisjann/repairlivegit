<?php

namespace Top10\CabinetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class commentForm extends AbstractType
{
	protected $type;
	protected $user;

    public function __construct($user, $type=null)
    {
		$this->type = $type;
		$this->user = $user;
    }


	public function buildForm(FormBuilderInterface $builder, array $options)
    {


		if( $this->user  == 'anon.' )
			$builder->add( 'author', 'text', array('label' => 'Предсавтесь', "required" => true ) );
		else
			$builder->add( 'author', 'hidden', array( 'empty_data' => $this->user ) );

		$builder->add( 'content', 'textarea', array('label' => 'Оставте свой комментарий', "required" => true, 'empty_data' => null) );

		if( $this->type == 'response' )
			$builder->add( 'parentint', 'hidden', array( "required" => true ) );
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Top10\CabinetBundle\Entity\comments'
        ));
    }

    public function getName()
    {
        if( $this->type == 'response' )
			return 'top10_cabinetbundle_comments_response';
		else
			return 'top10_cabinetbundle_comments';
    }
}
