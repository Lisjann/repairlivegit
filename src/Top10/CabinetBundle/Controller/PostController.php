<?php
namespace Top10\CabinetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Top10\CabinetBundle\Entity;
use Top10\CabinetBundle\Entity\Posts;
use Top10\CabinetBundle\Entity\roomsposts;
use Top10\CabinetBundle\Entity\tagsforegin;
use Top10\CabinetBundle\Entity\planforegin;
use Top10\CabinetBundle\Entity\Comments;
use Top10\CabinetBundle\Entity\Likes;
use Top10\CabinetBundle\Entity\Slides;

use Top10\CabinetBundle\Form\postForm;
use Top10\CabinetBundle\Form\commentForm;



/**
 * @Route("/")
 */
class PostController extends Controller
{
	/**
     * Post list.
     *
     * @Route("/post", name="post")
     * @Template("Top10CabinetBundle:Post:index.html.twig")
     */
	public function indexAction( Request $request )
	{
		$security	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();

		//--------Слайды----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\SlidesList */
		//$SlidesList = $this->get('cabinet.slides_list');
		//$arrSlides = $SlidesList->SlidesList();
		//--------/Слайды----------------------//

		//--------Посты----------------------//
		/** @var $postSearch \Top10\CabinetBundle\Service\PostSearch */
		$postSearch = $this->get('cabinet.post_search');
		if ( $security->isGranted('ROLE_ADMIN') )
			$arrPostsPaginator = $postSearch->search( array( 'approved' => 'all' ) );
		else
			$arrPostsPaginator = $postSearch->search();
		//--------/Посты----------------------//

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
			'pagination' => $arrPostsPaginator,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
		);
		return $result;
	}


	/**
     * Post list.
     *
     * @Route("/post/tag/{tag_code}", name="post_tag")
     * @Template("Top10CabinetBundle:Post:index.html.twig")
     */
	public function indexTag( $tag_code, Request $request )
	{
		$security	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();
		$em			  = $this->getDoctrine()->getManager();

		//--------Слайды----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\SlidesList */
		$SlidesList = $this->get('cabinet.slides_list');
		$arrSlides = $SlidesList->SlidesList();
		//--------/Слайды----------------------//

		//--------Посты----------------------//
		/** @var $postSearch \Top10\CabinetBundle\Service\PostSearch */
		$tagsPost =  $em->getRepository('Top10CabinetBundle:Tags')->findOneByCode( $tag_code );

		$postSearch = $this->get('cabinet.post_search');

		if ( $security->isGranted('ROLE_ADMIN') )
			$arrPostsPaginator = $postSearch->search( array( 'approved' => 'all', 'tags' => $tagsPost ) );
		else
			$arrPostsPaginator = $postSearch->search( array( 'tags' => $tagsPost ) );
		//--------/Посты----------------------//

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
			'slides' => $arrSlides,
			'pagination' => $arrPostsPaginator,
			'tags' => $arrTags,
			'tagspost' => $tagsPost,
			'homesRoomsMenu' => $homesRoomsMenu,
		);
		return $result;
	}

	/**
     * Show a post.
     *
     * @Route("/post/{id}/{code}", name="post_show")
     * @Template("Top10CabinetBundle:Post:showpost.html.twig")
     */
    public function postShowAction( $id, $code, Request $request)
    {
		$security 		= $this->get('security.context');
		$current_user 	= $security->getToken()->getUser();
		$router 		= $this->get('router'); 
        $breadcrumbs 	= $this->get('white_october_breadcrumbs');
		$em 		 	= $this->getDoctrine()->getManager();

		$rep = $em->getRepository('Top10CabinetBundle:Posts');

		/** @var $post Posts */
		$post = $rep->find( $id );

		//раставновка прав видинья неопубликованных комнат
		$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$post = $postSecurity['object'];

		if( !$post )
			throw $this->createNotFoundException('Not found post entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			$post->getHomes()->getHomestype()->getName() . '. ' . $post->getHomes()->getName(),
			$router->generate( 'home_show', array( 'id' => $post->getHomes()->getId(), 'code' => $post->getHomes()->getCode() ) )
		);
		/*$breadcrumbs->addItem( 
			$post->getRooms()->getRoomstype()->getName() . '. ' . $post->getRooms()->getName(),
			$router->generate( 'room_show', array( 'id' => $post->getRooms()->getId(), 'code' => $post->getRooms()->getCode() ) )
		);*/
        $breadcrumbs->addItem( $post->getName() );
		//--------/хлебные крошки----------------------//


		//--------так же рекомендуем посмотреть----------------------//
		$qb = $rep->createQueryBuilder('p');
		$qb->addSelect('p')
			->andWhere('p.approved = 1')
			->andWhere('p.id <> :id')
				->setParameter( 'id', $post->getId() )
			->andWhere('p.created < :created')
				->setParameter( 'created', $post->getCreated()->format("Y-m-d G:i") )
			->addOrderBy('p.created', 'DESC')
			->setMaxResults(1);
		$postPrev = $qb->getQuery()->getArrayResult();

		$qb = $rep->createQueryBuilder('p');
		$qb->addSelect('p')
			->andWhere('p.approved = 1')
			->andWhere('p.id <> :id')
				->setParameter( 'id', $post->getId() )
			->andWhere('p.created > :created')
				->setParameter( 'created', $post->getCreated()->format("Y-m-d G:i") )
			->addOrderBy('p.created', 'ASC')
			->setMaxResults(2);
		$postNext = $qb->getQuery()->getArrayResult();

		$arrPostNextPrev = $result = array_merge ($postPrev, $postNext);
		//--------/так же рекомендуем посмотреть----------------------//


		//--------Коментарии----------------------//
		// create a Comments and give it some dummy data for this example
		$repComments = $em->getRepository('Top10CabinetBundle:Comments');

		$Comments = new Comments();
        
		$commentsForm = $this->createForm( new commentForm( $current_user ), $Comments );
		$commentsForm->bind($request);

		if ( $request->isMethod('POST') )
		{
			if ($commentsForm->isValid()) {
				$Comments->setPosts( $post );

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
				$Comments->setPosts( $post );
				if( $security->isGranted('IS_AUTHENTICATED_FULLY') )
					$Comments->setUser( $current_user );

				$em->persist( $Comments );
				$em->flush();
			}
		}

		/** @var $arrComments CommentsRepository */
		$arrComments = $repComments->getComments( array( 'posts' => $post->getId() ) );
		//--------Коментарии----------------------//

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//
		
		//--------Лайки----------------------//
		$likesClass['enabled'] = 'likes';
		$likesClass['disabled'] = 'likes_disabled';
		if ( $em->getRepository('Top10CabinetBundle:Likes')->getCountLikesForPost( $post->getId(), null, $_SERVER['REMOTE_ADDR'] ) == 0 )
			$likesClass['live'] = 'likes';
		else
			$likesClass['live'] = 'likes_disabled';
		//--------/Лайки----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $em->getRepository('Top10CabinetBundle:homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'post' => $post,
			'postNextPrev' => $arrPostNextPrev,
			'comments' => $arrComments,
			'commentsCount' => $repComments->getCountComments( array( 'posts' => $post->getId() ) ),
			'commentsForm' => $commentsForm->createView(),
			//'commentsFormResponse' => $commentsFormResponse->createView(),
			'likesCount' => $em->getRepository('Top10CabinetBundle:Likes')->getCountLikesForPost( $post->getId() ),
			'likesClass' => $likesClass,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
			'isWriter' => $postSecurity['isWriter']
			//'slides' => $sResult,
		);

		return $result;
    }


	/**
     * Edit a post.
     *
     * @Route("/edit/post/{id}", name="post_edit")
     * @Template("Top10CabinetBundle:Post:editpost.html.twig")
     */
	public function postEditAction( $id, Request $request)
	{
		$security 		= $this->get('security.context');
		$current_user 	= $security->getToken()->getUser();
		$router 		= $this->get('router'); 
		$breadcrumbs 	= $this->get('white_october_breadcrumbs');
		$em 			= $this->getDoctrine()->getManager();
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar 		= $this->get('cabinet.translate_char');
		$repPlanForeginmeta = $em->getRepository('Top10CabinetBundle:Planforeginmeta');
		$repPlanForegin		= $em->getRepository('Top10CabinetBundle:Planforegin');
		$arrPlanForeginY	= array();

		$rep = $em->getRepository('Top10CabinetBundle:Posts');
		/** @var $post Posts */
		$post = $rep->find( $id );

		//растановка прав видиня неопубликованных комнат
		$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$post = $postSecurity['object'];

		if( !$postSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found post entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			$post->getHomes()->getHomestype()->getName() . '. ' . $post->getHomes()->getName(),
			$router->generate( 'home_show', array( 'id' => $post->getHomes()->getId(), 'code' => $post->getHomes()->getCode() ) )
		);
		$breadcrumbs->addItem( 'Редактирование поста' );
		//--------/хлебные крошки----------------------//


		//--------План----------------------//
		$planForeginmeta = $repPlanForeginmeta
			->createQueryBuilder('pf')
			->select('pf')
			->andWhere( 'pf.parent IS NULL' )
			->orderBy('pf.sort', 'ASC')
			->getQuery()
			->getResult();
		//--------/План----------------------//

		$postForm = $this->createForm( new postForm( $post, $security->isGranted('ROLE_ADMIN') ), $post );

		//валидация
		$valid = 1;
		$errorValids = null;

		if ( $request->isMethod('POST') )
		{
			$postForm->bind($request);

			if( $postForm->get('name')->getData() == null ){
				$valid = 0;
				$errorValids[] = '<b>Пустое название</b>, Придумайте название своего поста';
			}
			if( $security->isGranted('ROLE_ADMIN') )
				if( $postForm->get('mainBig')->getData() && $postForm->get('mainSmall')->getData() ){
					$valid = 0;
					$errorValids[] = '<b>На главной Большая</b>, <b>На главной Маленькая</b>. Выберете только одну из них';
				}

			//if ( $postForm->isValid() ) {
			if ( $valid == 1 ){

				if( $postForm->get('name')->getData() != null ){
					$post->setCode( strtolower( $translateChar->getInTranslateToEn( $postForm->get('name')->getData(), true ) ) );
				}

				$em->persist( $post );

				//--------------вставляем комнаты----------------
				$qb = $em->createQueryBuilder();
				$q = $qb->delete('Top10CabinetBundle:Roomsposts','rp')
					->where('rp.posts = :posts')
					->setParameter('posts', $post)
					->getQuery();
				$q->execute();

				$arrRoom = $request->request->get('room');

				if( count($arrRoom)> 0 ){
					$repRooms = $em->getRepository('Top10CabinetBundle:Rooms');

					foreach( $arrRoom as $rooms ){

						$room = $repRooms->find( $rooms );

						$roomsposts = new roomsposts();
						$roomsposts->setPosts( $post );
						$roomsposts->setRooms( $room );
						$em->persist( $roomsposts );
					}
				}
				//--------------вставляем комнаты----------------

				//--------------вставляем теги----------------
				$qb = $em->createQueryBuilder();
				$q = $qb->delete('Top10CabinetBundle:Tagsforegin','tf')
					->where('tf.posts = :posts')
					->setParameter('posts', $post)
					->getQuery();
				$q->execute();

				$arrTagspop = $request->request->get('tagspop');

				if( count($arrTagspop)> 0){
					$repTags = $em->getRepository('Top10CabinetBundle:Tags');

					foreach( $arrTagspop as $tagpop ){

						$tag = $repTags->find( $tagpop );

						$tagsforegin = new tagsforegin();
						$tagsforegin->setPosts( $post );
						$tagsforegin->setTags( $tag );
						$em->persist( $tagsforegin );
					}
				}
				//--------------вставляем теги----------------
				
				//--------------вставляем план----------------
				$arrReqPlan = $request->request->get('plan');
				if( count($arrReqPlan)> 0){
					foreach( $arrReqPlan as $reqPlanForeginId => $reqPlanForeginVal ){
						$planForegin = $repPlanForegin->find( $reqPlanForeginId );
						$planForegin->setPosts( $post );
						if( isset($request->request->get('finished')[$planForegin->getId()]) )
							$planForegin->setFinished( $request->request->get('finished')[$planForegin->getId()] );
						$em->persist( $planForegin );

						$planServis = $this->get('cabinet.plan_servis');
						$arrPlanForeginParentY = $planServis->getPlanParetnInPlanForegin( $planForegin->getPlan()->getParent(), $planForegin->getRooms(), 'finished' );
						$arrPlanForeginY = array_merge ($arrPlanForeginY, $arrPlanForeginParentY);

						$arrPlanForeginY[] = $planForegin->getId();
					}
				}
				//--------------/вставляем план----------------

				$em->flush();

				//---------------Делаем не активные Планы не попавшие в массив $arrPlanForeginY-----------------//
				foreach( $arrRoom as $rooms ){
					$room = $repRooms->find( $rooms );

					$qb = $em->createQueryBuilder();
					$qb->update('Top10CabinetBundle:Planforegin', 'pf')
						->set('pf.finished',':n')
							->setParameter(':n', 'N')
						->set('pf.posts','null')
						->where('pf.rooms = :room')
							->setParameter('room', $room)
						->andWhere('pf.posts = :post')
							->setParameter('post', $post);

					if( count($arrPlanForeginY) > 0 )
						$qb->andWhere( $qb->expr()->notIn('pf.id', $arrPlanForeginY) );

					$q = $qb->getQuery();

					$q->execute();
					$em->flush();
				}
				//---------------/Делаем не активные Планы не попавшие в массив $arrPlanForeginY-----------------//

				return new RedirectResponse( $this->generateUrl( 'post_show', array( 'id' => $post->getId(), 'code' => $post->getCode() ) ) );
			}
		}

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList( $post->getId() );
		//--------/Теги----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:homes');

		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'post' => $post,
			'postForm' => $postForm->createView(),
			'errorValids' => $errorValids,
			'planForeginmeta' => $planForeginmeta,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
			'isWriter' => $postSecurity['isWriter'],
		);

		return $result;
	}


	/**
     * Add a post.
     *
     * @Route("home/{home_id}/add/post", name="post_add")
     * @Template("Top10CabinetBundle:Post:addpost.html.twig")
     */
	public function postAddAction( $home_id, Request $request)
	{
		$security 		= $this->get('security.context');
		$current_user 	= $security->getToken()->getUser();
		$router 		= $this->get('router'); 
		$breadcrumbs 	= $this->get('white_october_breadcrumbs');
		$em 			= $this->getDoctrine()->getManager();

		$FineUploadHandler 	= $this->get('cabinet.fine_upload');
		$dirname 			= 'postuser' . $current_user->getId();//при введении пользователей сделать название папки user.id/некий идентификатор/
		$dirAdd 			= join( DIRECTORY_SEPARATOR, array( 'img', 'post', $dirname ) );

		//--------Автор----------------------//
		$arrAuthor = $em->getRepository('Top10CabinetBundle:User')->find( 23 );
		//--------/Автор----------------------//
		/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
		$translateChar = $this->get('cabinet.translate_char');

		$repPlanForeginmeta	  = $em->getRepository('Top10CabinetBundle:Planforeginmeta');
		$repPlanForegin	= $em->getRepository('Top10CabinetBundle:Planforegin');
		$arrPlanForeginY= array();

		$home = $em->getRepository('Top10CabinetBundle:Homes')->find( $home_id );

		/** @var $post Posts */
		$post = new posts();

		if( !$post || $home->getAuthor() !== $current_user )
			throw $this->createNotFoundException('Not found post entity.');

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			$home->getHomestype()->getName() . '. ' . $home->getName(),
			$router->generate( 'home_show', array( 'id' => $home->getId(), 'code' => $home->getCode() ) )
		);
        $breadcrumbs->addItem( 'Добавление поста' );
		//--------/хлебные крошки----------------------//

		//--------План----------------------//
		$planForeginmeta = $repPlanForeginmeta
			->createQueryBuilder('pf')
			->select('pf')
			->andWhere( 'pf.parent IS NULL' )
			->orderBy('pf.sort', 'ASC')
			->getQuery()
			->getResult();
		//--------/План----------------------//

		$postForm = $this->createForm( new postForm( $post, $security->isGranted('ROLE_ADMIN') ), $post );

		//валидация
		$valid = 1;
		$errorValids = null;

		if ( $request->isMethod('POST') )
		{
			$postForm->bind($request);

			$rep = $em->getRepository('Top10CabinetBundle:Posts');

			if( $security->isGranted('ROLE_ADMIN') )
				if( $postForm->get('mainBig')->getData() && $postForm->get('mainSmall')->getData()  ){
					$valid = 0;
					$errorValids[] = '<b>На главной Большая</b>, <b>На главной Маленькая</b>. Выберете только одну из них';
				}


			//if ( $postForm->isValid() ) {
			if ( $valid == 1 ) {

				$post->setAuthor( $current_user );

				$post->setHomes( $home );

				if( $postForm->get('name')->getData() != null )
					$post->setCode( strtolower( $translateChar->getInTranslateToEn( $postForm->get('name')->getData(), true ) ) );

				$em->persist( $post );


				//--------------вставляем комнаты----------------
				$arrRoom = $request->request->get('room');

				if( $arrRoom ){
					$repRooms = $em->getRepository('Top10CabinetBundle:Rooms');

					foreach( $arrRoom as $rooms ){

						$room = $repRooms->find( $rooms );

						$roomsposts = new roomsposts();
						$roomsposts->setPosts( $post );
						$roomsposts->setRooms( $room );
						$em->persist( $roomsposts );
					}
				}
				//--------------/вставляем комнаты----------------

				//--------------вставляем теги----------------
				$arrTagspop = $request->request->get('tagspop');

				if( $arrTagspop ){
					$repTags = $em->getRepository('Top10CabinetBundle:Tags');

					foreach( $arrTagspop as $tagpop ){

						$tag = $repTags->find( $tagpop );

						$tagsforegin = new tagsforegin();
						$tagsforegin->setPosts( $post );
						$tagsforegin->setTags( $tag );
						$em->persist( $tagsforegin );
					}
				}
				//--------------/вставляем теги----------------



				//--------------вставляем план----------------
				$arrReqPlan = $request->request->get('plan');
				if( count($arrReqPlan)> 0){
					foreach( $arrReqPlan as $reqPlanForeginId => $reqPlanForeginVal ){
						$planForegin = $repPlanForegin->find( $reqPlanForeginId );
						$planForegin->setPosts( $post );
						if( isset($request->request->get('finished')[$planForegin->getId()]) )
							$planForegin->setFinished( $request->request->get('finished')[$planForegin->getId()] );
						$em->persist( $planForegin );

						$planServis = $this->get('cabinet.plan_servis');
						$arrPlanForeginParentY = $planServis->getPlanParetnInPlanForegin( $planForegin->getPlan()->getParent(), $planForegin->getRooms(), 'finished' );
						$arrPlanForeginY = array_merge ($arrPlanForeginY, $arrPlanForeginParentY);

						$arrPlanForeginY[] = $planForegin->getId();
					}
				}
				//--------------/вставляем план----------------

				$em->flush();

				//---------------Делаем не активные Планы не попавшие в массив $arrPlanForeginY-----------------//
				/*foreach( $arrRoom as $rooms ){
					$room = $repRooms->find( $rooms );

					$qb = $em->createQueryBuilder();
					$qb->update('Top10CabinetBundle:Planforegin', 'pf')
						->set('pf.finished',':n')
							->setParameter(':n', 'N')
						->set('pf.posts','null')
						->where('pf.rooms = :room')
							->setParameter('room', $room)
						->andWhere('pf.posts = :post')
							->setParameter('post', $post);;

					if( count($arrPlanForeginY) > 0 )
						$qb->andWhere( $qb->expr()->notIn('pf.id', $arrPlanForeginY) );

					$q = $qb->getQuery();

					$q->execute();
					$em->flush();
				}*/
				//---------------/Делаем не активные Планы не попавшие в массив $arrPlanForeginY-----------------//


				$em->flush();

				//переименовываем временную папку для картинок и поля в Slides
				$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'post', $post->getId() ) );
				$dirOld = join( DIRECTORY_SEPARATOR, array( 'img', 'post', $post->getId() . 'old' ) );

				if (file_exists( $dirAdd )){
					if ( file_exists( $dir ) )
						rename($dir, $dirOld);

					if ( rename( $dirAdd, $dir ) ){
						$conn = $em->getConnection();
						$rowsAffected = $conn->executeUpdate(
							"UPDATE `slides`
								SET post_id = :post_id,
									code = NULL,
									image = REPLACE( image, '". $dirname ."', '" . $post->getId() . "' )
							WHERE code =:dirname",
							array(
								'post_id' => $post->getId(),
								'dirname' => $dirname,
								
							),
							array('ids' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
						);
					}
				}

				if( $postForm->get('content')->getData() != null ){
					$replace = array(
						$dirname => $post->getId(),
						'src="../../' => 'src="/',
						'src="../' => 'src="/'
					);

					$content = strtr( $postForm->get('content')->getData(), $replace );
					$post->setContent( $content );

					$image = strtr( $postForm->get('image')->getData(), $replace );
					$post->setImage( $image );
				}

				$em->flush();

				if( $postForm->get('name')->getData() != null )
					return new RedirectResponse( $this->generateUrl( 'post_show', array( 'id' => $post->getId(), 'code' => $post->getCode() ) ) );
			}
		}
		else{
			//-----------------------Удалеие фйлов из папки postuser### и полей из Slides---------------------------
			$FineUploadHandler->removeDir2( $dirAdd );

			$qb = $em->createQueryBuilder();
			$qb->delete('Top10CabinetBundle:Slides','s')
				->andWhere('s.code = :id')
					->setParameter( 'id', $dirname )
				->getQuery()
				->execute();
			//-----------------------/Удалеие фйлов из папки postuser### и полей из Slides---------------------------
		}

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList( $post->getId() );
		//--------/Теги----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:homes');

		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//



		$result = array(
			//'post' => $post,
			'postForm' => $postForm->createView(),
			'home' => $home,
			'errorValids' => $errorValids,
			'author' => $arrAuthor,
			'planForeginmeta' => $planForeginmeta,
			'tags' => $arrTags,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

		return $result;
	}




	/**
     * Delete a post in order.
     *
	 * @Route("/delete/post/{id}", name="post_delete")
     */
    public function postDeleteAction($id){

		$em = $this->getDoctrine()->getManager();
		$security = $this->get('security.context');
		/** @var $item ProductsOrders */
		$rep = $em->getRepository('Top10CabinetBundle:Posts');

		//растановка прав видиня неопубликованных комнат
		$postSecurity = $this->get('cabinet.security_role')->getObject( $rep, $id );
		$post = $postSecurity['object'];

		if( !$postSecurity['isWriter'] )
			throw $this->createNotFoundException('Not found post entity.');

		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'post', $id ) );
		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$FineUploadHandler->removeDir2( $dir );

		$homesId = $post->getHomes()->getId();
		$homesCode = $post->getHomes()->getCode();

    	$em->remove($post);
    	$em->flush();

    	//$this->get('session')->getFlashBag()->add('success', "Товар из заказа успешно удален");
    	return $this->redirect($this->generateUrl('home_show', array('id' => $homesId, 'code' => $homesCode ) ));
    }



	/**
     * @Route("/upload/post/{id}", name="post_upload")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function postUploadImgAction( $id, Request $request)
	{
		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'post', $id ) );

		$FineUploadHandler = $this->get('cabinet.fine_upload');
		$response = $FineUploadHandler->getUploadImgAction( $dir, $id, 'Posts', 'img' );

		return $response;
	}


	/**
     * Выод json списка загруженных картинок 
	 * @Route("/uploadlist/post/{id}", name="post_uploadlist")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function postUploadListImgAction( $id )
	{
		$uploadDir = join( DIRECTORY_SEPARATOR, array( 'img', 'post', $id ) );

		$FineUploadHandler = $this->get('cabinet.fine_upload');

		//$result = $FineUploadHandler->getUploadsSession( $uploadDir );
		$result = $FineUploadHandler->getSlidesUploadsSession( $uploadDir, $id );

		return array( 'jsoninput' => $result );
	}



}