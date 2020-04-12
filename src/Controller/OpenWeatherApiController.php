<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OpenWeatherApiController extends AbstractController
{
    /**
     * @Route("/open/weather/api", name="open_weather_api")
     */
    public function index()
    {
        return $this->render('open_weather_api/index.html.twig', [
            'controller_name' => 'OpenWeatherApiController',
        ]);
    }
}
