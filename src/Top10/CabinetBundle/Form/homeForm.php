<?php

namespace Top10\CabinetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class homeForm extends AbstractType
{
	protected $home;
	protected $homestype;
	protected $ROLE_ADMIN;

    public function __construct($home, $homestype, $ROLE_ADMIN)
    {
        $this->home = $home;
        $this->homestype = $homestype;
		$this->ROLE_ADMIN = $ROLE_ADMIN;
    }


	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		/** @var $Homes Homes */
		$home = $this->home;
		$homestype = $this->homestype;
		$ROLE_ADMIN = $this->ROLE_ADMIN;



		$choices = array();
		foreach( $homestype as $homestype )
			$choices[ $homestype['id'] ] = $homestype['name'];

		$homestypeInt = array( 'label' => 'Строение', 'choices' => $choices, 'required' => true );
		$name = array( 'label' => 'Придумайте название своему дому', 'empty_data' => $home->getName(), "required" => true, 'attr' => array('placeholder' => 'Название' )  );
		$code = array( 'label' => 'ЧПУ', 'empty_data' => $home->getCode(), "required" => true,  );
		$preview = array( 'label' => 'Расскажите о своем доме', 'empty_data' => $home->getPreview(), "required" => false,  );
		$approved = array('label' => 'Опубликовать', "required" => false,  );
		if( $home->getApproved() )
			$approved['attr'] = array('checked' => 'checked');

		if( $ROLE_ADMIN ){
			$created = array('label' => 'Дата создания', 'input'  => 'datetime', 'widget' => 'single_text', 'empty_data' => $home->getCreated()->format("Y-m-d"), "required" => false );
			$keywords = array( 'label' => 'Ключевые слова', 'empty_data' => $home->getKeywords(), "required" => false,  );
			$description = array( 'label' => 'СЕО описание', 'empty_data' => $home->getDescription(), "required" => false,  );
			$characteristics = array( 'label' => 'Характеристики', 'empty_data' => $home->getCharacteristics(), "required" => false,  );
		}

		$builder
			->add( 'homestypeint', 'choice', $homestypeInt )
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
            'data_class' => 'Top10\CabinetBundle\Entity\Homes'
        ));
    }

    public function getName()
    {
        return 'top10_cabinetbundle_home';
    }
}
