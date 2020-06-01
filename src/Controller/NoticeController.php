<?php

namespace App\Controller;

use App\Entity\Hostel;
use App\Entity\Statistics;
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

            //set id to session
            $session->set(self::NOTICE_SESSION_KEY, $array);

            // write to the hostel statistik /performance killer customer want it
            // build em for statistic counter
            $em = $this->getDoctrine()->getManager();
            $hostel_statistic = $em->getRepository(Statistics::class)->findOneBy(['hostel_id' => $id]);

            // if hostel id in statistics or save new
            if (!$hostel_statistic) {
                // new statistics entry
                $statistic = new Statistics();
                $statistic->setHostelId((int)$id);
                $statistic->setNoticeHostel(1);
                $em->persist($statistic);
                $em->flush();
            } else {
                // update statistics entry
                $count = $hostel_statistic->getNoticeHostel() + 1;
                $hostel_statistic->setNoticeHostel($count);
                $em->persist($hostel_statistic);
                $em->flush();
            }

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
