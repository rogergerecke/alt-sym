<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticeController extends AbstractController
{

    /**
     * @Route("/notice", name="notice")
     */
    public function index()
    {
        return $this->render(
            'notice/index.html.twig',
            [
                'controller_name' => 'NoticeController',
            ]
        );
    }


    # Submitted by ajax request

    /**
     * @Route("/notice/add/{id}", name="notice", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function add(int $id, Request $request)
    {


        $session = $request->getSession();

        if (!$session->has('notice')) {
            $session->set('notice', []);
        }

        $session->set('notice', array_merge($session->get('notice'), $id));
        print_r($session->get('notice'));

        return new Response('test');
    }

    /**
     * @Route("/notice/remove/{id}", name="notice", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function remove(int $id, Request $request)
    {
        $session = $request->getSession();

        if (!$session->has('notice')) {
            $session->set('notice', []);
        }

        $session->set('notice', array_merge($session->get('notice'), $id));

        return new Response('test');
    }
}
