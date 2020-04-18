<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    /**
     * @Route("/member", name="member")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        return $this->render(
            'member/index.html.twig',
            [
                'controller_name' => 'MemberController',
            ]
        );
    }

    /**
     * @Route("/member/login", name="login")
     */
    public function login()
    {
        // redirect by user role
        $user = $this->getUser();

        // not singe in
        if (!$user){
            return $this->redirectToRoute('app_login');
        }

        // to admin dash bord
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }

        // to member dash bord
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('member');
        }

        return $this->render(
            'member/index.html.twig',
            [
                'controller_name' => 'MemberController',
            ]
        );
    }
}
