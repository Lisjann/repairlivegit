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

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Top10\CabinetBundle\Entity;
use Top10\CabinetBundle\Entity\Comments;
use Top10\CabinetBundle\Entity\Likes;

use Top10\CabinetBundle\Form\commentForm;


class LikeCommentController extends Controller
{

	/**
     * plus a like post.
     *
	 * @Route("/like/{type}/{type_id}", name="like")
     * @Template("Top10CabinetBundle:Like:index.html.twig")
     */
    public function likeAction( $type, $type_id )
    {
		$security 		= $this->get('security.context');
		$current_user 	= $security->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();

		//$repType = $em->getRepository( 'Top10CabinetBundle:' . $type );

		switch ($type) {
			case "posts":
				$repType = $em->getRepository( 'Top10CabinetBundle:Posts' );
				break;
			case "comments":
				$repType = $em->getRepository( 'Top10CabinetBundle:Comments' );
				break;
			case "rooms":
				$repType = $em->getRepository( 'Top10CabinetBundle:Rooms' );
				break;
			case "homes":
				$repType = $em->getRepository( 'Top10CabinetBundle:Homes' );
				break;
		}

		$types = $repType->find( $type_id );

		$repLike = $em->getRepository('Top10CabinetBundle:Likes');

		if ( $repLike->getCountLikes( array( $type => $types->getId() ), $_SERVER['REMOTE_ADDR'] ) == 0 ){
			$Likes = new Likes();

			switch ($type) {
			case "posts":
				$Likes->setPosts( $types );
				break;
			case "comments":
				$Likes->setComments( $types );
				break;
			case "rooms":
				$Likes->setRooms( $types );
				break;
			case "homes":
				$Likes->setHomes( $types );
				break;
			}

			if( $security->isGranted('IS_AUTHENTICATED_FULLY') )
				$Likes->setUser( $current_user );

			$Likes->setIp( $_SERVER['REMOTE_ADDR'] );
			$Likes->setLikes( 1 );
			$em->persist( $Likes );
			$em->flush();
		}
		else{
			throw $this->createNotFoundException('Not found like entity.');
		}


		$result = array(
			'likesCount' => $repLike->getCountLikes( array( $type => $types->getId() ) ),
		);

		return $result;
	}


	/**
     * Edit a commetn response.
     *
* @Route("/commentresponse/{type}/{type_id}/{type_code}/{parent_id}", name="commentresponse_edit")
     * @Template("Top10CabinetBundle:Comments:response.html.twig")
     */
    public function commetnResponseEdit( $type, $type_id, $type_code, $parent_id )
    {
		$security 		= $this->get('security.context');
		$current_user 	= $security->getToken()->getUser();
		$Comments = new Comments();

		$commentsFormResponse = $this->createForm( new commentForm($current_user, 'response'), $Comments );

		$result = array(
			'typecr' => $type,
			'type_id' => $type_id,
			'type_code' => $type_code,
			'parent_id' => $parent_id,
			'commentsFormResponse' => $commentsFormResponse->createView(),
		);

		return $result;
    }

}