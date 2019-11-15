<?php

namespace Top10\CabinetBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use Symfony\Component\Form\FormFactory;
//use Knp\Component\Pager\Paginator;


use Top10\CabinetBundle\Entity;

class Tags
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
    //protected $paginator;
    /**
     * @var Request $request
     */
    //protected $request;
    /**
     * @var FormFactory $formFactory
     */
    //protected $formFactory;

	public function __construct(ContainerInterface $container)
	{
		$this->em = $container->get('doctrine.orm.entity_manager');
		//$this->paginator = $container->get('knp_paginator');
		//$this->request = $container->get('request');
        //$this->formFactory = $container->get('form.factory');
        $this->container = $container;
	}

    public function tagsList( $post_id = null )
    {
		$postChecked = null;
		//$postsRep = $this->em->getRepository('Top10CabinetBundle:Tags');
		//$qbTags = $postsRep->createQueryBuilder('t');

		//$query = $em->createQuery('SELECT u, count(g.id) FROM Entities\User u JOIN u.groups g GROUP BY u.id');
		
		if( $post_id > 0 )
			$postChecked = ',(SELECT COUNT(tf2.posts) FROM Top10CabinetBundle:tagsforegin tf2 WHERE tf2.tags = tf.tags AND tf2.posts = ' . $post_id . ' ) checked';

		$qbTags = $this->em->createQuery('
            SELECT t.id
				   ,t.name
				   ,t.code
				   ,count(t.id) CNT
				   ' . $postChecked . '
				FROM Top10CabinetBundle:Tags t
					,Top10CabinetBundle:tagsforegin tf
				WHERE t.id = tf.tags
			GROUP BY t.id
			ORDER BY CNT DESC'
        );

		//$qbTags->addSelect('t')->andWhere('1 = :one')->setParameter('one', 1);

		//$tagsArray = $qbTags->getQuery()->getArrayResult();
		$tagsArray = $qbTags->getArrayResult();

	return $tagsArray;
    }

}