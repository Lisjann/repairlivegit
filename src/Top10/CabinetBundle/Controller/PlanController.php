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
use Top10\CabinetBundle\Entity\Plan;
use Top10\CabinetBundle\Entity\Planforegin;
use Top10\CabinetBundle\Entity\Propertiesforegin;
use Top10\CabinetBundle\Entity\Slides;

use Top10\CabinetBundle\Form\planForm;
use Knp\Component\Pager\Paginator;


/**
 * @Route("/")
 */
class PlanController extends Controller
{

	/**
     * Edit a plan.
     *
     * @Route("/plan", name="plan")
     * @Template("Top10CabinetBundle:Plan:index.html.twig")
     */
	public function indexAction( Request $request)
	{
		$security				= $this->get('security.context');
		if ( !$security->isGranted('ROLE_ADMIN') )
			throw $this->createNotFoundException('Not found plan entity.');

		$current_user	= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar	= $this->get('cabinet.translate_char');
		$em 			= $this->getDoctrine()->getManager();
		$repPlan		= $em->getRepository('Top10CabinetBundle:Plan');

		$arrPlanForeginY	   = array();
		$arrPlanForeginParentY = array();

		//$room = $em->getRepository('Top10CabinetBundle:Rooms')->find( $room_id );
		//раствновка прав видиня неопубликованных комнат

		$plan = $repPlan
			->createQueryBuilder('p')
			->select('p')
			->andWhere( 'p.parent IS NULL' )
			->orderBy('p.sort', 'ASC')
			->getQuery()
			->getResult();

		$result = array(
			'plan' => $plan,
			'notid' => $request->query->get( 'notid' )
		);

		return $result;
	}

	/**
     * Show a plan.
     *
     * @Route("/plan/{id}/{code}", name="plan_show")
     * @Template("Top10CabinetBundle:Plan:showplan.html.twig")
     */
    public function planShowAction( $id, $code, Request $request)
    {
		$security 	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();
		$router 	  = $this->get('router'); 
		$breadcrumbs  = $this->get('white_october_breadcrumbs');
		$em 		  = $this->getDoctrine()->getManager();

		/** @var $plan plans */
		$rep = $em->getRepository('Top10CabinetBundle:Plan');

		$plan = $rep->find($id);

		if( !$plan )
			throw $this->createNotFoundException('Not found plan entity.');

		//--------хлебные крошки----------------------//
		/*$breadcrumbs->addItem( 
			'Дома',
			$router->generate('home')
		);
		$breadcrumbs->addItem( 
			$plan->getHomes()->getHomestype()->getName() . '. ' . $plan->getHomes()->getName(),
			$router->generate('home_show', array( 'id' => $plan->getHomes()->getId(), 'code' => $plan->getHomes()->getCode() ) )
		);*/

		$breadcrumbs->addItem( $plan->getName() );
		//--------/хлебные крошки----------------------//


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
			'plan' => $plan,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu
		);

