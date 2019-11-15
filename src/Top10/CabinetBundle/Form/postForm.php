<?php

namespace Top10\CabinetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class postForm extends AbstractType
{
	protected $post;

	protected $ROLE_ADMIN;

    public function __construct($post, $ROLE_ADMIN)
    {
        $this->post = $post;
        
		$this->ROLE_ADMIN = $ROLE_ADMIN;
    }


	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$post = $this->post;
		$ROLE_ADMIN = $this->ROLE_ADMIN;

		$name = array( 'label' => 'Придумайте название', 'empty_data' => $post->getName(), "required" => true, );
		$created = array('label' => 'Дата создания', 'input'  => 'datetime', 'widget' => 'single_text', 'empty_data' => $post->getCreated()->format("Y-m-d"), "required" => false );
		$approved = array('label' => 'Опубликовать', "required" => false );
		$code = array( 'label' => 'ЧПУ', 'empty_data' => $post->getCode(), "required" => true, );
		$title = array( 'label' => 'Титул', 'empty_data' => $post->getTitle(), "required" => false, );
		$keywords = array( 'label' => 'Ключевые слова', 'empty_data' => $post->getKeywords(), "required" => false, );
		$description = array( 'label' => 'СЕО описание', 'empty_data' => $post->getDescription(), "required" => false, );
		$image = array( 'label' => 'Главная картинка', 'empty_data' => $post->getImage(), "required" => true );
		$preview = array( 'label' => 'Превью', 'empty_data' => $post->getPreview(), "required" => false, );
		$content = array( 'label' => 'Содержание', 'empty_data' => $post->getContent(), "required" => false, );

		//if( $post->getApproved() == true )
			//$approved['checked'] = 'checked';

		if( $ROLE_ADMIN )
			$builder
				->add( 'interesting', 'checkbox', array('label' => 'Интересная', "required" => false ) )
				->add( 'useful', 'checkbox', array('label' => 'Полезная', "required" => false ) )
				->add( 'mainBig', 'checkbox', array('label' => 'На главной Большая', "required" => false ) )
				->add( 'mainSmall', 'checkbox', array('label' => 'На главной Маленькая', "required" => false ) )
				->add( 'title', 'text', $title )
				->add( 'keywords', 'text', $keywords )
				->add( 'description', 'textarea', $description )
				->add( 'image', 'text', $image )
				->add( 'preview', 'textarea', $preview );
		else
			$builder->add( 'image', 'hidden', $image );

		$builder
			->add( 'name', 'textarea', $name )
			->add( 'preview', 'textarea', $preview )
			->add( 'content', 'textarea', $content )
			->add( 'created', 'date', $created )
			->add( 'approved', 'checkbox', $approved );
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Top10\CabinetBundle\Entity\posts'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
