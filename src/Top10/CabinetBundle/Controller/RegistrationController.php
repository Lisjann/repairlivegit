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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
        $request = $this->container->get('request');
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $templating = $this->container->get('templating');
            
            $message = \Swift_Message::newInstance()
            	->setContentType("text/html")
	            ->setSubject('Новая заявка на регистрацию в Кабинете Оптовика')
	            ->setFrom( $this->container->getParameter('top10_cabinet.emails.default') )
	            ->setTo($this->container->getParameter('top10_cabinet.emails.manager'))
	            ->setBody($templating->render('Top10CabinetBundle:Mail:ManagerNewUser.html.twig', array('user' => $user)))
            ;
            $this->container->get('mailer')->send($message);
            
            $this->container->get('logger')->info(
            	sprintf('[mail] New user registration: %s', $user)
            );
            
            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $authUser = true;
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'registerForm' => $form->createView(),
        ));
    }

}