		return $result;
	}


	/**
     * Show a plan.
     *
     * @Route("/edit/plan", name="plan_edit")
     * @Template("Top10CabinetBundle:Plan:edit.html.twig")
     */
	public function planEditAction( Request $request)
	{
		$security				= $this->get('security.context');
		if ( !$security->isGranted('ROLE_ADMIN') )
			throw $this->createNotFoundException('Not found plan entity.');

		$current_user			= $security->getToken()->getUser();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$em 					= $this->getDoctrine()->getManager();
		$repPlan				= $em->getRepository('Top10CabinetBundle:Plan');

		if( $request->query->get( 'id' ) ){
			$plan = $repPlan->find( $request->query->get( 'id' ) );
			if(!$plan)
				$plan = new plan;
		}
		else{
			$plan = new plan;
		}


		//--------хлебные крошки----------------------//
		$router 			= $this->get('router'); 
		$breadcrumbs		= $this->get('white_october_breadcrumbs');

		$breadcrumbs->addItem( 
			'Комнаты',
			$router->generate('room')
		);

		$breadcrumbs->addItem( 'Редактировать план ремонта' );
		//--------/хлебные крошки----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//


		$planForm = $this->createForm( new planForm( $plan ), $plan );

		$planForm->bind($request);

		if ( $request->isMethod('POST') /*&& $planForm->isValid()*/ ) {
			if( $planForm->get('parentint')->getData() != null ){
				$planParent = $repPlan->find( $planForm->get('parentint')->getData() );
				$plan->setParent( $planParent );
			}
			$em->persist( $plan );
			$em->flush();

			return $this->redirect( $this->generateUrl( 'plan_edit', array( 'id' => $plan->getId() ) ) );

		}

		$result = array(
			'planForm' => $planForm->createView(),
			'plan' => $plan,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

		return $result;

	}

	/**
     * Delete a plan.
     *
	 * @Route("/delete/plan/{id}", name="plan_delete")
    */
    public function planDeleteAction($id)
	{
		$security = $this->get('security.context');

		if ( !$security->isGranted('ROLE_ADMIN') )
			throw $this->createNotFoundException('Not found plan entity.');

		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository('Top10CabinetBundle:Plan');

		$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$plan = $rep->find( $id );

		foreach( $plan->getChildren() as $children ){
			$planChildren = $rep->find( $children->getId() );
			$planChildren->setParent( $plan->getParent()  );
			$em->persist( $planChildren );
		}

		$em->remove($plan);
		$em->flush();

		return $this->redirect($this->generateUrl('plan_edit'));
	}


	/**
     * Delete a planforegin.
     *
	 * @Route("/delete/planforegin/{id}", name="planforegin_delete")
     */
    public function planforeginDeleteAction($id)
	{
    	$em = $this->getDoctrine()->getManager();
    	$security = $this->get('security.context');

		$rep = $em->getRepository('Top10CabinetBundle:Planforegin');

		//растановка прав видиня неопубликованных комнат
		$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$planforegin = $postSecurity['object'];

		if( !$postSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found planforegin entity.');

		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'planforegin', $id ) );
		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$FineUploadHandler->removeDir2( $dir );

		$roomId = $planforegin->getRooms()->getId();

		$em->remove($planforegin);
		$em->flush();

		return $this->redirect($this->generateUrl('plan_add', array('room_id' => $roomId ) ));
	}


	/**
     * @Route("/upload/planforegin/{id}", name="planforegin_upload")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function planforeginUploadImgAction( $id, Request $request)
	{
		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'planforegin', $id ) );

		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$response = $FineUploadHandler->getUploadImgAction( $dir, $id, 'Planforegin', 'plan' );

		return $response;

		/*$em = $this->getDoctrine()->getManager();
		$planForegin = $em->getRepository('Top10CabinetBundle:Planforegin')->find($id);

		$FineUploadHandler = $this->get('cabinet.fine_upload');

		$FineUploadHandler->allowedExtensions = array(); // all files types allowed by default

		// Specify max file size in bytes.
		$FineUploadHandler->sizeLimit = null;

		// Specify the input name set in the javascript.
		$FineUploadHandler->inputName = "qqfiles"; // matches Fine Uploader's default inputName value by default

		$FineUploadHandler->dirName = $id;

		// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
		//$FineUploadHandler->chunksFolder = "chunks";

		$method = $_SERVER["REQUEST_METHOD"];

		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'planforegin', $id ) );

		//print_r ($_REQUEST );

		if ( $request->isMethod('POST') &&  $request->request->get('qqfilename') ) {
			header("Content-Type: text/plain");

			// Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
			// For example: /myserver/handlers/endpoint.php?done
			if ( $request->query->get('done') ) {
				$result = $FineUploadHandler->combineChunks($dir);
			}
			// Handles upload requests
			else {

				$translateChar = $this->get('cabinet.translate_char');
				$_REQUEST['qqfilename'] = str_replace (" ",  "", $translateChar->getInTranslateToEn($_REQUEST['qqfilename']) );

				// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
				$result = $FineUploadHandler->handleUpload($dir);

				// To return a name used for uploaded file you can use the following line.
				$result["uploadName"] = $FineUploadHandler->getUploadName();

				//--------------------THUMB image-----------------------------
				$imagemanagerResponse = $this->container
					->get('liip_imagine.controller')
					->filterReplaceFileAction(
						$request,
						$result['terget'],
						join( DIRECTORY_SEPARATOR, array( $_SERVER["DOCUMENT_ROOT"], $result['terget'] ) ),
						'plan'
					);
				//--------------------/THUMB image-----------------------------

				//--------------------------ВСТАВКА ФАЙЛА В ТАБЛИЦУ SLIDERS--------------------------
				$arrSlidesJson = $FineUploadHandler->getSlidesUploadsSession( $dir, $id, 'Planforegin' );
				$arrSlides = json_decode($arrSlidesJson);

				//print_r( $arrSlides );
				$sort = count( $arrSlides ) + 1;

				$slides = new slides();

				if( $planForegin )
					$slides->setPlanforegin( $planForegin );
				else
					$slides->setCode( $id );

				$slides->setImage( join( DIRECTORY_SEPARATOR, array( '', $result['terget'] ) ) );
				$slides->setSort( $sort );
				$em->persist( $slides );
				$em->flush();

				//--------------------------/ВСТАВКА ФАЙЛА В ТАБЛИЦУ SLIDERS--------------------------
			}

			$response = array( 'jsoninput' => json_encode( $result ) );

			//$response = new Response(json_encode($result));
			//$response->headers->set('Content-Type', 'application/json; charset=UTF-8');
			//return $this->json( $result );

		}
		// for delete file requests
		else if ( $request->request->get('_method') == "DELETE" ) {

			$result = $FineUploadHandler->handleDelete($dir);

			$response =  array( 'jsoninput' => json_encode( $result ) );
			//--------------------------УДАЛЕНИЕ ФАЙЛА ИЗ ТАБЛИЦЫ SLIDERS--------------------------
			if( $result['success'] == true ){
				$qb = $em->createQueryBuilder();

				$qb->delete('Top10CabinetBundle:Slides','s')
					->andWhere('s.image LIKE :uuid')
						->setParameter('uuid', '%'.$result['uuid'].'%');

				if( $planForegin )
					$qb->andWhere('s.planforegin = :id')->setParameter( 'id', $id );
				else
					$qb->andWhere('s.code = :id')->setParameter( 'id', $id );

				$q = $qb->getQuery()->execute();

			}
			$FineUploadHandler->getSlidesUploadsSession( $dir, $id, 'Planforegin' );//перезаписываем сортировку
			//--------------------------/УДАЛЕНИЕ ФАЙЛА ИЗ ТАБЛИЦЫ SLIDERS--------------------------

			//$response = new Response(json_encode($result));
			//$response->headers->set('Content-Type', 'application/json; charset=UTF-8');
		}
		else {
			//header("HTTP/1.0 405 Method Not Allowed");
			$response =  array( 'jsoninput' => null );
		}

		return $response;*/
	}
	
	
	/**
     * Выод json списка загруженных картинок 
	 * @Route("/uploadlist/planforegin/{id}", name="planforegin_uploadlist")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function planforeginUploadListImgAction( $id )
	{
		$uploadDir = join(DIRECTORY_SEPARATOR, array('img', 'planforegin', $id));

		/** @var $FineUploadHandler \Top10\CabinetBundle\Service\FineUploadHandler */
		$FineUploadHandler = $this->get('cabinet.fine_upload');

		$result = $FineUploadHandler->getSlidesUploadsSession( $uploadDir, $id, 'Planforegin' );

		return array( 'jsoninput' => $result );
	}

}