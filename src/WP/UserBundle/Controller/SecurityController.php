<?php

namespace WP\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as Controller;
use Symfony\Component\HttpFoundation\Request;
use WP\UserBundle\Entity\User;
use WP\UserBundle\Form\UserType;

class SecurityController extends Controller {

    public function loginAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('wp_platform_home');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        $user = new User();

        $form = $this->get('form.factory')->create(UserType::class, $user, ["attr"=>["id"=>"test"]]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Message bien enregistré.');

            return $this->redirectToRoute('wp_platform_home');
        }

        return $this->render('WPUserBundle:Security:login.html.twig', array(
                    'last_username' => $authenticationUtils->getLastUsername(),
                    'error' => $authenticationUtils->getLastAuthenticationError(),
                    'form' => $form->createView(),
        ));
    }

}
