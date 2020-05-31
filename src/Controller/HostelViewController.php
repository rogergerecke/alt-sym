<?php

namespace App\Controller;

use App\Entity\Statistics;
use App\Form\SearchHostelType;
use App\Repository\HostelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function listing(HostelRepository $hostelRepository, Request $request, SessionInterface $session)
    {

        $hostels = null;
        // creat a new hostel search form
        $form = $this->createForm(SearchHostelType::class);

        // we have a search query
        if ($request->isMethod('POST') and $request->request->get($form->getName())) {

            // todo validate form

            $q = $request->request->get($form->getName());
            // TODO form function
            /* $hostels = $hostelRepository->findHostelsWithFilter($q);*/

            if ($hostels !== null) {
                // do output
                /*print_r($hostels);*/
            } else {
                $this->addFlash('info', 'Leider ergab ihre Suche keine ergebnisse');
            }


            //return $this->redirectToRoute('hostel_view');
        }


        // no request default show all
        if (null === $request->request->get('search_hostel')) {
            $hostels = $hostelRepository->findBy(['status' => true]);
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
            $hostel_statistics = $em->getRepository(Statistics::class)->findAll();

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
                'top_hostels'     => $hostelRepository->findTopListingHostels(),
            ]
        );
    }


    /**
     * @Route("/gastgeber/details/{id}", name="hostel_details", requirements={"id"="\d+"})
     * @param int $id
     * @param HostelRepository $hostelRepository
     */
    public function details(int $id, HostelRepository $hostelRepository)
    {

        $hostel = $hostelRepository->find($id);

        if (null === $hostel) {
            $this->addFlash('info', 'Diese Unterkunft hat noch keine Detailseite');
        }else{

            // Statistics detail page_view counter
            // write to the hostel statistik /performance killer customer want it
            // build em for statistic counter
            $em = $this->getDoctrine()->getManager();
            $statistics = $em->getRepository(Statistics::class)->findOneBy(['hostel_id'=>$id]);

            // if hostel id in statistics
            // if hostel id in statistics or save new
            if (!$statistics) {
                // new statistics entry
                $statistic = new Statistics();
                $statistic->setHostelId((int)$id);
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




        return $this->render(
            'hostel_view/hostel_details.html.twig',
            [
                'controller_name' => 'HostelViewController',
                'form'            => '$form->createView()',
                'hostels'         => '$hostels',
                'top_hostels'     => '$hostelRepository->findTopListingHostels()',
                'hostel'          => $hostel,

            ]
        );
    }
}
