<?php

namespace App\Controller;

use App\Entity\Hostel;
use App\Form\SearchHostelType;
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
     * @return Response
     */
    public function index(StaticSiteRepository $repository, HostelRepository $hostelRepository)
    {
        // load content from database for the start page seo text
        $content = $repository->findOneBy(['name' => 'Index']);

        // load the self marketing for entry
        $self_marketing = $repository->findOneBy(['name' => 'Entry']);

        // creat a new hostel search form
        $form = $this->createForm(SearchHostelType::class);
        // if the form submitted redirect to hostel view
        if ($form->isSubmitted() and $form->isValid()) {
            // todo add real $form-< valid entity
            // send user to /gastgeber
            return $this->redirectToRoute('hostel_view');
        }


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
            ]
        );
    }
}
