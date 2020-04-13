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

class OpenWeatherApiController extends AbstractController
{

    /**
     * @Route("/open/weather/api", name="open_weather_api")
     * @param OpenWeatherService $weatherService
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function index(OpenWeatherService $weatherService)
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
}
