<?php

namespace App\Controller;

use App\Service\CKFinderService;
use CKSource\Bundle\CKFinderBundle\Form\Type\CKFinderFileChooserType;
use CKSource\CKFinder\CKFinder;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{


    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        /* text remove leater to actionContent */
        $form = $this->createFormBuilder()
            ->add('file_chooser1', CKFinderFileChooserType::class, [
                'label' => 'Photo',
                'button_text' => 'Browse photos',
                'button_attr' => [
                    'class' => 'my-fancy-class'
                ]
            ])
            ->getForm();

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
