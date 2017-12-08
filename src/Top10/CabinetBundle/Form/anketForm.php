<?php

namespace Top10\CabinetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class anketForm extends AbstractType
{
	protected $anket;

	public function __construct($anket)
	{
		$this->anket = $anket;
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		/** @var $anket anket */
		$anket = $this->anket;

		$familymembers	   = array( 'label' => 'Перечислите членов семь их возраст', 'empty_data' => $anket->getFamilymembers(), "required" => false, 'attr' => array('placeholder' => 'Дочка 8лет') );
		$familymembersplan = array( 'label' => 'Планируется ли пополнение семью их статус и возраст', 'empty_data' => $anket->getFamilymembersplan(), "required" => false, 'attr' => array('placeholder' => 'Сын 0лет') );
		$hobby			   = array( 'label' => 'Предметы хобби и увлечения ', 'empty_data' => $anket->getHobby(), "required" => false, 'attr' => array('placeholder' => 'Боулинг: шар, кегли, Гитарист электрогитара колонка') );;
		$animals		   = array( 'label' => 'Перечислете животных и их предметы и места', 'empty_data' => $anket->getAnimals(), "required" => false, 'attr' => array('placeholder' => 'Попугай, клетка 40*60см') );
		$guests			   = array( 'label' => 'Гости, как часто они бывают, их предметы и места', 'empty_data' => $anket->getGuests(), "required" => false, 'attr' => array('placeholder' => 'Друг семьи Антон и Галя, 2 спальных места') );
		$zone			   = array( 'label' => 'Зонирование определенных областей в комнате', 'empty_data' => $anket->getZone(), "required" => false, 'attr' => array('placeholder' => 'Выделить обеденную зону, оградить спальное место') );
		$redevelopment	   = array( 'label' => 'Нужна ли прерпланировка', 'empty_data' => $anket->getRedevelopment(), "required" => false, 'attr' => array('placeholder' => 'Объеденить прихожую с гостинной, уменьшить спальню') );
		$style			   = array( 'label' => 'Предпочтительный стиль в интерьере', 'empty_data' => $anket->getStyle(), "required" => false, 'attr' => array('placeholder' => 'Классический, Современный, Хайтек, Европейский, Минимализм, Восточный ...') );
		$material		   = array( 'label' => 'Предпочтение в материалов', 'empty_data' => $anket->getMaterial(), "required" => false, 'attr' => array('placeholder' => 'Древесина, Обои с мишками, Ламинат на потолок') );
		$technique		   = array( 'label' => 'Необходимая и предпочтительная техника', 'empty_data' => $anket->getTechnique(), "required" => false, 'attr' => array('placeholder' => 'Телефизор, посудомойка, кофеварка, проектор ... ') );
		$electrician	   = array( 'label' => 'Расположение розеток выключателей, зоны освещения, виды светильников', 'empty_data' => $anket->getElectrician(), "required" => false, 'attr' => array('placeholder' => 'Выключатель у входной двери, розетка для телефона у кровати, бра над диваном, точечная подсветка рабочего стола ... ') );
		$furniture		   = array( 'label' => 'Предпочтительная Мебель для храниения, мягкая мебель, предметы каторые нужно хранить, ', 'empty_data' => $anket->getFurniture(), "required" => false, 'attr' => array('placeholder' => 'Подиум, Шкаф купе во всю стену, кресло качалка, низкий широкий диван, ...') );
		$habitatquality	   = array( 'label' => 'Нужна ли звуко-теплоизоляция, изменение: температуры, качества воды возуха, чистота в комнате', 'empty_data' => $anket->getHabitatquality(), "required" => false, 'attr' => array('placeholder' => 'Звукоизоляция потолка, теплый пол, замена батарей, кондиционер, ионизатор воздуха, ...') );
		$plants			   = array( 'label' => 'Использования растений', 'empty_data' => $anket->getPlants(), "required" => false, 'attr' => array('placeholder' => 'Пальма 2метра в большом ведре, Стена покрытая мхом, трава лдя кошек, ...') );
		$save			   = array( 'label' => 'Что хотите сохранить из того что у вас у же есть', 'empty_data' => $anket->getSave(), "required" => false, 'attr' => array('placeholder' => 'Кровать, кресло, стенка, Бабущкин ковер, ...') );
		$other			   = array( 'label' => 'Дополнительно укажите то что не встретилось в пунктах выше', 'empty_data' => $anket->getOther(), "required" => false, 'attr' => array('placeholder' => '') );

		$builder
			->add( 'familymembers', 'textarea', $familymembers )
			->add( 'familymembersplan', 'textarea', $familymembersplan )
			->add( 'hobby', 'textarea', $hobby )
			->add( 'animals', 'textarea', $animals )
			->add( 'guests', 'textarea', $guests )
			->add( 'zone', 'textarea', $zone )
			->add( 'redevelopment', 'textarea', $redevelopment )
			->add( 'style', 'textarea', $style )
			->add( 'material', 'textarea', $material )
			->add( 'technique', 'textarea', $technique )
			->add( 'electrician', 'textarea', $electrician )
			->add( 'furniture', 'textarea', $furniture )
			->add( 'habitatquality', 'textarea', $habitatquality )
			->add( 'plants', 'textarea', $plants )
			->add( 'save', 'textarea', $save )
			->add( 'other', 'textarea', $other );
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Top10\CabinetBundle\Entity\anket'
        ));
    }

    public function getName()
    {
        return 'top10_cabinetbundle_anket';
    }
}
