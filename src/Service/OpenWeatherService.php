<?php


namespace App\Service;


use App\Repository\OpenWeatherRepository;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OpenWeatherService
{
    /**
     * @var OpenWeatherRepository
     */
    private $repository;

    private $isWeatherUpToDate = false;

    private $now;
    /**
     * @var AdminMessagesHandler
     */
    private $adminMessagesHandler;

    public function __construct(OpenWeatherRepository $repository, AdminMessagesHandler $adminMessagesHandler)
    {
        $this->repository = $repository;

        $this->now = time();
        $this->adminMessagesHandler = $adminMessagesHandler;
    }

    /**
     * @return bool
     */
    public function isWeatherUpToDate(): bool
    {
        return $this->isWeatherUpToDate;
    }

    /**
     * @param bool $isWeatherUpToDate
     */
    public function setIsWeatherUpToDate(bool $isWeatherUpToDate): void
    {
        $this->isWeatherUpToDate = $isWeatherUpToDate;
    }

    public function downloadWeather()
    {
        $this->requestWeatherApi($_ENV['OPENWEATHER_API_CALL_WEATHER']);
    }

    public function downloadForecast()
    {
        $this->requestWeatherApi($_ENV['OPENWEATHER_API_CALL_FORECAST']);
    }



    private function requestWeatherApi($url)
    {/**/
        $client = new CurlHttpClient();
        $response = null;
        // fired request with no redirects
        try {
            $response = $client->request('GET', $url, ['max_redirects' => 0,]);
        } catch (TransportExceptionInterface $e) {
            $this->adminMessagesHandler->addError(
                "CurlHttpClient Request Exception: {$e->getMessage()}",
                "CurlHttpClient Request Exception"
            );
        }


        // the status code of the request to verified code
        $statusCode = null;
        try {
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            $this->adminMessagesHandler->addError(
                "CurlHttpClient Response Exception: {$e->getMessage()}",
                "CurlHttpClient Response Exception"
            );
        }


        // status code not ok
        if (!$statusCode == 200) {
            $this->adminMessagesHandler->addError(
                "Der Import der Wetter-Daten hat nicht funktioniert der Server von dem die Daten heruntergeladen werden sollten Anwortet mit CODE: {$statusCode}, Hilfe auf https://openweathermap.org/api",
                "Fehler beim Import der Wetter Daten",
                "Server Antwort CODE: {$statusCode}"
            );
        }

        if ($statusCode == 200) {
            // its json
            // its valid
            // wee get a json string with weather data
            $contents = $response->getContent();
        }

        return true;
    }


}