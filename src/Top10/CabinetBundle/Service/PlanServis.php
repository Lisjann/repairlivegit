<?php

namespace Top10\CabinetBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Top10\CabinetBundle\Entity\Planforegin;
use Top10\CabinetBundle\Entity\Planforeginmeta;

class PlanServis
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
     * @var Request $request
     */
    protected $request;


	public function __construct(ContainerInterface $container)
	{
		$this->em = $container->get('doctrine.orm.entity_manager');
		$this->request = $container->get('request');
		$this->container = $container;
	}

	public function getPlanParetnInPlanForegin( $paretn, $room, $updateitem = 'value' )
	{
		$repPlanForegin = $this->em->getRepository('Top10CabinetBundle:Planforegin');
		$arrPlanForeginY = array();

		if( $paretn ){
			$planForeginParent = $repPlanForegin->findOneBy( array(
				'rooms' => $room->getId(),
				'plan' => $paretn->getId()
				)
			);

			//вставляем родительские Планы
			if( !$planForeginParent )
				$planForeginParent = new planforegin();

			$planForeginParent->setRooms( $room );
			$planForeginParent->setPlan( $paretn );

			if( $updateitem == 'value' )
				$planForeginParent->setValue( 'Y' );
			//if( $updateitem == 'finished' )
				//$planForeginParent->setFinished( 'Y' );

			$this->em->persist( $planForeginParent );
			$this->em->flush();

			$arrPlanForeginY = $this->getPlanParetnInPlanForegin( $paretn->getParent(), $room, $updateitem );
			$arrPlanForeginY[] = $planForeginParent->getId();

		}
		return $arrPlanForeginY;
    }

	public function getPlanIndication( $room )
	{
		$repPlanForeginmeta	  = $this->em->getRepository('Top10CabinetBundle:Planforeginmeta');
		$get = array();

		$pfDayPriceworcksum = $repPlanForeginmeta
			->createQueryBuilder('pf')
			->select('SUM( pf.dayworck * p.complexity ) dayworcksum, SUM( pf.pricematerial) pricesum, SUM( pf.priceworck * pf.quantity) priceworcksum')
			->leftJoin( 'pf.plan', 'p')
			->andWhere('pf.value = :value')
				->setParameter( 'value', 'Y' )
			->andWhere('pf.rooms = :room')
				->setParameter( 'room', $room )
			
			->getQuery()
			->getResult();

		$pfDayPriceworcksumFinished = $repPlanForeginmeta
			->createQueryBuilder('pf')
			->select('SUM( pf.dayworck * p.complexity ) dayworcksum, SUM( pf.pricematerial ) pricesum, SUM( pf.priceworck * pf.quantity ) priceworcksum')
			->leftJoin( 'pf.plan', 'p')
			->andWhere('pf.value = :value')
				->setParameter( 'value', 'Y' )
			->andWhere('pf.finished = :finished')
				->setParameter( 'finished', 'Y' )
			->andWhere('pf.rooms = :room')
				->setParameter( 'room', $room )
			->getQuery()
			->getResult();

		if( $pfDayPriceworcksum[0]['dayworcksum'] > 0 && $pfDayPriceworcksumFinished[0]['dayworcksum'] > 0 )
			$get['procentWorck'] = floor( 100/( $pfDayPriceworcksum[0]['dayworcksum'] / $pfDayPriceworcksumFinished[0]['dayworcksum'] ) );
		else
			$get['procentWorck'] = 0;

		$get['pricesum'] = $pfDayPriceworcksum[0]['pricesum'];
		$get['priceworcksum'] = $pfDayPriceworcksum[0]['priceworcksum'];
		$get['pricesumFinished'] = $pfDayPriceworcksumFinished[0]['pricesum'];
		$get['priceworcksumFinished'] = $pfDayPriceworcksumFinished[0]['priceworcksum'];

		return $get;
    }

}