<?php


namespace App\Service;


use App\Entity\OpenWeather;
use App\Repository\OpenWeatherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class OpenWeatherService
 * @package App\Service
 */
class OpenWeatherService
{
    /**
     * @var OpenWeatherRepository
     */
    private $repository;

    /**
     * Store unix now time stamp
     * @var int
     */
    private $now;

    /**
     * Free openweathermap account have request limit
     * so wee cache the weather over the database for 1 hour.
     * @var int
     */
    private $limit = 3600;

    /**
     * API openweathermap.org/api Type Name
     * @var string
     */
    private $type = 'onecall'; // onecall api most recent data

    /**
     * Store the weather data
     * @var
     */
    private $data;

    /**
     * @var AdminMessagesHandler
     */
    private $adminMessagesHandler;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * OpenWeatherService constructor.
     * @param OpenWeatherRepository $repository
     * @param AdminMessagesHandler $adminMessagesHandler
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param string $type
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __construct(
        OpenWeatherRepository $repository,
        AdminMessagesHandler $adminMessagesHandler,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        $type = ''
    ) {
        $this->repository = $repository;

        $this->now = time();
        $this->adminMessagesHandler = $adminMessagesHandler;

        $this->validator = $validator;
        $this->em = $em;

        // if type set
        if (!empty($type)) {
            $this->type = $type;
        }

        // load new?
        if (!$this->isWeatherUpToDate($type)) {
            $this->downloadNewWeatherData($type);
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }


    /**
     * Check is the dataset is up to date
     * @param $type
     * @return bool
     */
    private function isWeatherUpToDate($type): bool
    {
        // load time form db by type
        $result = $this->repository->findOneBy(array('data_type' => $type));

        // nothing
        if (!$result->getId()) {
            return false;
        }


        // calculate diff
        $diff = $this->now - $result->getDate()->format('U');

        if ($diff > $this->limit) {
            return false;
        }

        return true;
    }

    /**
     * @param $type
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function downloadNewWeatherData($type)
    {
        switch ($type) {
            case 'weather' :
                $this->downloadWeather();
                break;
            case 'forecast' :
                $this->downloadForecast();
                break;
            case 'onecall' :
                $this->downloadOneCall();
                break;
            default :
                $this->downloadOneCall();
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function downloadWeather()
    {
        $this->type = 'weather';
        $this->requestWeatherApi($_ENV['OPENWEATHER_API_CALL_WEATHER']);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function downloadForecast()
    {
        $this->type = 'forecast';
        $this->requestWeatherApi($_ENV['OPENWEATHER_API_CALL_FORECAST']);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function downloadOneCall()
    {
        $this->type = 'onecall';
        $this->requestWeatherApi($_ENV['OPENWEATHER_API_ONE_CALL']);
    }


    /**
     * @param $url
     * @return bool|Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    private function requestWeatherApi($url)
    {
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

            // json string
            $response->getContent();

            // validation of input
            $openWeather = new OpenWeather();
            $openWeather->setWeatherData($response->toArray());
            $openWeather->setDataType($this->type);
            $openWeather->setImportStatusCode(200);


            $errors = $this->validator->validate($openWeather);

            if (count($errors) > 0) {
                $errorsString = (string)$errors;
                $this->adminMessagesHandler->addError(
                    "Error String vom Validator $errorsString",
                    "Json Validation Error beim Import der Wetterdaten"
                );

                $openWeather = null;

                return false;
            }

            // fired to db
            $this->em->persist($openWeather);
            $this->em->flush();
        }

        return true;
    }


}