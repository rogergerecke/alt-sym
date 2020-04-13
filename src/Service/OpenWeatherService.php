<?php


namespace App\Service;


use App\Entity\OpenWeather;
use App\Repository\OpenWeatherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
 * this class only manage the data import from weather api
 * to the database,  she looks at the datetime from the stored
 * data.  Is its to old import new one.
 *
 *  -check its to old import
 *  -valid data its json
 *  -store it and save a admin massage
 *
 * @package App\Service
 */
class OpenWeatherService
{
    /**
     *
     */
    private const API_BASE_URI = 'https://api.openweathermap.org/data/2.5/';

    /**
     * @var string
     */
    private $api_key = '';

    /**
     * Lounge setting string
     * german default default 'de'
     *
     * @var string
     */
    private $lang = 'de';

    /**
     * Measured setting string
     * german default 'metric'
     * @var string
     */
    private $units = 'metric';

    /**
     * Latitude
     *
     * @var string
     */
    private $latitude = '';

    /**
     * Longitude
     *
     * @var string
     */
    private $longitude = '';

    /**
     * The full request url with
     * parameter
     *
     * @var string
     */
    private $request_url = '';

    /**
     * API
     * openweathermap.org/api
     * Type Name default 'onecall'
     *
     * @var string
     */
    private $type = 'onecall';

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
    private $limit = 1800;

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
     */
    public function __construct(
        OpenWeatherRepository $repository,
        AdminMessagesHandler $adminMessagesHandler,
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->adminMessagesHandler = $adminMessagesHandler;
        $this->validator = $validator;
        $this->em = $em;

        $this->now = time();
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     * @return OpenWeatherService
     */
    public function setApiKey(string $api_key): OpenWeatherService
    {
        $this->api_key = $api_key;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     * @return OpenWeatherService
     */
    public function setLang(string $lang): OpenWeatherService
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnits(): string
    {
        return $this->units;
    }

    /**
     * @param string $units
     * @return OpenWeatherService
     */
    public function setUnits(string $units): OpenWeatherService
    {
        $this->units = $units;

        return $this;
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     * @return OpenWeatherService
     */
    public function setLatitude(string $latitude): OpenWeatherService
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     * @return OpenWeatherService
     */
    public function setLongitude(string $longitude): OpenWeatherService
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        return $this->request_url;
    }

    /**
     * @param string $request_url
     * @return OpenWeatherService
     */
    public function setRequestUrl(string $request_url): OpenWeatherService
    {
        $this->request_url = $request_url;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return OpenWeatherService
     */
    public function setType(string $type): OpenWeatherService
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return OpenWeatherService
     */
    public function setLimit(int $limit): OpenWeatherService
    {
        $this->limit = $limit;

        return $this;
    }


    /**
     * @return bool
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function _runUpdate(): bool
    {

        if (!$this->isWeatherUpToDate()) {

            // set latitude longitude
            $this->setLatitudeAndLongitude();
            $this->setApiKey($_ENV['OPENWEATHER_API_KEY']);
            $this->setType($_ENV['OPENWEATHER_API_TYPE']);

            // build from vars above
            $api_url = $this->_buildApiUrl();
            $this->setRequestUrl($api_url);

            // download with curl and store to db
            $this->downloadWeather();

            return true;
        }

        return false;
    }


    /**
     * Is in database new weather
     *
     * @return bool
     */
    private function isWeatherUpToDate(): bool
    {
        // load the old entry by type filter
        $result = $this->repository->findOneBy(array('data_type' => $this->type), array('id' => 'DESC'));

        // nothing
        if (!$result) {
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
     * setLatitudeAndLongitude from .env
     *
     * @throws Exception
     */
    private function setLatitudeAndLongitude(): void
    {

        if (!isset($_ENV['OPENWEATHER_LAT_LON'])) {
            throw new Exception('OPENWEATHER_LAT_LON must set in .env');
        }

        // set latitude and longitude
        $coordinates = str_replace('@', '', $_ENV['OPENWEATHER_LAT_LON']);
        $coordinates = explode(',', $coordinates, 2);

        if (empty($coordinates[0]) or empty($coordinates[1])) {
            throw new Exception(
                'OPENWEATHER_LAT_LON format not valid in .env set @lat,lon comma separate example: @49.1161974,10.6974247'
            );
        }


        $this->setLatitude($coordinates[0]);
        $this->setLongitude($coordinates[1]);
    }

    /**
     * Build the request api url whit parameter
     * from setter.
     *
     * @return string
     */
    private function _buildApiUrl()
    {

        $parameter = [
            'lat'   => $this->latitude,
            'lon'   => $this->longitude,
            'lang'  => $this->lang,
            'units' => $this->units,
            'appid' => $this->api_key,

        ];

        return self::API_BASE_URI.$this->type.'?'.http_build_query($parameter);
    }

    /**
     * @return bool|Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function downloadWeather()
    {

        $client = new CurlHttpClient();
        $response = null;

        // fired request with no redirects
        try {
            $response = $client->request('GET', $this->request_url, ['max_redirects' => 0,]);
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

            // if old entry update or create the first one insert
            $openWeather = $this->repository->findOneBy(array('data_type' => $this->type), array('id' => 'DESC'));

            // null entry creat the entity object
            if ($openWeather === null) {
                $openWeather = new OpenWeather();
            }

            // json string
            $response->getContent();

            // data object
            $openWeather->setWeatherData($response->toArray());
            $openWeather->setDataType($this->type);
            $openWeather->setImportStatusCode(200);

            // validation of input with entity Asserts
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