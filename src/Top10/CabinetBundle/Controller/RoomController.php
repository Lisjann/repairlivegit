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
use Top10\CabinetBundle\Entity\Rooms;
use Top10\CabinetBundle\Entity\Comments;
use Top10\CabinetBundle\Entity\Likes;
use Top10\CabinetBundle\Entity\Slides;
use Top10\CabinetBundle\Entity\Plan;
use Top10\CabinetBundle\Entity\Planforegin;
use Top10\CabinetBundle\Entity\Propertiesforegin;
use Top10\CabinetBundle\Entity\Materialforegin;
use Top10\CabinetBundle\Entity\Anket;

use Top10\CabinetBundle\Form\roomForm;
use Top10\CabinetBundle\Form\anketForm;
use Top10\CabinetBundle\Form\commentForm;
use Knp\Component\Pager\Paginator;


/**
 * @Route("/")
 */
class RoomController extends Controller
{
	/**
     * Room list.
     *
     * @Route("/room", name="room")
     * @Template("Top10CabinetBundle:Room:index.html.twig")
     */

	public function indexAction( Request $request )
	{
		$paginator = $this->get('knp_paginator');
		$security 	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();

		//--------Комнаты----------------------//
		$rep = $this->getDoctrine()->getRepository('Top10CabinetBundle:Rooms');

		$qbRooms = $rep->createQueryBuilder('r');
		$qbRooms->addSelect('r')->addOrderBy('r.created', 'DESC');
		if ( !$security->isGranted('ROLE_ADMIN') )
			$qbRooms->andWhere('r.approved = 1');

		$rooms = $paginator->paginate(
			$qbRooms,
			$request->query->get( 'page', 1 ),
			20
		);
		//--------/Комнаты----------------------//

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:homes');

		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'rooms' => $rooms,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
		);
		return $result;
	}


	/**
     * Show a room.
     *
     * @Route("/room/{id}/{code}", name="room_show")
     * @Template("Top10CabinetBundle:Room:showroom.html.twig")
     */
    public function roomShowAction( $id, $code, Request $request)
    {
		$security			  = $this->get('security.context');
		$current_user		  = $security->getToken()->getUser();
		$router				  = $this->get('router'); 
		$breadcrumbs		  = $this->get('white_october_breadcrumbs');
		$em 				  = $this->getDoctrine()->getManager();
		$repPlanForeginmeta	  = $em->getRepository('Top10CabinetBundle:Planforeginmeta');
		$repAnket			  = $em->getRepository('Top10CabinetBundle:Anket');
		$repSlides			  = $em->getRepository('Top10CabinetBundle:Slides');

		/** @var $room Rooms */
		$rep = $em->getRepository('Top10CabinetBundle:Rooms');

		//раствновка прав видиня неопубликованных комнат
		$roomSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$room = $roomSecurity['object'];

		if( !$room )
			throw $this->createNotFoundException('Not found room entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			'Дома',
			$router->generate('home')
		);
		$breadcrumbs->addItem( 
			$room->getHomes()->getHomestype()->getName() . '. ' . $room->getHomes()->getName(),
			$router->generate('home_show', array( 'id' => $room->getHomes()->getId(), 'code' => $room->getHomes()->getCode() ) )
		);

        $breadcrumbs->addItem( $room->getRoomstype()->getName() . '. ' .  $room->getName() );
		//--------/хлебные крошки----------------------//


		//--------Анкета----------------------//
		$anket = $repAnket->findOneByRooms( $room->getId() );
		if( $anket )
			$anketForm = $this->createForm( new anketForm( $anket ), $anket );
		//--------/Анкета----------------------//

		//--------Слайды----------------------//
		$slides = $repSlides->findBy( array('type' => null, 'rooms' => $room->getId() ) );
		$anketimg = $repSlides->findBy( array('type' => 'anket', 'rooms' => $room->getId() ) );
		$layout = $repSlides->findBy( array('type' => 'layout', 'rooms' => $room->getId() ) );
		$collage = $repSlides->findBy( array('type' => 'collage', 'rooms' => $room->getId() ) );
		$reamingwalls = $repSlides->findBy( array('type' => 'reamingwalls', 'rooms' => $room->getId() ) );
		$threedmodel = $repSlides->findBy( array('type' => 'threedmodel', 'rooms' => $room->getId() ) );

		//--------/Слайды----------------------//


		//--------План----------------------//
		$planForeginmeta = $repPlanForeginmeta
			->createQueryBuilder('pf')
			->select('pf')
			->andWhere( 'pf.parent IS NULL' )
			->andWhere('pf.rooms = :room')
				->setParameter( 'room', $room )
			->orderBy('pf.sort', 'ASC')
			->getQuery()
			->getResult();

		$planServis = $this->get('cabinet.plan_servis');
		$planIndication = $planServis->getPlanIndication( $room ) ;
		//--------/План----------------------//


		//--------Посты----------------------//
		/** @var $postSearch \Top10\CabinetBundle\Service\PostSearch */
		$postSearch = $this->get('cabinet.post_search');

		//растановка прав видиния неопубликованных постов
		if ( $security->isGranted('ROLE_ADMIN') )
			$arrPostsPaginator = $postSearch->search( array( 'rooms' => $room, 'approved' => 'all'  ) );
		elseif ( $room->getAuthor() === $current_user )
			$arrPostsPaginator = $postSearch->search( array( 'rooms' => $room, 'approved' => 'all'  ) );
		else
			$arrPostsPaginator = $postSearch->search( array( 'rooms' => $room  ) );

		$arrPostsPaginator->setTemplate('Top10CabinetBundle:Room:pagination.html.twig');
		//--------/Посты----------------------//



		//--------Коментарии----------------------//
		// create a Comments and give it some dummy data for this example
		$repComments = $em->getRepository('Top10CabinetBundle:Comments');

		$Comments = new Comments();

		$commentsForm = $this->createForm( new commentForm( $current_user ), $Comments );
		$commentsForm->bind($request);

		if ( $request->isMethod('POST') )
		{
			if ($commentsForm->isValid()) {
				$Comments->setRooms( $room );

				if( $security->isGranted('IS_AUTHENTICATED_FULLY') )
					$Comments->setUser( $current_user );

				$em->persist( $Comments );
				$em->flush();
			}
		}

		$Comments = new Comments();

		$commentsFormResponse = $this->createForm( new commentForm( $current_user, 'response'), $Comments );
		$commentsFormResponse->bind($request);

		if ( $request->isMethod('POST') ) {
			if ($commentsFormResponse->isValid())
			{
				$commentsParent = $repComments->find( $commentsFormResponse->get('parentint')->getData() );
				$Comments->setParent( $commentsParent );
				$Comments->setRooms( $room );
				if( $security->isGranted('IS_AUTHENTICATED_FULLY') )
					$Comments->setUser( $current_user );

				$em->persist( $Comments );
				$em->flush();
			}
		}

		/** @var $arrComments CommentsRepository */
		$arrComments = $repComments->getComments( array( 'rooms' => $room->getId() ) );
		//--------Коментарии----------------------//

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//


		//--------Лайки----------------------//
		$likesClass['enabled'] = 'likes';
		$likesClass['disabled'] = 'likes_disabled';
		if ( $em->getRepository('Top10CabinetBundle:Likes')->getCountLikesForRoom( $room->getId(), null, $_SERVER['REMOTE_ADDR'] ) == 0 )
			$likesClass['live'] = 'likes';
		else
			$likesClass['live'] = 'likes_disabled';
		//--------/Лайки----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//


		$result = array(
			'room' => $room,
			'anket' => $anket,
			'slides' => $slides,
			'anketimg' => $anketimg,
			'layout' => $layout,
			'collage' => $collage,
			'threedmodel' => $threedmodel,
			'reamingwalls' => $reamingwalls,
			'planForeginmeta' => $planForeginmeta,
			'planIndication' => $planIndication,
			'posts' => $arrPostsPaginator,
			'comments' => $arrComments,
			'commentsCount' => $repComments->getCountComments(  array( 'rooms' => $room->getId() ) ),
			'commentsForm' => $commentsForm->createView(),
			'likesCount' => $em->getRepository('Top10CabinetBundle:Likes')->getCountLikesForRoom( $room->getId() ),
			'likesClass' => $likesClass,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
			'isWriter' => $roomSecurity['isWriter'],
		);
		
		if($anket)
			$result['anketForm'] = $anketForm->createView();

		return $result;
    }



	/**
     * Edit a room.
     *
     * @Route("/edit/room/{id}", name="room_edit")
     * @Template("Top10CabinetBundle:Room:editroom.html.twig")
     */
	public function roomEditAction( $id, Request $request)
	{
		$security 	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();
		$em 		  = $this->getDoctrine()->getManager();
		$router 	  = $this->get('router'); 
		$breadcrumbs  = $this->get('white_october_breadcrumbs');

		$rep 		  = $em->getRepository('Top10CabinetBundle:Rooms');
		$repRoomstype = $em->getRepository('Top10CabinetBundle:Roomstype');
		
		$repProperties		  = $em->getRepository('Top10CabinetBundle:Properties');
		$repPropertiesForegin = $em->getRepository('Top10CabinetBundle:propertiesforegin');
		$properties			  = $repProperties->findByIsin( 'rooms' );

		//раствновка прав видиня неопубликованных комнат
		$roomSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$room = $roomSecurity['object'];

		/** @var $room rooms */

		if( !$roomSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found room entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			$room->getHomes()->getHomestype()->getName() . '. ' . $room->getHomes()->getName(),
			$router->generate('home_show', array( 'id' => $room->getHomes()->getId(), 'code' => $room->getHomes()->getCode() ) )
		);

		$breadcrumbs->addItem( 'Редактировать комнату' );
		//--------/хлебные крошки----------------------//

		if (!$room)
			$room = new rooms();

		$roomstype = $repRoomstype
			   ->createQueryBuilder('h')
			   ->select('h')
			   ->getQuery()
			   ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

		$roomForm = $this->createForm( new roomForm( $room, $roomstype, $security->isGranted('ROLE_ADMIN') ), $room );

		$roomForm->bind($request);

		if ( $request->isMethod('POST') /*&& $roomForm->isValid()*/ ) {

			if( $roomForm->get('roomstypeint')->getData() != null ){
				$roomstype = $repRoomstype->find( $roomForm->get('roomstypeint')->getData() );
				$room->setRoomstype( $roomstype );
			}

			//$room->setAuthor( $current_user );

			$em->persist( $room );

			//--------Добавляем свойства ---------------------------
			$arrReqProperties = $request->request->get('properties');

			foreach( $properties as $propertie ){

				if( @$arrReqProperties[$propertie->getId()] ){

					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'rooms' => $room->getId() 
						)
					);

					if( !$propertiesForegin )
						$propertiesForegin = new propertiesforegin();

					$propertiesForegin->setProperties( $propertie );
					$propertiesForegin->setRooms( $room );
					$propertiesForegin->setValue( $arrReqProperties[$propertie->getId()] );
					$em->persist( $propertiesForegin );
					$em->flush();
				}
				else{
					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'rooms' => $room->getId() 
						)
					);
					if( $propertiesForegin ){
						$em->remove($propertiesForegin);
						$em->flush();
					}
				}

			}
			//--------/Добавляем свойства ---------------------------

			$em->flush();

			return $this->redirect( $this->generateUrl( 'room_show', array( 'id' => $room->getId(), 'code' => $room->getCode() ) ) );
		}

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'roomForm' => $roomForm->createView(),
			'room' => $room,
			'properties' => $properties,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

		return $result;
	}



	/**
     * Edit a room.
     *
     * @Route("/home/{home_id}/add/room/", name="room_add")
     * @Template("Top10CabinetBundle:Room:editroom.html.twig")
     */
	public function roomAddAction( $home_id, Request $request)
	{
		$security			= $this->get('security.context');
		$current_user		= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar		= $this->get('cabinet.translate_char');
		$em 				= $this->getDoctrine()->getManager();
		$router 			= $this->get('router'); 
		$breadcrumbs		= $this->get('white_october_breadcrumbs');
		$FineUploadHandler 	= $this->get('cabinet.fine_upload');
		$dirname 			= 'roomuser' . $current_user->getId();
		$dirAdd				= join( DIRECTORY_SEPARATOR, array( 'img', 'room', $dirname ) );

		$home = $em->getRepository('Top10CabinetBundle:Homes')->find( $home_id );
		$repRoomstype= $em->getRepository('Top10CabinetBundle:Roomstype');

		$repProperties		  = $em->getRepository('Top10CabinetBundle:Properties');
		$repPropertiesForegin = $em->getRepository('Top10CabinetBundle:propertiesforegin');
		$properties			  = $repProperties->findByIsin( 'rooms' );

		$room = new rooms();

		if( !$room || $home->getAuthor() !== $current_user )
			throw $this->createNotFoundException('Not found room entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			$home->getHomestype()->getName() . '. ' . $home->getName(),
			$router->generate('home_show', array( 'id' => $home->getId(), 'code' => $home->getCode() ) )
		);

        $breadcrumbs->addItem( 'Добавить комнату' );
		//--------/хлебные крошки----------------------//

		$roomsTypeList = $repRoomstype
				->createQueryBuilder('h')
				->select('h')
				->getQuery()
				->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

		$roomForm = $this->createForm( new roomForm( $room, $roomsTypeList, $security->isGranted('ROLE_ADMIN') ), $room );

		$roomForm->bind($request);

		if ( $request->isMethod('POST') /*&& $roomForm->isValid()*/ ) {

			if( $roomForm->get('roomstypeint')->getData() != null ){
				$roomsType = $repRoomstype->find( $roomForm->get('roomstypeint')->getData() );
				$room->setRoomstype( $roomsType );
			}

			$room->setAuthor( $current_user );
			$room->setHomes( $home );

			$code = $translateChar->getInTranslateToEn( $roomForm->get('name')->getData(), true );
			$room->setCode( $code );

			$em->persist( $room );
			$em->flush();

			//--------Добавляем свойства---------------------------
			$arrReqProperties = $request->request->get('properties');

			foreach( $properties as $propertie ){

				if( @$arrReqProperties[$propertie->getId()] ){

					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'rooms' => $room->getId() 
						)
					);

					if( !$propertiesForegin )
						$propertiesForegin = new propertiesforegin();

					$propertiesForegin->setProperties( $propertie );
					$propertiesForegin->setRooms( $room );
					$propertiesForegin->setValue( $arrReqProperties[$propertie->getId()] );
					$em->persist( $propertiesForegin );
					$em->flush();
				}
				else{
					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'rooms' => $room->getId() 
						)
					);
					if( $propertiesForegin ){
						$em->remove($propertiesForegin);
						$em->flush();
					}
				}

			}
			//--------/Добавляем свойства---------------------------

			//переименовываем временную папку для картинок  и поля в Slides
			$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'room', $room->getId() ) );
			$dirOld = join( DIRECTORY_SEPARATOR, array( 'img', 'room', $room->getId() . 'old' ) );
			if ( file_exists( $dirAdd ) )
				if ( file_exists( $dir ) )
					rename($dir, $dirOld);

			if ( file_exists( $dirAdd ) ){
				if ( rename($dirAdd, $dir) ){
					$conn = $em->getConnection();
					$rowsAffected = $conn->executeUpdate(
						"UPDATE `slides`
							SET rooms_id = :rooms_id,
								code = NULL,
								image = REPLACE( image, '". $dirname ."', '" . $room->getId() . "' )
						WHERE code =:dirname",
						array(
							'rooms_id' => $room->getId(),
							'dirname' => $dirname,
							
						),
						array('ids' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
					);
				}
			}

			return $this->redirect( $this->generateUrl( 'room_show', array( 'id' => $room->getId(), 'code' => $room->getCode() ) ) );
		}
		else{
			//-----------------------Удалеие файлов из папки user### и полей из Slides---------------------------
			$FineUploadHandler->removeDir2( $dirAdd );

			$qb = $em->createQueryBuilder();
			$qb->delete('Top10CabinetBundle:Slides','s')
				->andWhere('s.code = :id')
					->setParameter( 'id', $dirname )
				->getQuery()
				->execute();
			//-----------------------/Удалеие файлов из папки user### и полей из Slides---------------------------
		}

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'roomForm' => $roomForm->createView(),
			'home_id' => $home->getId(),
			'tags' => $arrTags,
			'tagspost' => null,
			'properties' => $properties,
			'homesRoomsMenu' => $homesRoomsMenu,
		);


		return $result;
	}



	/**
     * Delete a room.
     *
	 * @Route("/delete/room/{id}", name="room_delete")
     */
    public function roomDeleteAction($id)
	{
    	$em = $this->getDoctrine()->getManager();
    	$security = $this->get('security.context');

		$rep = $em->getRepository('Top10CabinetBundle:Rooms');

		//растановка прав видиня неопубликованных комнат
		$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$room = $postSecurity['object'];

		if( !$postSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found Room entity.');

		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'room', $id ) );
		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$FineUploadHandler->removeDir2( $dir );

		$homeId = $room->getHomes()->getId();
		$homeCode = $room->getHomes()->getCode();

		$em->remove($room);
		$em->flush();

		return $this->redirect($this->generateUrl('home_show', array('id' => $homeId, 'code' => $homeCode ) ));
	}



	/**
     * @Route("/upload/room/{id}", name="room_upload")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function roomUploadImgAction( $id, Request $request)
	{
		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'room', $id ) );

		$FineUploadHandler = $this->get('cabinet.fine_upload');
		
		if( $request->query->get( 'type' ) ){
			if( $request->query->get( 'type' ) == 'layout' ||
				$request->query->get( 'type' ) == 'layout' || 
				$request->query->get( 'type' ) == 'reamingwalls' ||
				$request->query->get( 'type' ) == 'threedmodel' ||
				$request->query->get( 'type' ) == 'anket' 
			)
				$thumb = 'layout';
			else
				$thumb = 'slide';

			$response = $FineUploadHandler->getUploadImgAction( $dir, $id, 'Rooms', $thumb );
		}
		else
			$response = $FineUploadHandler->getUploadImgAction( $dir, $id, 'Rooms');

		return $response;
	}


	/**
     * Выод json списка загруженных картинок 
	 * @Route("/uploadlist/room/{id}", name="room_uploadlist")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function roomUploadListImgAction( $id, Request $request )
	{
		$uploadDir = join(DIRECTORY_SEPARATOR, array('img', 'room', $id));

		/** @var $FineUploadHandler \Top10\CabinetBundle\Service\FineUploadHandler */
		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$result = $FineUploadHandler->getSlidesUploadsSession( $uploadDir, $id, 'Rooms', $request->query->get( 'type' ) );

		return array( 'jsoninput' => $result );
	}



	/**
     * Edit a plan.
     *
     * @Route("/room/{room_id}/add/plan/", name="plan_add")
     * @Template("Top10CabinetBundle:Plan:edit_plan.html.twig")
     */
	public function planAddAction( $room_id, Request $request)
	{
		$security				= $this->get('security.context');
		$current_user			= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar			= $this->get('cabinet.translate_char');
		$em 					= $this->getDoctrine()->getManager();
		$repPlan				= $em->getRepository('Top10CabinetBundle:Plan');
		$repPlanForegin			= $em->getRepository('Top10CabinetBundle:Planforegin');
		//$repProperties		= $em->getRepository('Top10CabinetBundle:properties');
		$repMaterial			= $em->getRepository('Top10CabinetBundle:Material');
		$repMaterialforegin		= $em->getRepository('Top10CabinetBundle:Materialforegin');
		$repPlanproperties		= $em->getRepository('Top10CabinetBundle:Planproperties');
		$repPropertiesForegin	= $em->getRepository('Top10CabinetBundle:propertiesforegin');
		//$plan				= $repPlan->findAll();

		$router 			= $this->get('router'); 
		$breadcrumbs		= $this->get('white_october_breadcrumbs');

		$arrPlanForeginY	   = array();
		$arrPlanForeginParentY = array();

		//$room = $em->getRepository('Top10CabinetBundle:Rooms')->find( $room_id );
		//раствновка прав видиня неопубликованных комнат

		$rep = $em->getRepository('Top10CabinetBundle:Rooms');
		$roomSecurity = $this->get('cabinet.security_role')->getObject( $rep, $room_id );
		$room = $roomSecurity['object'];

		if( !$roomSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found room entity.');
		/*if( !$current_user )
			return $this->redirect( $this->generateUrl( 'fos_user_security_login' ) );

		if( $room->getAuthor() !== $current_user )
			throw $this->createNotFoundException('Not found plan entity.');*/

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			$room->getRoomstype()->getName() . '. ' . $room->getName(),
			$router->generate('room_show', array( 'id' => $room->getId(), 'code' => $room->getCode() ) )
		);

		$breadcrumbs->addItem( 'Создать план ремонта' );
		//--------/хлебные крошки----------------------//

		$plan = $repPlan
			->createQueryBuilder('p')
			->select('p')
			->andWhere( 'p.parent IS NULL' )
			->orderBy('p.sort', 'ASC')
			->getQuery()
			->getResult();

		$planServis = $this->get('cabinet.plan_servis');
		$planIndication = $planServis->getPlanIndication( $room ) ;

		if ( $request->isMethod('POST') /*&& $planForm->isValid()*/ ) {


			//--------Вставляем ИЗ РЕКВЕСТА ДАННЫЕ ПО ПЛАНУ----------------------//
			$arrReqPlan = $request->request->get('plan');

//print_r( $arrReqPlan );
			if( count($arrReqPlan)> 0){
				foreach( $arrReqPlan as $reqPlanId => $reqArrPlanVal ){
					foreach( $reqArrPlanVal as $reqPlanForeginId => $reqPlanVal ){
						$planItem = $repPlan->find( $reqPlanId );
						if( $planItem ){

							if( $reqPlanForeginId == 'NEW' ){
								/*$planForegin = $repPlanForegin->findOneBy( array(
									'rooms' => $room->getId(),
									'plan' => $planItem->getId() 
									) 
								);*/

								//if( !$planForegin )
								$planForegin = new planforegin();
								$planForegin->setFinished( 'N' );
							}
							else
								$planForegin = $repPlanForegin->find( $reqPlanForeginId );


							$planForegin->setRooms( $room );
							$planForegin->setPlan( $planItem );
							$planForegin->setValue( $reqPlanVal );

							if( $request->request->get('planforegin_name')[$reqPlanId][$reqPlanForeginId] )
								$planForegin->setName( $request->request->get('planforegin_name')[$reqPlanId][$reqPlanForeginId] );

							if( $request->request->get('planforegin_link')[$reqPlanId][$reqPlanForeginId] )
								$planForegin->setLink( $request->request->get('planforegin_link')[$reqPlanId][$reqPlanForeginId] );
							
							if( $request->request->get('planforegin_datebegin')[$reqPlanId][$reqPlanForeginId] )
								$planForegin->setDatebegin( new \DateTime( $request->request->get('planforegin_datebegin')[$reqPlanId][$reqPlanForeginId] ) );

							if( $request->request->get('planforegin_dateend')[$reqPlanId][$reqPlanForeginId] )
								$planForegin->setDateend( new \DateTime( $request->request->get('planforegin_dateend')[$reqPlanId][$reqPlanForeginId] ) );


							if( $request->request->get('planforegin_quantity')[$reqPlanId][$reqPlanForeginId] )
								$planForegin->setQuantity( $request->request->get('planforegin_quantity')[$reqPlanId][$reqPlanForeginId] );

							if( $request->request->get('planforegin_price')[$reqPlanId][$reqPlanForeginId] )
								$planForegin->setPrice( $request->request->get('planforegin_price')[$reqPlanId][$reqPlanForeginId] );

							if( $request->request->get('planforegin_priceworck')[$reqPlanId][$reqPlanForeginId] )
								$planForegin->setPriceworck( $request->request->get('planforegin_priceworck')[$reqPlanId][$reqPlanForeginId] );

							$em->persist( $planForegin );
							$em->flush();

							if( @$request->request->get('planforegin_add')[$reqPlanId] ){
								$planForeginAdd = new planforegin();
								$planForeginAdd->setRooms( $room );
								$planForeginAdd->setPlan( $planItem );
								$planForeginAdd->setValue( $reqPlanVal );
								$em->persist( $planForeginAdd );
								$em->flush();
							}

							//добавляем всех родителей Плана в planForegin
							$planServis = $this->get('cabinet.plan_servis');
							$arrPlanForeginParentY = $planServis->getPlanParetnInPlanForegin( $planItem->getParent(), $room ) ;
							$arrPlanForeginY = array_merge ($arrPlanForeginY, $arrPlanForeginParentY);

							//----Переименовываем временную папку для картинок  и поля в Slides----
							$dirname = 'planforegin-' . $room->getId() . '-' . $reqPlanId;
							$dirAdd = join( DIRECTORY_SEPARATOR, array( 'img', 'planforegin', $dirname ) );

							$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'planforegin', $planForegin->getId() ) );
							$dirOld = join( DIRECTORY_SEPARATOR, array( 'img', 'planforegin', $planForegin->getId() . 'old' ) );
							if ( file_exists( $dirAdd ) )
								if ( file_exists( $dir ) )
									rename($dir, $dirOld);

							if ( @rename($dirAdd, $dir) ){
								$conn = $em->getConnection();
								$rowsAffected = $conn->executeUpdate(
									"UPDATE `slides`
										SET planforegin_id = :planforegin_id,
											code = NULL,
											image = REPLACE( image, '". $dirname ."', '" . $planForegin->getId() . "' )
									WHERE code =:dirname",
									array(
										'planforegin_id' => $planForegin->getId(),
										'dirname' => $dirname,
										
									),
									array('ids' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
								);
							}
							//----/Переименовываем временную папку для картинок  и поля в Slides----

							$arrPlanForeginY[] = $planForegin->getId();

							//--------Добавляем материалы ---------------------------
							$arrReqMaterial = $request->request->get('materialforegin');
							if( $planForegin->getMaterialforegin() ){
								foreach( $planForegin->getMaterialforegin() as $materialforegin ){
									if( @$arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['name'] ){
										$materialforegin->setName( $arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['name'] );
									}

									if( @$arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['name'] ){
										$materialforegin->setLink( $arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['link'] );
									}

									if( @$arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['name'] ){
										$materialforegin->setQuantity( $arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['quantity'] );
									}

									if( @$arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['name'] ){
										$materialforegin->setMeasure( $arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['measure'] );
									}

									if( @$arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['name'] ){
										$materialforegin->setPrice( $arrReqMaterial[$planItem->getId()][$materialforegin->getId()]['price'] );
									}
								}
							}

							$arrReqMaterialNew = $request->request->get('materialforegin_new');

							if ( isset( $arrReqMaterialNew[$planItem->getId()][$reqPlanForeginId] ) ){
								foreach( $arrReqMaterialNew[$planItem->getId()][$reqPlanForeginId] as $materialId => $materialforeginNewMaterial ){
									$materialforegin = new materialforegin();

									$material = $repMaterial->find( $materialId );
									$materialforegin->setMaterial( $material );

									$materialforegin->setPlanforegin( $planForegin );

									$materialforegin->setName( $materialforeginNewMaterial['name'] );
									$materialforegin->setLink( $materialforeginNewMaterial['link'] );
									$materialforegin->setQuantity( $materialforeginNewMaterial['quantity'] );
									$materialforegin->setMeasure( $materialforeginNewMaterial['measure'] );
									$materialforegin->setPrice( $materialforeginNewMaterial['price'] );
									$em->persist( $materialforegin );
									$em->flush();
								}
							}
							//--------/Добавляем материалы ---------------------------


							//--------Добавляем свойства плана---------------------------
							$planproperties = $repPlanproperties->findByPlan( $planItem );
							$arrReqProperties = $request->request->get('properties');
							foreach( $planproperties as $planpropertie ){

								if( @$arrReqProperties[$planItem->getId()][$planpropertie->getProperties()->getId()] ){

									$propertiesForegin = $repPropertiesForegin->findOneBy( array(
										'properties' => $planpropertie->getProperties()->getId(),
										'planforegin' => $planForegin->getId() 
										)
									);

									if( !$propertiesForegin )
										$propertiesForegin = new propertiesforegin();

									$propertiesForegin->setProperties( $planpropertie->getProperties() );
									$propertiesForegin->setPlanforegin( $planForegin );
									$propertiesForegin->setValue( $arrReqProperties[$planItem->getId()][$planpropertie->getProperties()->getId()][$reqPlanForeginId] );
									$em->persist( $propertiesForegin );
									$em->flush();
								}
								else{
									$propertiesForegin = $repPropertiesForegin->findOneBy( array(
										'properties' => $planpropertie->getProperties()->getId(),
										'planforegin' => $planForegin->getId() 
										)
									);
									if( $propertiesForegin ){
										$em->remove($propertiesForegin);
										$em->flush();
									}
								}

							}
							//--------/Добавляем свойства плана---------------------------

						}
					}
				}

				//---------------Делаем не активные Планы не попавшие в массив $arrPlanForeginY-----------------//
				$qb = $em->createQueryBuilder();
				$q = $qb->update('Top10CabinetBundle:Planforegin', 'pf')
					->set('pf.value',':n')
						->setParameter(':n', 'N')
					->where('pf.rooms = :room')
						->setParameter('room', $room)
					->andWhere( $qb->expr()->notIn('pf.id', $arrPlanForeginY) )
					->getQuery();
				$q->execute();
				//---------------/Делаем не активные Планы не попавшие в массив $arrPlanForeginY-----------------//
			}
			//--------/Вставляем свойства----------------------//

			$em->flush();

			return $this->redirect( $this->generateUrl( 'plan_add', array( 'room_id' => $room->getId() ) ) );
		}


		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			//'planForm' => $planForm->createView(),
			'room' => $room,
			'plan' => $plan,
			'planIndication' => $planIndication,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

		return $result;
	}



	/**
     * Edit a plan.
     *
     * @Route("/room/{room_id}/add/anket/", name="anket_add")
     * @Template("Top10CabinetBundle:Anket:edit.html.twig")
     */
	public function anketAddAction( $room_id, Request $request)
	{
		$security				= $this->get('security.context');
		$current_user			= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar			= $this->get('cabinet.translate_char');
		$em 					= $this->getDoctrine()->getManager();
		$repAnket				= $em->getRepository('Top10CabinetBundle:Anket');

		//$plan				= $repPlan->findAll();

		$router 			= $this->get('router'); 
		$breadcrumbs		= $this->get('white_october_breadcrumbs');

		$arrPlanForeginY	   = array();
		$arrPlanForeginParentY = array();

		//$room = $em->getRepository('Top10CabinetBundle:Rooms')->find( $room_id );
		//растановка прав видиня неопубликованных комнат

		$rep = $em->getRepository('Top10CabinetBundle:Rooms');
		$roomSecurity = $this->get('cabinet.security_role')->getObject( $rep, $room_id );
		$room = $roomSecurity['object'];

		if( !$roomSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found room entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			$room->getRoomstype()->getName() . '. ' . $room->getName(),
			$router->generate('room_show', array( 'id' => $room->getId(), 'code' => $room->getCode() ) )
		);
		$breadcrumbs->addItem( 'Заполнить анкету' );
		//--------/хлебные крошки----------------------//

		$anket = $repAnket->findOneByRooms( $room->getId() );

		if( !$anket )
			$anket = new anket();

		$anketForm = $this->createForm( new anketForm( $anket ), $anket );
		$anketForm->bind($request);

		if ( $request->isMethod('POST') /*&& $roomForm->isValid()*/ ) {

			$anket->setRooms( $room );

			$em->persist( $anket );
			$em->flush();
		}

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//
		
		$result = array(
			'anketForm' => $anketForm->createView(),
			'room' => $room,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

		return $result;
	}

}