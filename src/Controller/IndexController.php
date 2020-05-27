<?php

namespace App\Controller;

use App\Entity\Advertising;
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
     * @var StaticSiteRepository
     */
    private $siteRepository;
    /**
     * @var HostelRepository
     */
    private $hostelRepository;
    /**
     * @var AdvertisingRepository
     */
    private $advertisingRepository;

    public function __construct(
        StaticSiteRepository $siteRepository,
        HostelRepository $hostelRepository,
        AdvertisingRepository $advertisingRepository
    ) {
        $this->siteRepository = $siteRepository;
        $this->hostelRepository = $hostelRepository;
        $this->advertisingRepository = $advertisingRepository;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {

        // index content for start page
        $content = $this->siteRepository->findOneBy(['route' => 'Index']);



        // creat a new hostel search form
        $form = $this->createForm(SearchHostelType::class);
        // if the form submitted redirect to hostel view
        if ($form->isSubmitted() and $form->isValid()) {
            // todo add real $form-< valid entity
            // send user to /gastgeber
            return $this->redirectToRoute('hostel_view');
        }


        $ads = null;

        // build advertising for start page bax first place
        if (null !== $advertising = $this->advertisingRepository->getAdvertising()) {

            // extend with address
            foreach ($advertising as $item) {

                $user_hostel = $this->hostelRepository->findOneBy(['user_id' => $item->getUserId()]);
                $ads[] = [
                    'title'           => $item->getTitle(),
                    'text'            => $item->getText(),
                    'link'            => $item->getLink(),
                    'image'           => $item->getImage(),
                    'city'            => $user_hostel->getCity(),
                    'distance_to_see' => $user_hostel->getDistanceToSee(),
                ];
            }
        }

        return $this->render(
            'index/index.html.twig',
            [
                'title'              => $content->getMetaTitle(),
                'description'        => $content->getMetaDescription(),
                'heading'            => $content->getHeading(),
                'content'            => $content->getContent(),
                'form'               => $form->createView(),
                'start_page_hostels' => $this->hostelRepository->findStartPageHostels(),
                'advertisings'       => $ads,
            ]
        );
    }
}
