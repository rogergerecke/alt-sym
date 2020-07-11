<?php

namespace App\Controller;

use App\Entity\Hostel;
use App\Entity\Statistics;
use App\Repository\HostelRepository;
use App\Repository\StatisticsRepository;
use App\Service\OpenWeatherService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class CronJobController run system cron job
 * to manage system info and updates
 *
 * @package App\Controller
 */
class CronJobController extends AbstractController
{
    // todo add $password
    /**
     * Creat the first entry for every hostel and date
     * its better to do it with a cron job before we have
     * a response time for a user request over 5 seconds
     * @Route("/cron/job/create-first-statistic-entry", name="cron_job_create_first_statistic_entry")
     * @param StatisticsRepository $statistics
     * @param HostelRepository $hostel
     * @return Response
     * @throws Exception
     */
    public function create(StatisticsRepository $statistics, HostelRepository $hostel)
    {
        // create a first blank entry for a
        // new hostel and date to save a little
        // bit performance for the system
        if ($hostels = $hostel->findAll()) {
            foreach ($hostels as $hostel) {
                // if exist a entry for the hostel with date
                $id = $hostel->getId();
                $em = $this->getDoctrine()->getManager();
                $month = new DateTime();
                $month = $month->format('Y-m-01');
                $hostel_statistics = $statistics->findBy(['date' => new DateTime($month), 'hostel_id' => $id]);

                // no create new entry
                if (!$hostel_statistics) {
                    $statistic = new Statistics();
                    $statistic->setHostelId($id);
                    $statistic->setDate(new DateTime($month));
                    $em->persist($statistic);
                    $em->flush();
                }
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/cron/job/update-the-weather", name="cron_job_update_the_weather")
     * @param OpenWeatherService $weatherService
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function update(OpenWeatherService $weatherService)
    {
        $status = ($weatherService->_runUpdate(
            ) == true) ? 'Weather Update done' : 'Weather data was new noting to do.';

        return $this->render(
            'open_weather_api/index.html.twig',
            [
                'status' => $status,
            ]
        );
    }


    /**
     * Return a array with the description of the cron jops
     *
     * @Route("/cron/job/show", name="cron_job_show")
     *
     * @return Response
     */
    public function show()
    {
        $jobs[] = [
            'description' => 'Erzeugt den ersten eintrag für eine Neue Unterkunft in der Statistik-Tabelle',
            'phat'        => 'cron_job_create_first_statistic_entry',
        ];

        $jobs[] = [
            'description' => 'Hält das Wetter auf dem neusten Stand',
            'phat'        => 'cron_job_update_the_weather',
        ];

        return $this->render(
            'cron_job/index.html.twig',
            [
                'jobs' => $jobs,
            ]
        );
    }
}
