<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticeController extends AbstractController
{
    const NOTICE_SESSION_KEY = 'notice';

    /**
     * Index for showing the notice hostel
     *
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
     * Add a new id value to the session
     *
     * @Route("/notice/add/{id}", name="notice_add", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function add(int $id, Request $request)
    {

        // get session instance
        $session = $request->getSession();

        if (!$session->has(self::NOTICE_SESSION_KEY)) {
            $session->set(self::NOTICE_SESSION_KEY, [$id]);
        }

        // add the new id to
        $array = $session->get(self::NOTICE_SESSION_KEY);
        $array[] = $id;

        $session->set(self::NOTICE_SESSION_KEY, $array);

        return new Response('/notice/remove/'.$id);
    }

    /**
     * Remove a id from session
     *
     * @Route("/notice/remove/{id}", name="notice_remove", requirements={"id"="\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function remove(int $id, Request $request)
    {

        // get session instance
        $session = $request->getSession();
        $notice_ids = [];

        if ($session->has(self::NOTICE_SESSION_KEY)) {

            // search in array and remove id
            foreach ($session->get(self::NOTICE_SESSION_KEY) as $value) {
                if ($value != $id) {
                    $notice_ids[] = $value;
                }
            }

            $session->set(self::NOTICE_SESSION_KEY, $notice_ids);
        }


        return new Response('/notice/add/'.$id);
    }
}
