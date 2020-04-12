<?php

namespace App\Controller;

use App\Service\OpenWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OpenWeatherApiController extends AbstractController
{
    /**
     * @Route("/open/weather/api", name="open_weather_api")
     * @param OpenWeatherService $weatherService
     * @return Response
     */
    public function index(OpenWeatherService $weatherService)
    {

        return $this->render('open_weather_api/index.html.twig', [
            'status' => 'ok',
        ]);
    }
}
