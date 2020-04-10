<?php

namespace App\Controller;

use App\Entity\Hostel;
use App\Form\SearchHostelType;
use App\Repository\StaticSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param StaticSiteRepository $repository
     * @return Response
     */
    public function index(StaticSiteRepository $repository)
    {
        // load content from database for the start page
        $content = $repository->findOneBy(array('name' => 'Index'));

        // creat a new hostel search form
        $hostel = new Hostel();

        $form = $this->createForm(SearchHostelType::class);

        if ($form->isSubmitted() and $form->isValid()){
            // send user to /gastgeber
            return $this->redirectToRoute('hostel_view');
        }


        return $this->render('index/index.html.twig', [
            'title' => $content->getMetaTitle(),
            'description' => $content->getMetaDescription(),
            'heading' => $content->getHeading(),
            'content' => $content->getContent(),
            'form' => $form->createView()
        ]);
    }
}
