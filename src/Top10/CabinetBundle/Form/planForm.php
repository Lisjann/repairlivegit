<?php

namespace Top10\CabinetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class planForm extends AbstractType
{
	protected $plan;

    public function __construct($plan)
    {
        $this->plan = $plan;
    }


	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$plan = $this->plan;

		$choices_type = array(
			'checkbox' => 'галочка',
			'tree' => 'плюсик',
			'caption' => 'заголовок',
		);

		$type = array( 'label' => 'Тип пункта', 'choices' => $choices_type, 'required' => true );
		if( $plan->getType() )
			$type['preferred_choices'] = array( $plan->getType() );

		$parentint = array( 'label' => 'Состоит в', "required" => true );
		if( $plan->getParent() )
			$parentint['empty_data'] = $plan->getParent()->getId();


		$name = array( 'label' => 'Название', 'empty_data' => $plan->getName(), "required" => true, 'attr' => array('placeholder' => 'Название') );
		$code = array( 'label' => 'ЧПУ', 'empty_data' => $plan->getCode(), "required" => true, );
		$image = array( 'label' => 'Картинка', 'empty_data' => $plan->getImage(), "required" => false, );
		$preview = array( 'label' => 'Краткое описание', 'empty_data' => $plan->getPreview(), "required" => false,  );
		$content = array( 'label' => 'Описание', 'empty_data' => $plan->getContent(), "required" => false,  );
		$plus = array( 'label' => 'Плюсы(;)', 'empty_data' => $plan->getPlus(), "required" => false,);
		$minus = array( 'label' => 'Минусы(;)', 'empty_data' => $plan->getMinus(), "required" => false,);
		$externallinks = array( 'label' => 'ссылки по теме(;)', 'empty_data' => $plan->getExternallinks(), "required" => false,);
		$videolinks = array( 'label' => 'ссылки на видео(;)', 'empty_data' => $plan->getVideolinks(), "required" => false,);
		$pricematerial = array( 'label' => 'Средняя стоимость материалов', 'empty_data' => $plan->getPricematerial(), "required" => false,);
		$pricematerialmeasure = array( 'label' => 'Мера', 'empty_data' => $plan->getPricematerialmeasure(), "required" => false,);
		$priceworck = array( 'label' => 'Средняя стоимость работ', 'empty_data' => $plan->getPriceworck(), "required" => false,);
		$priceworckmeasure = array( 'label' => 'Мера', 'empty_data' => $plan->getPriceworckmeasure(), "required" => false,);
		$complexity = array( 'label' => 'Сложность(от 1 до 5)', 'empty_data' => $plan->getComplexity(), "required" => false,);
		$worcktime = array( 'label' => 'Среднее время на работу', 'empty_data' => $plan->getWorcktime(), "required" => false,);
		$sort = array( 'label' => 'Сортировка', 'empty_data' => $plan->getSort(), "required" => false,);

		//if( $plan->getApproved() )
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
			->add( 'priceworck', 'text', $priceworck )
			->add( 'priceworckmeasure', 'text', $priceworckmeasure )
			->add( 'complexity', 'text', $complexity )
			->add( 'worcktime', 'text', $worcktime )
			->add( 'sort', 'text', $sort );
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Top10\CabinetBundle\Entity\plan'
        ));
    }

    public function getName()
    {
        return 'top10_cabinetbundle_plan';
    }
}
