<?php

namespace Acme\HelloBundle\;

//use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpFoundation\Response;

class AcmeDemoBundle
{
	public function indexAction($name)
	{
		return new Response('<html><body>Hello '.$name.'!</body></html>');
	}
}
