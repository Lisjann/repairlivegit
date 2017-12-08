<?php

namespace Top10\CabinetBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityRepository;

use Top10\CabinetBundle\Entity\User;
use Top10\CabinetBundle\Entity;

class DefaultController extends Controller
{
	
    /**
     * @Route("/")
     * @Template()
     */
	public function indexAction()
    {
		$security	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();
		$paginator	  = $this->container->get('knp_paginator');

		$rep = $this->getDoctrine()->getRepository('Top10CabinetBundle:Posts');
		$qb = $rep->createQueryBuilder('p');
		$qb->addSelect('p')
			->leftJoin('p.author', 'a', 'WITH', 'a.enabled = 1')
			->andWhere('p.approved = 1')
			->andWhere('p.mainBig = 1 OR p.mainSmall = 1')
			;

		//Свежие
		$dateQb = clone $qb;
		$dateQb->addOrderBy('p.id', 'DESC')->setMaxResults( 4 );
		$postMainDate = $dateQb->getQuery()->getResult();


		//Интересные
		$interestQb = clone $qb;
		$interestQb
			->leftJoin('p.popposts', 'pp')
			->orderBy('pp.popposts', 'DESC')
			->andWhere('p.interesting = 1')
			->addOrderBy('p.created', 'DESC')
			->setMaxResults( 4 );

		$postMainInterest = $interestQb->getQuery()->getResult();

		//Полезные
		$usefulQb = clone $qb;
		$usefulQb
			->leftJoin('p.popposts', 'pp')
			->orderBy('pp.popposts', 'DESC')
			->andWhere('p.useful = 1')
			->addOrderBy('p.created', 'ASC')
			->setMaxResults( 4 );
		$postMainUseful = $usefulQb->getQuery()->getResult();

		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$tResult = $tags->tagsList();

		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:homes');

		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'postMainDate' => $postMainDate,
			'postMainInterest' => $postMainInterest,
			'postMainUseful' => $postMainUseful,
			'tags' => $tResult,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

        return $result;

    }

	/**
     * about list.
     *
     * @Route("/about", name="about")
     * @Template("Top10CabinetBundle:Default:about.html.twig")
     */
	public function aboutAction( Request $request )
	{
		$security 	  = $this->get('security.context');
		$current_user = $security->getToken()->getUser();

		/** @var $tags \Top10\CabinetBundle\Service\Tags */
		$tags = $this->get('cabinet.tags');
		$tResult = $tags->tagsList();
		
		//--------Меню Дом c Комнатами----------------------//
		$repHomes = $this->getDoctrine()->getRepository('Top10CabinetBundle:Homes');
		$homesRoomsMenu = $repHomes->getHomes( array( 'author' => $current_user ) );
		//--------/Меню  Дом c Комнатами----------------------//

		$result = array(
			'tags' => $tResult,
			'tagspost' => null,
			'homesRoomsMenu' => $homesRoomsMenu,
		);

        return $result;
	}
}
