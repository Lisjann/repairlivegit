<?php
namespace Top10\CabinetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Top10\CabinetBundle\Entity;
use Top10\CabinetBundle\Entity\Homes;
use Top10\CabinetBundle\Entity\Comments;
use Top10\CabinetBundle\Entity\Likes;
use Top10\CabinetBundle\Entity\Slides;
use Top10\CabinetBundle\Entity\Properties;
use Top10\CabinetBundle\Entity\Propertiesforegin;

use Top10\CabinetBundle\Form\homeForm;
use Top10\CabinetBundle\Form\commentForm;
use Knp\Component\Pager\Paginator;


/**
 * @Route("/")
 */
class HomeController extends Controller
{
	/**
     * home list.
     *
     * @Route("/home", name="home")
     * @Template("Top10CabinetBundle:Home:index.html.twig")
     */

	public function indexAction( Request $request )
	{

		$paginator = $this->get('knp_paginator');
		$security 		= $this->get('security.context');
		$current_user 	= $security->getToken()->getUser();

		//--------Дом----------------------//
		$rep = $this->getDoctrine()->getRepository('Top10CabinetBundle:Homes');

		$qbHomes = $rep->createQueryBuilder('h');
		$qbHomes->addSelect('h')->addOrderBy('h.created', 'DESC');
		if ( !$security->isGranted('ROLE_ADMIN') )
			$qbHomes->andWhere('h.approved = 1');

		$homes = $paginator->paginate(
			$qbHomes,
			$request->query->get( 'page', 1 ),
			20
		);
		//--------/Дом----------------------//

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
			//'slides' => $arrSlides,
			'homes' => $homes,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
		);
		return $result;
	}


	/**
     * Show a home.
     *
     * @Route("/home/{id}/{code}", name="home_show")
     * @Template("Top10CabinetBundle:Home:showhome.html.twig")
     */
	public function homeShowAction( $id, $code, Request $request)
	{
		$security 		= $this->get('security.context');
		$current_user 	= $security->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		$router = $this->get('router'); 
		$breadcrumbs = $this->get('white_october_breadcrumbs');

		$rep = $this->getDoctrine()->getRepository('Top10CabinetBundle:Homes');
		
		//раставновка прав видиня неопубликованных комнат
		$homeSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$home = $homeSecurity['object'];

		if( !$home )
			throw $this->createNotFoundException('Not found home entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			'Дома',
			$router->generate('home')
		);
        $breadcrumbs->addItem( $home->getHomestype()->getName() . '. ' . $home->getName() );
		//--------/хлебные крошки----------------------//


		//--------Комнаты----------------------//
		$rep = $this->getDoctrine()->getRepository('Top10CabinetBundle:Rooms');

		$qbRooms = $rep->createQueryBuilder('r');

		$qbRooms->addSelect('r')
				->andWhere( 'r.homes = :homes' )
					->setParameter( 'homes', $home )
				->addOrderBy('r.created', 'DESC');

		if ( !$security->isGranted('ROLE_ADMIN') )
			$qbRooms->andWhere('r.approved = 1');
		
		$rooms = $qbRooms->getQuery()->getResult();

		/*$rooms = $paginator->paginate(
			$qbRooms,
			$request->query->get( 'pageroom', 1 ),
			10
		);*/
		//--------/Комнаты----------------------//

		//--------Посты----------------------//
		/** @var $postSearch \Top10\CabinetBundle\Service\PostSearch */
		$postSearch = $this->get('cabinet.post_search');

		//раствновка прав видиния неопубликованных постов
		if ( $security->isGranted('ROLE_ADMIN') )
			$arrPostsPaginator = $postSearch->search( array( 'homes' => $home, 'approved' => 'all' ) );
		elseif ( $home->getAuthor() === $current_user )
			$arrPostsPaginator = $postSearch->search( array( 'homes' => $home, 'approved' => 'all' ) );
		else
			$arrPostsPaginator = $postSearch->search( array( 'homes' => $home ) );

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
				$Comments->setHomes( $home );

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
				$Comments->setHomes( $home );
				if( $security->isGranted('IS_AUTHENTICATED_FULLY') )
					$Comments->setUser( $current_user );

				$em->persist( $Comments );
				$em->flush();
			}
		}

		/** @var $arrComments CommentsRepository */
		$arrComments = $repComments->getComments( array( 'homes' => $home->getId() ) );
		//--------Коментарии----------------------//

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//


		//--------Лайки----------------------//
		$likesClass['enabled'] = 'likes';
		$likesClass['disabled'] = 'likes_disabled';
		if ( $em->getRepository('Top10CabinetBundle:Likes')->getCountLikes( array( 'homes' => $home->getId() ), $_SERVER['REMOTE_ADDR'] ) == 0 )
			$likesClass['live'] = 'likes';
		else
			$likesClass['live'] = 'likes_disabled';
		//--------/Лайки----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:homes');

		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//


		$result = array(
			//'slides' => $arrSlides,
			'home' => $home,
			'rooms' => $rooms,
			'posts' => $arrPostsPaginator,
			'comments' => $arrComments,
			'commentsCount' => $repComments->getCountComments(  array( 'homes' => $home->getId() ) ),
			'commentsForm' => $commentsForm->createView(),
			'likesCount' => $em->getRepository('Top10CabinetBundle:Likes')->getCountLikes( array( 'homes' => $home->getId() ) ),
			'likesClass' => $likesClass,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
			'isWriter' => $homeSecurity['isWriter'],
			//'slides' => $sResult,
		);

		return $result;
    }




	/**
     * Edit a home.
     *
     * @Route("/edit/home/{id}", name="home_edit")
     * @Template("Top10CabinetBundle:Home:edithome.html.twig")
     */
	public function homeEditAction( $id, Request $request)
	{
		$security	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();

		$em  = $this->getDoctrine()->getManager();
		$rep = $em->getRepository('Top10CabinetBundle:Homes');

		$repHomestype		  = $em->getRepository('Top10CabinetBundle:Homestype');
		$repProperties		  = $em->getRepository('Top10CabinetBundle:Properties');
		$repPropertiesForegin = $em->getRepository('Top10CabinetBundle:propertiesforegin');
		$properties			  = $repProperties->findByIsin( 'homes' );

		$router 	 = $this->get('router'); 
		$breadcrumbs = $this->get('white_october_breadcrumbs');

		//растановка прав видиня неопубликованных комнат
		$homeSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$home = $homeSecurity['object'];


		if( !$current_user )
			return $this->redirect( $this->generateUrl( 'fos_user_security_login' ) );

		if( !$homeSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found home entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			'Мой профиль',
			$router->generate( 'fos_user_profile_show' )
		);
		$breadcrumbs->addItem( 'Редактировать дом' );
		//--------/хлебные крошки----------------------//

		if (!$home)
			$home = new homes();

		$homestype = $repHomestype
			   ->createQueryBuilder('h')
			   ->select('h')
			   ->getQuery()
			   ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

		$homeForm = $this->createForm( new homeForm( $home, $homestype, $security->isGranted('ROLE_ADMIN') ), $home );

		$homeForm->bind($request);

		if ( $request->isMethod('POST') /*&& $homeForm->isValid()*/ ) {

			if( $homeForm->get('homestypeint')->getData() != null ){
				$homestype = $repHomestype->find( $homeForm->get('homestypeint')->getData() );
				$home->setHomestype( $homestype );
			}

			//$home->setAuthor( $current_user );

			$em->persist( $home );

			//--------Добавляем свойства дома---------------------------
			$arrReqProperties = $request->request->get('properties');
			
			foreach( $properties as $propertie ){

				if( @$arrReqProperties[$propertie->getId()] ){

					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'homes' => $home->getId() 
						)
					);

					if( !$propertiesForegin )
						$propertiesForegin = new propertiesforegin();

					$propertiesForegin->setProperties( $propertie );
					$propertiesForegin->setHomes( $home );
					$propertiesForegin->setValue( $arrReqProperties[$propertie->getId()] );
					$em->persist( $propertiesForegin );
					$em->flush();
				}
				else{
					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'homes' => $home->getId() 
						)
					);
					if( $propertiesForegin ){
						$em->remove($propertiesForegin);
						$em->flush();
					}
				}
			}
			//--------/Добавляем свойства плана---------------------------

			$em->flush();

			return $this->redirect( $this->generateUrl( 'home_show', array( 'id' => $home->getId(), 'code' => $home->getCode() ) ) );
		}

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:Homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'homeForm' => $homeForm->createView(),
			'home' => $home,
			'tags' => $arrTags,
			'tagspost' => null,
			'properties' => $properties,
			'homesRoomsMenu' => $homesRoomsMenu,
		);


		return $result;
	}



	/**
     * Edit a home.
     *
     * @Route("/add/home/", name="home_add")
     * @Template("Top10CabinetBundle:Home:edithome.html.twig")
     */
	public function homeAddAction( Request $request)
	{
		$security		= $this->get('security.context');
		$current_user	= $security->getToken()->getUser();
		$translateChar	= $this->get('cabinet.translate_char');

		$em 				  = $this->getDoctrine()->getManager();
		$repHomestype		  = $em->getRepository('Top10CabinetBundle:Homestype');
		$repProperties		  = $em->getRepository('Top10CabinetBundle:Properties');
		$repPropertiesForegin = $em->getRepository('Top10CabinetBundle:propertiesforegin');
		$properties			  = $repProperties->findByIsin( 'homes' );

		$router				= $this->get('router'); 
		$breadcrumbs		= $this->get('white_october_breadcrumbs');
		$FineUploadHandler	= $this->get('cabinet.fine_upload');
		$dirname 			= 'homeuser' . $current_user->getId();
		$dirAdd				= join( DIRECTORY_SEPARATOR, array( 'img', 'home', $dirname ) );

		if( !$current_user )
			return $this->redirect( $this->generateUrl( 'fos_user_security_login') );

		$home = new homes();

		if( !$home )
			throw $this->createNotFoundException('Not found home entity.');
		

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			'Мой профиль',
			$router->generate( 'fos_user_profile_show' )
		);
		$breadcrumbs->addItem( 'Добавить дом' );
		//--------/хлебные крошки----------------------//

		$homestype = $repHomestype
				->createQueryBuilder('h')
				->select('h')
				->getQuery()
				->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

		$homeForm = $this->createForm( new homeForm( $home, $homestype, $security->isGranted('ROLE_ADMIN') ), $home );

		$homeForm->bind($request);

		if ( $request->isMethod('POST') /*&& $homeForm->isValid()*/ ) {

			if( $homeForm->get('homestypeint')->getData() != null ){
				$homestype = $repHomestype->find( $homeForm->get('homestypeint')->getData() );
				$home->setHomestype( $homestype );
			}

			$home->setAuthor( $current_user );

			$code = $translateChar->getInTranslateToEn( $homeForm->get('name')->getData(), true );
			$home->setCode( strtolower($translateChar->getInTranslateToEn( $code )) );

			$em->persist( $home );
			$em->flush();

			//--------Добавляем свойства дома---------------------------
			$arrReqProperties = $request->request->get('properties');

			foreach( $properties as $propertie ){

				if( @$arrReqProperties[$propertie->getId()] ){

					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'homes' => $home->getId() 
						)
					);

					if( !$propertiesForegin )
						$propertiesForegin = new propertiesforegin();

					$propertiesForegin->setProperties( $propertie );
					$propertiesForegin->setHomes( $home );
					$propertiesForegin->setValue( $arrReqProperties[$propertie->getId()] );
					$em->persist( $propertiesForegin );
					$em->flush();
				}
				else{
					$propertiesForegin = $repPropertiesForegin->findOneBy( array(
						'properties' => $propertie->getId(),
						'homes' => $home->getId() 
						)
					);
					if( $propertiesForegin ){
						$em->remove($propertiesForegin);
						$em->flush();
					}
				}
			}
			//--------/Добавляем свойства дома---------------------------


			//переименовываем временную папку для картинок  и поля в Slides
			$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'home', $home->getId() ) );
			$dirOld = join( DIRECTORY_SEPARATOR, array( 'img', 'home', $home->getId() . 'old' ) );
			if (file_exists( $dirAdd ))
				if ( file_exists( $dir ) )
					rename($dir, $dirOld);

				if ( rename($dirAdd, $dir) ){

					$conn = $em->getConnection();
					$rowsAffected = $conn->executeUpdate(
						"UPDATE `slides`
							SET homes_id = :homes_id,
								code = NULL,
								image = REPLACE( image, '". $dirname ."', '" . $home->getId() . "' )
						WHERE code =:dirname",
						array(
							'homes_id' => $home->getId(),
							'dirname' => $dirname,
							
						),
						array('ids' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
					);
				}

			return $this->redirect( $this->generateUrl( 'home_show', array( 'id' => $home->getId(), 'code' => $home->getCode() ) ) );
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
			'homeForm' => $homeForm->createView(),
			'tags' => $arrTags,
			'tagspost' => null,
			'properties' => $properties,
			'homesRoomsMenu' => $homesRoomsMenu,
		);


		return $result;
	}



	/**
     * Delete a home.
     *
	 * @Route("/delete/home/{id}", name="home_delete")
     */
    public function homeDeleteAction($id)
	{
    	$em = $this->getDoctrine()->getManager();
    	$security = $this->get('security.context');

        $rep = $em->getRepository('Top10CabinetBundle:Homes');

        //растановка прав видиня неопубликованных комнат
		$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$home = $postSecurity['object'];

		if( !$postSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found Home entity.');

		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'home', $id ) );
		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$FineUploadHandler->removeDir2( $dir );

    	$authorName = $home->getAuthor()->getUsername();

		$em->remove($home);
    	$em->flush();

		return $this->redirect( $this->generateUrl( 'user_show', array( 'username' => $authorName ) ) );
    }



	/**
     * @Route("/upload/home/{id}", name="home_upload")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function homeUploadImgAction( $id, Request $request)
	{
		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'home', $id ) );

		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$response = $FineUploadHandler->getUploadImgAction( $dir, $id, 'Homes' );

		return $response;
	}


	/**
     * Выод json списка загруженных картинок 
	 * @Route("/uploadlist/home/{id}", name="home_uploadlist")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function homeUploadListImgAction( $id )
	{
		$uploadDir = join(DIRECTORY_SEPARATOR, array('img', 'home', $id));

		/** @var $FineUploadHandler \Top10\CabinetBundle\Service\FineUploadHandler */
		$FineUploadHandler = $this->get('cabinet.fine_upload');

		$result = $FineUploadHandler->getSlidesUploadsSession( $uploadDir, $id, 'Homes' );

		return array( 'jsoninput' => $result );
	}

}