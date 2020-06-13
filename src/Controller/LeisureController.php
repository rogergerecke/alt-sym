<?php

namespace App\Controller;

use App\Repository\LeisureRepository;
use App\Repository\StaticSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeisureController extends AbstractController
{
    /**
     * @Route("/freizeitangebote", name="leisure")
     * @param LeisureRepository $leisureRepository
     * @param StaticSiteRepository $staticSiteRepository
     * @return Response
     */
    public function index(LeisureRepository $leisureRepository, StaticSiteRepository $staticSiteRepository)
    {
        // get all leisure entry's
        $leisures = $leisureRepository->findBy(['status' => true]);

        $site = $staticSiteRepository->findOneBy(['route' => 'Leisure', 'status' => true]);

        if (!$site) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'leisure/index.html.twig',
            [
                'leisures' => $leisures,
                'content'  => $site,
            ]
        );
    }

    /**
     * @Route("/freizeitangebot/{id}", name="leisure_detail", requirements={"id"="\d+"})
     * @param $id
     * @param LeisureRepository $leisureRepository
     * @return Response
     */
    public function detail($id, LeisureRepository $leisureRepository)
    {

        $content = $leisureRepository->find($id);

        // entry not more exist RedirectResponse to index
        if (!$content) {
            new RedirectResponse($this->generateUrl('index'));
        }


        return $this->render(
            'leisure/detail.html.twig',
            [
                'content'          => $content,
            ]
        );
    }
}
