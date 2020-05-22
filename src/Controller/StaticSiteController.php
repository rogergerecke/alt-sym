<?php

namespace App\Controller;

use App\Repository\StaticSiteRepository;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * StaticSiteController constructor.
     * @param StaticSiteRepository $repository
     * @param TranslatorInterface $translator
     */
    public function __construct(StaticSiteRepository $repository,TranslatorInterface $translator)
    {
        $this->repository = $repository;
        $this->translator = $translator;
    }

    /**
     * Required information page about imprint
     *
     * @Route("/impressum", name="static_site_imprint")
     */
    public function imprint()
    {
        $site = $this->repository->findOneBy(['route' => 'Imprint', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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
        $site = $this->repository->findOneBy(['route' => 'Privacy', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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
        $site = $this->repository->findOneBy(['route' => 'Contact', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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
        $site = $this->repository->findOneBy(['route' => 'Region', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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
     * @Route("/freizeitangebote", name="static_site_leisure")
     */
    public function leisure()
    {
        $site = $this->repository->findOneBy(['route' => 'Leisure', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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
        $site = $this->repository->findOneBy(['route' => 'Entry', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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

    /**
     * The Marketing Page for free event entry
     *
     * @Route("/event-kostenlos-eintragen", name="static_site_event_entry")
     */
    public function event_entry()
    {
        $site = $this->repository->findOneBy(['route' => 'Event_Entry', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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


    /**
     * The cookies disclaimer
     *
     * @Route("/cookies", name="static_site_cookies")
     */
    public function cookies()
    {
        $site = $this->repository->findOneBy(['route' => 'Cookies', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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

    /**
     * The handler of dynamic content sites
     * @param $site
     * @return Response
     * @Route("/content/{site}", requirements={"site"="\w+"})
     */
    public function content(string $site)
    {
        $site = $this->repository->findOneBy(['url' => $site, 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

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
}
