<?php
namespace Top10\CabinetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

//use JMS\SecurityExtraBundle\Annotation\Secure;
//use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Top10\CabinetBundle\Entity;
use Top10\CabinetBundle\Entity\Material;
use Top10\CabinetBundle\Entity\Slides;
use Top10\CabinetBundle\Entity\Materialforegin;
use Top10\CabinetBundle\Entity\Propertiesforegin;

use Top10\CabinetBundle\Form\materialForm;
use Knp\Component\Pager\Paginator;


/**
 * @Route("/")
 */
class MaterialController extends Controller
{
	/**
     * index a material.
     *
     * @Route("/material", name="material")
     * @Template("Top10CabinetBundle:Material:index.html.twig")
     */
	public function indexAction( Request $request)
	{
		$security = $this->get('security.context');
		if ( !$security->isGranted('ROLE_ADMIN') )
			throw $this->createNotFoundException('Not found material entity.');

		$current_user	= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar	= $this->get('cabinet.translate_char');
		$em 			= $this->getDoctrine()->getManager();
		$repMaterial	= $em->getRepository('Top10CabinetBundle:Material');

		$arrMaterialForeginY	   = array();
		$arrMaterialForeginParentY = array();

		//--------хлебные крошки----------------------//
		$router 			= $this->get('router'); 
		$breadcrumbs		= $this->get('white_october_breadcrumbs');

		$breadcrumbs->addItem( 
			'Главная',
			$router->generate('top10_cabinet_default_index')
		);
		$breadcrumbs->addItem( 'Список стройматериалов' );
		//--------/хлебные крошки----------------------//

		$material = $repMaterial
			->createQueryBuilder('p')
			->select('p')
			->andWhere( 'p.parent IS NULL' )
			->orderBy('p.sort', 'ASC')
			->getQuery()
			->getResult();

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'material' => $material,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

		return $result;
	}

	/**
     * list ajax a material.
     *
     * @Route("/materialparent", name="material_parent")
     * @Template("Top10CabinetBundle:Material:index_parent.html.twig")
     */
	public function indexAjaxParentAction( Request $request)
	{
		$security				= $this->get('security.context');
		if ( !$security->isGranted('ROLE_ADMIN') )
			throw $this->createNotFoundException('Not found material entity.');

		$current_user	= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar	= $this->get('cabinet.translate_char');
		$em 			= $this->getDoctrine()->getManager();
		$repMaterial		= $em->getRepository('Top10CabinetBundle:Material');

		$arrMaterialForeginY	   = array();
		$arrMaterialForeginParentY = array();

		//$room = $em->getRepository('Top10CabinetBundle:Rooms')->find( $room_id );
		//раствновка прав видиня неопубликованных комнат

		$material = $repMaterial
			->createQueryBuilder('p')
			->select('p')
			->andWhere( 'p.parent IS NULL' )
			->orderBy('p.sort', 'ASC')
			->getQuery()
			->getResult();

		$result = array(
			'material' => $material,
			'notid' => $request->query->get( 'notid' )
		);

		return $result;
	}
	
	/**
     * show a Material.
     *
     * @Route("/material/{plan_id}/{planforegin_id}", name="material_planforegin")
     * @Template("Top10CabinetBundle:Material:index_planforegin.html.twig")
     */
	public function indexAjaxPlanforeginAction( $plan_id, $planforegin_id, Request $request)
	{
		$security				= $this->get('security.context');
		$current_user			= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar			= $this->get('cabinet.translate_char');
		$em 					= $this->getDoctrine()->getManager();
		$repMaterial			= $em->getRepository('Top10CabinetBundle:Material');

		//$room = $em->getRepository('Top10CabinetBundle:Rooms')->find( $room_id );
		//раствновка прав видиня неопубликованных комнат

		$material = $repMaterial
			->createQueryBuilder('p')
			->select('p')
			->andWhere( 'p.parent IS NULL' )
			->orderBy('p.sort', 'ASC')
			->getQuery()
			->getResult();

		$result = array(
			'material' => $material,
			'plan_id' => $plan_id,
			'planforegin_id' => $planforegin_id,
		);

		return $result;
	}

	/**
     * Edit a material.
     *
     * @Route("/edit/material", name="material_edit")
     * @Template("Top10CabinetBundle:Material:edit.html.twig")
     */
	public function materialEditAction( Request $request)
	{
		$security				= $this->get('security.context');
		if ( !$security->isGranted('ROLE_ADMIN') )
			throw $this->createNotFoundException('Not found material entity.');

		$current_user			= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$em 					= $this->getDoctrine()->getManager();
		$repMaterial				= $em->getRepository('Top10CabinetBundle:Material');

		if( $request->query->get( 'id' ) ){
			$material = $repMaterial->find( $request->query->get( 'id' ) );
			if(!$material)
				$material = new material;
		}
		else{
			$material = new material;
		}


		//--------хлебные крошки----------------------//
		$router 			= $this->get('router'); 
		$breadcrumbs		= $this->get('white_october_breadcrumbs');

		$breadcrumbs->addItem( 
			'Стройматериалы',
			$router->generate('material')
		);

		$breadcrumbs->addItem( 'Редактировать стройматериал ' . $material->getName() );
		//--------/хлебные крошки----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//


		$materialForm = $this->createForm( new materialForm( $material ), $material );

		$materialForm->bind($request);

		if ( $request->isMethod('POST') /*&& $materialForm->isValid()*/ ) {
			if( $materialForm->get('parentint')->getData() != null ){
				$materialParent = $repMaterial->find( $materialForm->get('parentint')->getData() );
				$material->setParent( $materialParent );
			}
			$em->persist( $material );
			$em->flush();

			return $this->redirect( $this->generateUrl( 'material_edit', array( 'id' => $material->getId() ) ) );

		}

		$result = array(
			'materialForm' => $materialForm->createView(),
			'material' => $material,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

		return $result;

	}

	/**
	 * Delete a material.
	 *
	 * @Route("/delete/material/{id}", name="material_delete")
	 */
	public function materialDeleteAction($id)
	{
		$security = $this->get('security.context');

		if ( !$security->isGranted('ROLE_ADMIN') )
			throw $this->createNotFoundException('Not found material entity.');

		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository('Top10CabinetBundle:Material');

		//$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$material = $rep->find( $id );

		foreach( $material->getChildren() as $children ){
			$materialChildren = $rep->find( $children->getId() );
			$materialChildren->setParent( $material->getParent()  );
			$em->persist( $materialChildren );
		}

		$em->remove($material);
		$em->flush();

		return $this->redirect($this->generateUrl('material_edit'));
	}

	/**
     * Delete a materialforegin.
     *
	 * @Route("/delete/materialforegin/{id}", name="materialforegin_delete")
     */
    public function materialforeginDeleteAction($id)
	{
    	$em = $this->getDoctrine()->getManager();
    	$security = $this->get('security.context');

		$rep = $em->getRepository('Top10CabinetBundle:Materialforegin');
		$repRoom = $em->getRepository('Top10CabinetBundle:Rooms');

		$materialforegin = $rep->find($id);
		//растановка прав видиня неопубликованных комнат
		
		$postSecurity = $this->get('cabinet.security_role')->getObject( $repRoom, $materialforegin->getPlanforegin()->getRooms()->getId() );

		if( !$postSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found materialforegin entity.');

		$roomId = $materialforegin->getPlanforegin()->getRooms()->getId();

		$em->remove($materialforegin);
		$em->flush();

		return $this->redirect($this->generateUrl('plan_add', array('room_id' => $roomId ) ));
	}

}