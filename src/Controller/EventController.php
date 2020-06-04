<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * The index of the event page return all listet
     * events
     *
     * @Route("/veranstaltungen", name="event")
     * @param EventsRepository $eventsRepository
     * @return Response
     */
    public function index(EventsRepository $eventsRepository)
    {

        $events = $eventsRepository->getAllActiveEvent();

        if(!$events){
            $this->addFlash('info','Zurzeit keine Events vorhanden.');
        }


        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * The detail page of the listet event with there owen
     * template
     *
     * @Route("/veranstaltungen/{id}", name="event_details", requirements={"id"="\d+"})
     * @param $id
     * @param EventsRepository $eventsRepository
     * @return Response
     */
    public function detail($id, EventsRepository $eventsRepository)
    {

        $event = $eventsRepository->find((int)$id);

        // entry not more exist RedirectResponse to index
        if (!$event) {
            new RedirectResponse($this->generateUrl('index'));
        }

        if (!$event){
            $this->addFlash('info','Keine Event Details vorhanden.');
        }

        return $this->render('event/event_details.html.twig', [
            'content' => $event,
        ]);
    }
}
