<?php


namespace App\Service;


use App\Entity\AdminMessage;
use App\Repository\AdminMessageRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdminMessagesHandler
 * @package App\Service
 */
class AdminMessagesHandler
{
    /**
     * https://getbootstrap.com/ Alerts
     */
    const MASSAGE_TYPE_DANGER = 'danger';

    /**
     * https://getbootstrap.com/ Alerts
     */
    const MASSAGE_TYPE_WARNING = 'warning';

    /**
     * https://getbootstrap.com/ Alerts
     */
    const MASSAGE_TYPE_INFO = 'info';

    /**
     * https://getbootstrap.com/ Alerts
     */
    const MASSAGE_TYPE_SUCCESS = 'success';

    /**
     * @var AdminMessageRepository
     */
    private $repository;
    /**
     * @var AdminMessage
     */
    private $adminEntity;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AdminMessagesHandler constructor.
     * @param AdminMessageRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AdminMessageRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;

        $this->entityManager = $entityManager;
    }

    /**
     * Save the danger message
     * @param string $message
     * @param string $heading
     * @param string $sub_heading
     */
    public function addError($message = '', $heading = '', $sub_heading = '')
    {
        $this->addMessage($message, $heading, $sub_heading, self::MASSAGE_TYPE_DANGER);
    }

    /**
     * Save the warning message
     * @param string $message
     * @param string $heading
     * @param string $sub_heading
     */
    public function addWarning($message = '', $heading = '', $sub_heading = '')
    {
        $this->addMessage($message, $heading, $sub_heading, self::MASSAGE_TYPE_WARNING);
    }

    /**
     * Save the info message
     * @param string $message
     * @param string $heading
     * @param string $sub_heading
     */
    public function addInfo($message = '', $heading = '', $sub_heading = '')
    {
        $this->addMessage($message, $heading, $sub_heading, self::MASSAGE_TYPE_INFO);
    }

    /**
     * Save the success message
     * @param string $message
     * @param string $heading
     * @param string $sub_heading
     */
    public function addSuccess($message = '', $heading = '', $sub_heading = '')
    {
        $this->addMessage($message, $heading, $sub_heading, self::MASSAGE_TYPE_SUCCESS);
    }

    /**
     * Save the message to the admin_message->Repository
     * @param $message
     * @param $heading
     * @param $sub_heading
     * @param $type
     */
    private function addMessage($message, $heading, $sub_heading, $type)
    {
        // entity object
        $adminMessage = new AdminMessage();

        // fill the object
        if (!empty($message)) {
            $adminMessage->setMessage($message);
        }

        if (!empty($heading)) {
            $adminMessage->setHeading($heading);
        }else{
            $adminMessage->setHeading('-- System Nachricht --');
        }

        if (!empty($sub_heading)) {
            $adminMessage->setSubHeading($sub_heading);
        }

        if (!empty($type)) {
            $adminMessage->setType($type);
        }

        // fired to db
        $this->entityManager->persist($adminMessage);
        $this->entityManager->flush();

    }

}