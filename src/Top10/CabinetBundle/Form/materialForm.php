<?php

namespace Top10\CabinetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class materialForm extends AbstractType
{
	protected $material;

	public function __construct($material)
	{
		$this->material = $material;
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$material = $this->material;

		$choices_type = array(
			'checkbox' => 'галочка',
			'tree' => 'плюсик',
			'caption' => 'заголовок',
		);

		$type = array( 'label' => 'Тип пункта', 'choices' => $choices_type, 'required' => true );
		if( $material->getType() )
			$type['preferred_choices'] = array( $material->getType() );

		$parentint = array( 'label' => 'Состоит в', "required" => true );
		if( $material->getParent() )
			$parentint['empty_data'] = $material->getParent()->getId();


		$name = array( 'label' => 'Название', 'empty_data' => $material->getName(), "required" => true, 'attr' => array('placeholder' => 'Название') );
		$code = array( 'label' => 'ЧПУ', 'empty_data' => $material->getCode(), "required" => true, );
		$image = array( 'label' => 'Картинка', 'empty_data' => $material->getImage(), "required" => false, );
		$preview = array( 'label' => 'Краткое описание', 'empty_data' => $material->getPreview(), "required" => false,  );
		$content = array( 'label' => 'Описание', 'empty_data' => $material->getContent(), "required" => false,  );
		$plus = array( 'label' => 'Плюсы(;)', 'empty_data' => $material->getPlus(), "required" => false,);
		$minus = array( 'label' => 'Минусы(;)', 'empty_data' => $material->getMinus(), "required" => false,);
		$externallinks = array( 'label' => 'ссылки по теме(;)', 'empty_data' => $material->getExternallinks(), "required" => false,);
		$videolinks = array( 'label' => 'ссылки на видео(;)', 'empty_data' => $material->getVideolinks(), "required" => false,);
		$pricematerial = array( 'label' => 'Средняя стоимость материалов', 'empty_data' => $material->getPricematerial(), "required" => false,);
		$pricematerialmeasure = array( 'label' => 'Мера', 'empty_data' => $material->getPricematerialmeasure(), "required" => false,);
		$complexity = array( 'label' => 'Сложность(от 1 до 5)', 'empty_data' => $material->getComplexity(), "required" => false,);
		$sort = array( 'label' => 'Сортировка', 'empty_data' => $material->getSort(), "required" => false,);

		//if( $material->getApproved() )
			//$approved['attr'] = array('checked' => 'checked');

		$builder
			->add( 'parentint', 'hidden', $parentint )
			->add( 'code', 'text', $code )
			->add( 'name', 'text', $name )
			->add( 'type', 'choice', $type )
			->add( 'image', 'text', $image )
			->add( 'preview', 'textarea', $preview )
			->add( 'content', 'textarea', $content )
			->add( 'plus', 'textarea', $plus )
			->add( 'minus', 'textarea', $minus )
			->add( 'externallinks', 'textarea', $externallinks )
			->add( 'videolinks', 'textarea', $videolinks )
			->add( 'pricematerial', 'text', $pricematerial )
			->add( 'pricematerialmeasure', 'text', $pricematerialmeasure )
			->add( 'complexity', 'text', $complexity )
			->add( 'sort', 'text', $sort );
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Top10\CabinetBundle\Entity\material'
        ));
    }

    public function getName()
    {
        return 'top10_cabinetbundle_material';
    }
}
