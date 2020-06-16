<?php

namespace App\Controller;

use App\Service\OpenWeatherService;
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
     * @Route("/cron/job/create-first-statistic-entry", name="cron_job_create_first_statistic_entry")
     */
    public function create()
    {
        return $this->render(
            'cron_job/index.html.twig',
            [
                'controller_name' => 'CronJobController',
            ]
        );
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
            'phat'=>'cron_job_create_first_statistic_entry',
        ];

        $jobs[] = [
            'description' => 'Hält das Wetter auf dem neusten Stand',
            'phat'=>'cron_job_update_the_weather',
        ];

        return $this->render(
            'cron_job/index.html.twig',
            [
                'jobs' => $jobs,
            ]
        );
    }
}
