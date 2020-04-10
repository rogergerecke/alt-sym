<?php

namespace App\Controller;

use App\Repository\StaticSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StaticSiteController
 * Handle dynamic routes from database content url: impress.html
 * @package App\Controller
 */
class StaticSiteController extends AbstractController
{
    /**
     * @var StaticSiteRepository
     */
    private $repository;

    /**
     * StaticSiteController constructor.
     * @param StaticSiteRepository $repository
     */
    public function __construct(StaticSiteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/impressum", name="static_site_impressum")
     */
    public function impressum()
    {
        $site = $this->repository->findOneBy(['name'=>'Impressum']);
        return $this->render('static_site/index.html.twig', [
            'heading'=>$site->getHeading(),
            'meta_title'=>$site->getMetaTitle(),
            'meta_description'=>$site->getMetaDescription(),
            'content' => $site->getContent(),
        ]);
    }

    /**
     * @Route("/datenschutz", name="static_site_privacy")
     */
    public function privacy()
    {
        $site = $this->repository->findOneBy(['name'=>'Privacy']);

        return $this->render('static_site/index.html.twig', [
            'heading'=>$site->getHeading(),
            'meta_title'=>$site->getMetaTitle(),
            'meta_description'=>$site->getMetaDescription(),
            'content' => $site->getContent(),
        ]);
    }

    /**
     * @Route("/kontakt", name="static_site_contact")
     */
    public function contact()
    {
        $site = $this->repository->findOneBy(['name'=>'Contact']);

        return $this->render('static_site/index.html.twig', [
            'heading'=>$site->getHeading(),
            'meta_title'=>$site->getMetaTitle(),
            'meta_description'=>$site->getMetaDescription(),
            'content' => $site->getContent(),
        ]);
    }
}
