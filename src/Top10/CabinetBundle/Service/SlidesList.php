<?php

namespace Top10\CabinetBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Knp\Component\Pager\Paginator;

use Top10\CabinetBundle\Entity\ProductRepository;
use Top10\CabinetBundle\Form\Model\CatalogFilter;
use Top10\CabinetBundle\Form\CatalogFilterForm;

use Top10\CabinetBundle\Entity;

class SlidesList
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

    public function SlidesList()
    {
		$slidesRep = $this->em->getRepository('Top10CabinetBundle:Slides');

		$qbSlides = $slidesRep->createQueryBuilder('s');

		$qbSlides->addSelect('s')->addOrderBy('s.sort', 'ASC');

		$slidesPagination = $qbSlides->getQuery()->getArrayResult();

	return $slidesPagination;
    }

}