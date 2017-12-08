<?php

namespace Top10\CabinetBundle\Service;

//use Doctrine\ORM\EntityManager;
//use Symfony\Component\Security\Core\SecurityContext;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use Top10\CabinetBundle\Entity;

class SecurityRole
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;
    /**
     * @var EntityManager $em
     */
    //protected $em;
	/**
     * @var EntityManager $security
     */
    protected $security;
    /**
     * @var Request $request
     */
    //protected $request;

	public function __construct(ContainerInterface $container)
	{
		//$this->em = $container->get('doctrine.orm.entity_manager');
		//$this->request = $container->get('request');
		$this->container = $container;
		$this->security = $container->get('security.context');
	}

	public function getObject( $repository, $id )
	{
		$current_user 	= $this->security->getToken()->getUser();
		$object = $repository->find( $id );
		$isWriter = false;

		if ( $this->security->isGranted('ROLE_ADMIN') )
			$isWriter = true;
		elseif ( $object->getAuthor() === $current_user )
			$isWriter = true;
		else{
			$isWriter = false;
			$object = $repository->findOneBy(array('id' => $id, 'approved' => 1));
		}

		return array( 'object' => $object, 'isWriter' => $isWriter );
    }

}