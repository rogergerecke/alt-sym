<?php

namespace App\Controller;

use App\Entity\Hostel;
use App\Form\SearchHostelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function index()
    {
        // creat a new hostel search form
        $hostel = new Hostel();

        $form = $this->createForm(SearchHostelType::class);

        if ($form->isSubmitted() and $form->isValid()){
            // send user to /gastgeber
            return $this->redirectToRoute('hostel_view');
        }

        return $this->render('hostel_view/index.html.twig', [
            'controller_name' => 'HostelViewController',
            'form' => $form->createView()
        ]);
    }
}
