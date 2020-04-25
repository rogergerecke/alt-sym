<?php

namespace App\Controller;

use App\Entity\Hostel;
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
     * @return RedirectResponse|Response
     */
    public function index(HostelRepository $hostelRepository,Request $request)
    {

        // creat a new hostel search form
        $hostel = new Hostel();

        $form = $this->createForm(SearchHostelType::class);

        if ($form->isSubmitted() and $form->isValid()){
            // send user to /gastgeber
            return $this->redirectToRoute('hostel_view');
        }

        $hostels = null;
        // get the request
        if($request->get('search_hostel')){
            $q = $request->query->get('search_hostel');
            $hostels = $hostelRepository->findHostelsWithFilter($q);

           /* if($hostels !== null){
               print_r($hostels);
            }*/
        }



        return $this->render('hostel_view/index.html.twig', [
            'controller_name' => 'HostelViewController',
            'form' => $form->createView(),
            'hostels'=>$hostels
        ]);
    }
}
