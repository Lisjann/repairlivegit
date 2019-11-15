<?php

namespace Top10\CabinetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class roomForm extends AbstractType
{
	protected $room;
	protected $roomstype;
	protected $ROLE_ADMIN;

    public function __construct($room, $roomstype, $ROLE_ADMIN)
    {
        $this->room = $room;
        $this->roomstype = $roomstype;
        $this->ROLE_ADMIN = $ROLE_ADMIN;
    }


	public function buildForm( FormBuilderInterface $builder, array $options )
    {
		/** @var $rooms rooms */
		$room = $this->room;
		$roomstype = $this->roomstype;
		$ROLE_ADMIN = $this->ROLE_ADMIN;

		$choices = array();
		foreach( $roomstype as $roomstype )
			$choices[ $roomstype['id'] ] = $roomstype['name'];

		$roomstypeInt = array( 'label' => 'Вид комнаты', 'choices' => $choices, 'required' => true );
		if( $room->getRoomstype() )
			$roomstypeInt['preferred_choices'] = array( $room->getRoomstype()->getId() );

		$name = array( 'label' => 'Придумайте название этой комнате', 'empty_data' => $room->getName(), "required" => true, 'attr' => array('placeholder' => 'Название') );
		$code = array( 'label' => 'ЧПУ', 'empty_data' => $room->getCode(), "required" => true, );
		$preview = array( 'label' => 'Расскажите об этой комнате', 'empty_data' => $room->getPreview(), "required" => false,  );
		$approved = array('label' => 'Опубликовать', "required" => false );
		if( $room->getApproved() )
			$approved['attr'] = array('checked' => 'checked');

		if( $ROLE_ADMIN ){
			$keywords = array( 'label' => 'Ключевые слова', 'empty_data' => $room->getKeywords(), "required" => false, );
			$description = array( 'label' => 'СЕО описание', 'empty_data' => $room->getDescription(), "required" => false,  );
			$created = array('label' => 'Дата создания', 'input'  => 'datetime', 'widget' => 'single_text', 'empty_data' => $room->getCreated()->format("Y-m-d"), "required" => false );
			$characteristics = array( 'label' => 'Характеристики', 'empty_data' => $room->getCharacteristics(), "required" => false,  );
		}

		$builder
			->add( 'roomstypeint', 'choice', $roomstypeInt )
			->add( 'name', 'textarea', $name )
			->add( 'preview', 'textarea', $preview )
			->add( 'approved', 'checkbox', $approved );

		if( $ROLE_ADMIN )
			$builder
				->add( 'code', 'hidden', $code )
				->add( 'created', 'date', $created )
				->add( 'keywords', 'text', $keywords )
				->add( 'description', 'text', $description )
				->add( 'characteristics', 'textarea', $characteristics );

	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Top10\CabinetBundle\Entity\rooms'
        ));
    }

    public function getName()
    {
        return 'top10_cabinetbundle_room';
    }
}
