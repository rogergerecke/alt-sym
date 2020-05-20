<?php

namespace App\Controller;

use App\Entity\Hostel;
use App\Form\SearchHostelType;
use App\Repository\AdvertisingRepository;
use App\Repository\HostelRepository;
use App\Repository\StaticSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param StaticSiteRepository $repository
     * @param HostelRepository $hostelRepository
     * @param AdvertisingRepository $advertisingRepository
     * @return Response
     */
    public function index(
        StaticSiteRepository $repository,
        HostelRepository $hostelRepository,
        AdvertisingRepository $advertisingRepository
    ) {

        // index content for start page
        $content = $repository->findOneBy(['route' => 'Index']);

        // load the self marketing for entry
        $self_marketing = $repository->findOneBy(['route' => 'Entry']);

        // creat a new hostel search form
        $form = $this->createForm(SearchHostelType::class);
        // if the form submitted redirect to hostel view
        if ($form->isSubmitted() and $form->isValid()) {
            // todo add real $form-< valid entity
            // send user to /gastgeber
            return $this->redirectToRoute('hostel_view');
        }

        // get advertising for start page
        $advertisings = $advertisingRepository->findBy(['status' => true]);


        return $this->render(
            'index/index.html.twig',
            [
                'title'              => $content->getMetaTitle(),
                'description'        => $content->getMetaDescription(),
                'heading'            => $content->getHeading(),
                'content'            => $content->getContent(),
                'form'               => $form->createView(),
                'start_page_hostels' => $hostelRepository->findStartPageHostels(),
                'self_marketing'     => $self_marketing->getContent(),
                'advertisings'        => $advertisings,
            ]
        );
    }
}
