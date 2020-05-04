<?php

namespace App\Controller;

use App\Repository\StaticSiteRepository;
use Doctrine\Common\Annotations\Annotation\Required;
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
     * Required information page about imprint
     *
     * @Route("/impressum", name="static_site_imprint")
     */
    public function imprint()
    {
        $site = $this->repository->findOneBy(['name' => 'Imprint']);

        return $this->render(
            'static_site/index.html.twig',
            [
                'heading'          => $site->getHeading(),
                'meta_title'       => $site->getMetaTitle(),
                'meta_description' => $site->getMetaDescription(),
                'content'          => $site->getContent(),
            ]
        );
    }

    /**
     * Required information page about privacy
     *
     * @Route("/datenschutz", name="static_site_privacy")
     */
    public function privacy()
    {
        $site = $this->repository->findOneBy(['name' => 'Privacy']);

        return $this->render(
            'static_site/index.html.twig',
            [
                'heading'          => $site->getHeading(),
                'meta_title'       => $site->getMetaTitle(),
                'meta_description' => $site->getMetaDescription(),
                'content'          => $site->getContent(),
            ]
        );
    }

    /**
     * The contact form page with mail formular
     * // todo creat the mail formular with google captia
     *
     * @Route("/kontakt", name="static_site_contact")
     */
    public function contact()
    {
        $site = $this->repository->findOneBy(['name' => 'Contact']);

        return $this->render(
            'static_site/index.html.twig',
            [
                'heading'          => $site->getHeading(),
                'meta_title'       => $site->getMetaTitle(),
                'meta_description' => $site->getMetaDescription(),
                'content'          => $site->getContent(),
            ]
        );
    }

    /**
     * The region description page with google maps card
     * and geolocation of all hostels // todo add geolocation's
     * @Route("/region", name="static_site_region")
     */
    public function region()
    {
        $site = $this->repository->findOneBy(['name' => 'Region']);

        return $this->render(
            'static_site/index.html.twig',
            [
                'heading'          => $site->getHeading(),
                'meta_title'       => $site->getMetaTitle(),
                'meta_description' => $site->getMetaDescription(),
                'content'          => $site->getContent(),
            ]
        );
    }

    /**
     * The leisure entry only with payment
     *
     * @Route("/freizeit", name="static_site_leisure")
     */
    public function leisure()
    {
        $site = $this->repository->findOneBy(['name' => 'Leisure']);

        return $this->render(
            'static_site/index.html.twig',
            [
                'heading'          => $site->getHeading(),
                'meta_title'       => $site->getMetaTitle(),
                'meta_description' => $site->getMetaDescription(),
                'content'          => $site->getContent(),
            ]
        );
    }

    /**
     * The entry marketing page
     *
     * @Route("/eintrag", name="static_site_entry")
     */
    public function entry()
    {
        $site = $this->repository->findOneBy(['name' => 'Entry']);

        return $this->render(
            'static_site/index.html.twig',
            [
                'heading'          => false,
                'meta_title'       => $site->getMetaTitle(),
                'meta_description' => $site->getMetaDescription(),
                'content'          => $site->getContent(),
            ]
        );
    }

    // todo add handler for dynamic url and pages
}
