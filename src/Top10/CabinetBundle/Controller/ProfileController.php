<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Top10\CabinetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as BaseController;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class ProfileController extends Controller
{
   
    public function showAction()
    {
        $breadcrumbs = $this->container->get('white_october_breadcrumbs');
		$user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

		//--------хлебные крошки----------------------//
        $breadcrumbs->addItem( 'Мой профиль' );
		//--------/хлебные крошки----------------------//

		//--------Дом c Комнатами----------------------//
		$rep = $this->getDoctrine()->getRepository('Top10CabinetBundle:Homes');

		$homes = $rep->findByAuthor( $user );

		/*$qbhomes = $rep->createQueryBuilder('h');
		$qbhomes->addSelect('h', 's', 'c', 'a', 'ht', 'l', 'r', 'rs', 'rl', 'rc')
			->leftJoin('h.slides', 's', 'WITH', 's.current = 1')
			->leftJoin('h.likes', 'l')
			->leftJoin('h.comments', 'c', 'WITH', 'c.approved = 1')
			->leftJoin('h.author', 'a', 'WITH', 'a.enabled = 1')
			->andWhere( 'a.id = :author' )
				->setParameter( 'author', $user->getId() )
			->leftJoin('h.homestype', 'ht')
			->leftJoin('h.rooms', 'r', 'WITH', 'r.approved = 1')
			->leftJoin('r.slides', 'rs', 'WITH', 'rs.current = 1')
			->leftJoin('r.likes', 'rl')
			->leftJoin('r.comments', 'rc')
			->andWhere('h.approved = 1')
			->addOrderBy('h.created', 'ASC');

		$homes = $qbhomes->getQuery()->getArrayResult();*/
		//--------/Дом c Комнатами----------------------//

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//


        return $this->container->get('templating')
        	->renderResponse( 'FOSUserBundle:Profile:show.html.' . $this->container->getParameter('fos_user.template.engine'),
				array( 'user' => $user,
					   'homes' => $homes,
					   'homesRoomsMenu' => $homes,
					   'tags' => $arrTags,
					   'tagspost' => null,
				)
			);
    }


    public function editAction()
    {
        $request = $this->container->get('request');
		$breadcrumbs = $this->container->get('white_october_breadcrumbs');
		$router = $this->get('router'); 

        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

		//--------хлебные крошки----------------------//
		$breadcrumbs->addItem( 
			'Мой профиль',
			$router->generate( 'fos_user_profile_show' )
		);
		$breadcrumbs->addItem( 'Редактировать' );
		//--------/хлебные крошки----------------------//

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:homes');

		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//

        $form = $this->container->get('fos_user.profile.form');
        $formHandler = $this->container->get('fos_user.profile.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            //$this->setFlash('fos_user_success', 'profile.flash.updated');

            //return new RedirectResponse($this->getRedirectionUrl($user));
			return new RedirectResponse($this->generateUrl('user_show', array( 'username' => $user->getUsername() )));
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array( 
				'form' => $form->createView(),
				'homesRoomsMenu' => $homesRoomsMenu,
				'tags' => $arrTags,
				'tagspost' => null,
				
			)
        );
    }

	/**
     * Post list.
     *
     * @Route("/profile/{username}", name="user_show")
     * @Template("FOSUserBundle:Profile:show.html.twig")
     */
	public function showUsernameAction( $username, Request $request  )
	{
		$repUser = $this->getDoctrine()->getRepository('Top10CabinetBundle:User');
		$user = $repUser->findOneByUsername( $username );
		$current_user = $this->container->get('security.context')->getToken()->getUser();

		if (!is_object($user) || !$user instanceof UserInterface)
            throw new AccessDeniedException('This user does not have access to this section.');
		//--------/Автор----------------------//


		//--------Дом c Комнатами----------------------//
		$rep = $this->getDoctrine()->getRepository('Top10CabinetBundle:Homes');

		$homes = $rep->findByAuthor( $user );

		$homesRoomsMenu = $rep->findByAuthor( $current_user );
		//--------/Дом c Комнатами----------------------//


		//--------Теги----------------------//
		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$arrTags = $tags->tagsList();
		//--------/Теги----------------------//

		$result = array(
			'user' => $user,
			'homes' => $homes,
			'homesRoomsMenu' => $homesRoomsMenu,
			'tags' => $arrTags,
			'tagspost' => null,
		);
		return $result;
	}
 

	/**
     * @Route("/upload/profile", name="profile_upload")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function profileUploadImgAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

		/** @var $FineUploadHandler \Top10\CabinetBundle\Service\FineUploadHandler */
		$FineUploadHandler = $this->get('cabinet.fine_upload');

		$FineUploadHandler->allowedExtensions = array(); // all files types allowed by default

		// Specify max file size in bytes.
		$FineUploadHandler->sizeLimit = null;

		// Specify the input name set in the javascript.
		$FineUploadHandler->inputName = "qqfiles"; // matches Fine Uploader's default inputName value by default

		$FineUploadHandler->dirName = $user->getId();

		// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
		//$FineUploadHandler->chunksFolder = "chunks";

		$method = $_SERVER["REQUEST_METHOD"];

		$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'profile', $user->getId() ) );

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

				/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
				$translateChar = $this->get('cabinet.translate_char');
				$_REQUEST['qqfilename'] = $translateChar->getInTranslateToEn($_REQUEST['qqfilename']);

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
						'avatar'
					);
				//--------------------/THUMB image-----------------------------
			}


			//--------------------------ВСТАВКА URL АВАТАРА--------------------------
			if( $result['success'] == true ){
				$user->setAvatar( DIRECTORY_SEPARATOR . $result['terget'] );
				$em->persist( $user );
			}
			//--------------------------/ВСТАВКА URL АВАТАРА--------------------------

			$response = array( 'jsoninput' => json_encode( $result ) );

		}
		// for delete file requests
		else if ( $request->request->get('_method') == "DELETE" ) {

			$result = $FineUploadHandler->handleDelete($dir);

			$response =  array( 'jsoninput' => json_encode( $result ) );

			//--------------------------УДАЛЕНИЕ URL АВАТАРА--------------------------
			if( $result['success'] == true ){
				$user->setAvatar( null );
				$em->persist( $user );
			}
			//--------------------------/УДАЛЕНИЕ URL АВАТАРА--------------------------

			//$response = new Response(json_encode($result));
			//$response->headers->set('Content-Type', 'application/json; charset=UTF-8');
		}
		else {
			//header("HTTP/1.0 405 Method Not Allowed");
			$response =  array( 'jsoninput' => null );
		}

		$em->flush();
		return $response;
	}


	/**
     * Выод json списка загруженных картинок 
	 * @Route("/uploadlist/profile", name="profile_uploadlist")
	 * @Template("Top10CabinetBundle:Json:uploadt.html.twig")
    */
	public function roomUploadListImgAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface)
            throw new AccessDeniedException('This user does not have access to this section.');


		/** @var $FineUploadHandler \Top10\CabinetBundle\Service\FineUploadHandler */
		$FineUploadHandler = $this->get('cabinet.fine_upload');

		// Specify max file size in bytes.
		$FineUploadHandler->sizeLimit = null;

		// Specify the input name set in the javascript.
		$FineUploadHandler->inputName = "qqfiles"; // matches Fine Uploader's default inputName value by default

		$uploadDir = join(DIRECTORY_SEPARATOR, array('img', 'profile', $user->getId() ));

		$result = $FineUploadHandler->getUploadsSession( $uploadDir );

		return array( 'jsoninput' => $result );
	}

}
