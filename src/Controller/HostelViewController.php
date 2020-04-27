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
    public function index(HostelRepository $hostelRepository, Request $request)
    {

        // creat a new hostel search form
        $form = $this->createForm(SearchHostelType::class);

        if ($request->isMethod('POST')) {

            /* $form->submit($request->request->get($form->getName()));*/

            if ($form->isSubmitted() and $form->isValid()) {
                // send user to /gastgeber
                return $this->redirectToRoute('hostel_view');
            } else {
                $this->addFlash('danger', 'Diese Anfrage war nicht valide bitte tun Sie das nicht.');
            }
        }


// todo pagination
        $hostels = null;
        // get the request
        if ($request->request->get($form->getName())) {
            $q = $request->query->get('search_hostel');
            $hostels = $hostelRepository->findHostelsWithFilter($q);

            if ($hostels !== null) {
                // do output
                /*print_r($hostels);*/
            } else {
                $this->addFlash('info', 'Leider ergab ihre Suche keine ergebnisse');
            }
        }

        // no request default show all
        if (null === $request->request->get('search_hostel')) {
            $hostels = $hostelRepository->findBy(['status' => true]);
        }


        return $this->render(
            'hostel_view/index.html.twig',
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
                'go_maps_api_key' => $_ENV['GOOGLE_MAPS_API_KEY'],
            ]
        );
    }
}
