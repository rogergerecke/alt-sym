<?php

namespace App\Controller;

use App\Entity\RoomAmenitiesDescription;
use App\Entity\Statistics;
use App\Form\SearchHostelType;
use App\Repository\HostelRepository;
use App\Repository\RoomAmenitiesDescriptionRepository;
use App\Repository\RoomAmenitiesRepository;
use App\Repository\RoomTypesRepository;
use App\Service\CalendarService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/**
 * Class HostelViewController
 * The hostel view generate the filtered view of hostel
 * @package App\Controller
 */
class HostelViewController extends AbstractController
{
    /**
     * @Route("/gastgeber", name="hostel_view")
     * @param HostelRepository $hostelRepository
     * @param Request $request
     * @param SessionInterface $session
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function listing(HostelRepository $hostelRepository, Request $request, SessionInterface $session)
    {

        $hostels = null;
        $top_hostels = null;
        $header_region = null;
        // creat a new hostel search form
        $form = $this->createForm(SearchHostelType::class);

        // we have a search query
        if ($request->isMethod('POST') and $request->request->all($form->getName())) {
            // get the query array
            $q = $request->request->all($form->getName());

            if ($hostels = $hostelRepository->findHostelsWithFilter($q)) {
                $header_region = $hostelRepository->getRegionsName();
            } else {
                $this->addFlash('info', 'Leider ergab ihre Suche keine ergebnisse');
            }


            //return $this->redirectToRoute('hostel_view');
        }


        // no request default show all
        if (!$request->request->all('search_hostel')) {
            $hostels = $hostelRepository->findBy(['status' => true]);
            $top_hostels = $hostelRepository->findTopListingHostels();
        }

        #
        # STATISTICS
        #
        // if the first page view update statistics only one time per session
        if (!$session->has('notice_page_view')) {
            $session->set('notice_page_view', true);

            // Statistics global_page_view counter
            // write to the hostel statistik /performance killer customer want it
            // build em for statistic counter
            $em = $this->getDoctrine()->getManager();
            $month = new \DateTime();
            $month = $month->format('Y-m');
            $hostel_statistics = $em->getRepository(Statistics::class)->findBy(['date' => new \DateTime($month.'-01')]);

            // if hostel id in statistics
            foreach ($hostel_statistics as $hostelStatistic) {
                // update statistics entry
                $count = $hostelStatistic->getGlobalPageView() + 1;
                $hostelStatistic->setGlobalPageView($count);
                $em->persist($hostelStatistic);
                $em->flush();
            }
        }


        return $this->render(
            'hostel_view/hostel_listing.twig',
            [
                'controller_name' => 'HostelViewController',
                'form'            => $form->createView(),
                'hostels'         => $hostels,
                'top_hostels'     => $top_hostels,
                'header_region'   => $header_region,
            ]
        );
    }


    /**
     * Build the vars for the detail site
     * @Route("/gastgeber/details/{id}", name="hostel_details", requirements={"id"="\d+"})
     * @param int $id
     * @param HostelRepository $hostelRepository
     * @param RoomTypesRepository $roomTypesRepository
     * @param RoomAmenitiesRepository $roomAmenitiesRepository
     * @param CalendarService $calendar
     * @return Response
     * @throws Exception
     */
    public function details(
        int $id,
        HostelRepository $hostelRepository,
        RoomTypesRepository $roomTypesRepository,
        RoomAmenitiesRepository $roomAmenitiesRepository,
        CalendarService $calendar
    ) {


        $hostel = $hostelRepository->find($id);
        $services = false;

        // nothing hostel data exist
        if (null === $hostel) {
            $this->addFlash('info', 'Diese Unterkunft hat noch keine Detailseite');
        } else {
            if ($hostel->getStatus() == 0) {
                $this->addFlash('danger', 'Diese Unterkunft ist im Moment deaktiviert.');
                $this->addFlash('danger', "Wenn Sie meinen das sei nicht korrekt kontaktieren Sie den Support. <a href='".$this->generateUrl('static_site_contact')."'>Hilfe</a>");
                return new RedirectResponse($this->generateUrl('hostel_view'));
            }

            // Create the Amenities Description with service names and icons.svg
            if ($amenities = $hostel->getAmenities()) {
                // lang code DE
                $roomAmenities = $roomAmenitiesRepository->getRoomAmenitiesWithDescription();

                foreach ($roomAmenities as $amenity) {
                    if (in_array($amenity['name'], $amenities)) {
                        $services[] = $amenity;
                    }
                }
            }

            #####################################
            #   // Statistics detail page_view counter
            #   // write to the hostel statistik /performance killer customer want it
            #   // build em for statistic counter

            $em = $this->getDoctrine()->getManager();
            $month = new \DateTime();
            $month = $month->format('Y-m');
            $statistics = $em->getRepository(Statistics::class)->findOneBy(
                ['hostel_id' => $id, 'date' => new \DateTime($month.'-01')]
            );

            // if hostel id in statistics
            // if hostel id in statistics or save new
            if (!$statistics) {
                // new statistics entry
                $statistic = new Statistics();
                $statistic->setHostelId((int)$id);
                $statistic->setDate(new \DateTime($month.'-01'));
                $statistic->setPageView(1);
                $em->persist($statistic);
                $em->flush();
            } else {
                // update statistics entry
                $count = $statistics->getPageView() + 1;
                $statistics->setPageView($count);
                $em->persist($statistics);
                $em->flush();
            }
        }

        // get rooms data if in database
        $rooms = null;
        $rooms = $roomTypesRepository->findBy(['hostel_id' => $id], ['final_rate' => 'ASC']);


        return $this->render(
            'hostel_view/hostel_details.html.twig',
            [
                'hostel'   => $hostel,
                'services' => $services,
                'rooms'    => $rooms,
                'calendar' => $calendar->getCalendar(),

            ]
        );
    }
}
