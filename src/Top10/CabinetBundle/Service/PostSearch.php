<?php

namespace Top10\CabinetBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Knp\Component\Pager\Paginator;

//use Top10\CabinetBundle\Entity\ProductRepository;
//use Top10\CabinetBundle\Form\Model\PostFilter;
//use Top10\CabinetBundle\Form\PostFilterForm;

use Top10\CabinetBundle\Entity\Posts;
use Top10\CabinetBundle\Entity\Tags;

class PostSearch
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;
    /**
     * @var EntityManager $em
     */
    protected $em;
    /**
     * @var Paginator $paginator
     */
    protected $paginator;
    /**
     * @var Request $request
     */
    protected $request;
    /**
     * @var FormFactory $formFactory
     */
    protected $formFactory;

	public function __construct(ContainerInterface $container)
	{
		$this->em = $container->get('doctrine.orm.entity_manager');
		$this->paginator = $container->get('knp_paginator');
		$this->request = $container->get('request');
		$this->formFactory = $container->get('form.factory');
		$this->container = $container;
	}

	public function search( $arrWhere = null )
	{
		$rep = $this->em->getRepository('Top10CabinetBundle:Posts');

		$qbPosts = $rep->createQueryBuilder('p')
					->select('p');

		//----сортировка----
		$order = $this->request->get('order', array());


		if( isset( $order['pop'] ) && strtoupper( $order['pop'] ) == 'DESC' )
			$qbPosts->leftJoin('p.popposts', 'pp')
					->orderBy('pp.popposts', 'DESC');
		elseif( isset( $order['approved'] ) && strtoupper( $order['approved'] ) == 'ASC' )
			$qbPosts->orderBy( 'p.approved', 'ASC' );
		elseif( isset( $order['approved'] ) && strtoupper( $order['approved'] ) == 'DESC' )
			$qbPosts->orderBy( 'p.approved', 'DESC' );
		elseif( isset( $order['date'] ) && strtoupper( $order['date'] ) == 'ASC' )
			$qbPosts->orderBy( 'p.id', 'ASC' );
		elseif( isset( $order['date'] ) && strtoupper( $order['date'] ) == 'DESC' )
			$qbPosts->orderBy( 'p.id', 'DESC' );
		else
			$qbPosts->orderBy( 'p.id', 'DESC' );

		//опубликованные all для модерирования
		if( isset( $arrWhere['approved']) ){
			if( @$arrWhere['approved'] != 'all'  ){
				$qbPosts->andWhere( 'p.approved = :approved' )
					->setParameter( 'approved', $arrWhere['approved'] );
			}
		}
		else{
			$qbPosts->andWhere( 'p.approved = 1' );
		}

		//if( $filter->getTags() ){
		if( isset( $arrWhere['tags'] ) ){
			$qbPosts->leftJoin( 'p.tagsforegin', 't');
			$qbPosts->andWhere( 't.tags = :tags' )
				->setParameter( 'tags', $arrWhere['tags'] );
		}

		if( isset( $arrWhere['rooms'] ) ){
			$qbPosts->leftJoin( 'p.roomsposts', 'rp');
			$qbPosts->andWhere( 'rp.rooms = :rooms' )
				->setParameter( 'rooms', $arrWhere['rooms'] );
		}

		if( isset( $arrWhere['homes'] ) ){
			$qbPosts->andWhere( 'p.homes = :homes' )
				->setParameter( 'homes', $arrWhere['homes'] );
		}

		$pagination = $this->paginator->paginate(
			$qbPosts,
			$this->request->query->get( 'page', 1 ),
			10
		);

		return $pagination;
    }

}