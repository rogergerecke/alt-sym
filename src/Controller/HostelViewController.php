<?php

namespace App\Controller;

use App\Form\SearchHostelType;
use App\Repository\HostelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return RedirectResponse|Response
     */
    public function listing(HostelRepository $hostelRepository, Request $request)
    {

        $hostels = null;
        // creat a new hostel search form
        $form = $this->createForm(SearchHostelType::class);

        // we have a search query
        if ($request->isMethod('POST') and $request->request->get($form->getName())) {

            // todo validate form

                $q = $request->request->get($form->getName());
                print_r($q);
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
