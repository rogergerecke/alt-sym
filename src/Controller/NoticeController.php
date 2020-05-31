<?php

namespace App\Controller;

use App\Entity\Hostel;
use App\Repository\HostelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class NoticeController extends AbstractController
{
    const NOTICE_SESSION_KEY = 'notice';

    // TODO add statistic counter for id

    /**
     * Index for showing the notice hostel
     *
     * @Route("/notice", name="notice")
     * @param HostelRepository $hostelRepository
     * @param SessionInterface $session
     * @return Response
     */
    public function index(HostelRepository $hostelRepository, SessionInterface $session)
    {
        $hostels = false;

        if ($session->has(self::NOTICE_SESSION_KEY)) {

            $hostels = $hostelRepository->findAllHostelWithId($session->get(self::NOTICE_SESSION_KEY));

        }

        return $this->render(
            'notice/index.html.twig',
            [
                'hostels' => $hostels,
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

        // if id not set add the new id to session
        if (!in_array($id, $session->get(self::NOTICE_SESSION_KEY))) {
            $array = $session->get(self::NOTICE_SESSION_KEY);
            $array[] = $id;

            //set
            $session->set(self::NOTICE_SESSION_KEY, $array);

            // write to the hostel statistik /performance killer customer want it
            /**/
        }


        // response for the ajax handle the new a:href
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

        // response for the ajax handle the new a:href
        return new Response('/notice/add/'.$id);
    }
}
