<?php


namespace App\Service;

use App\Entity\SystemOptions;
use App\Repository\SystemOptionsRepository;

final class SystemOptionsService
{
    /**
     * @var SystemOptionsRepository
     */
    private $systemOptionsRepository;

    /**
     * @var SystemOptions[]
     */
    private $options;


    public function __construct(SystemOptionsRepository $systemOptionsRepository)
    {

        // get all options from the entity and build option array
        $this->systemOptionsRepository = $systemOptionsRepository;
        foreach ($this->systemOptionsRepository->findAll() as $systemOptions) {
            $this->options[$systemOptions->getVarName()] = $systemOptions->getValue();
        }
    }

    public function getByKey($key)
    {
        return $this->options[$key];
    }

    public function getSupportEmailAddress()
    {
        return $this->getByKey('SUPPORT_EMAIL_ADDRESS');
    }

    public function getCopiedReviverEmailAddress()
    {
        return $this->getByKey('CC_EMAIL');
    }

    public function getWebSiteName()
    {
        return $this->getByKey('WEB_SITE_NAME');
    }

    public function getSupportPhoneNumber()
    {
        return $this->getByKey('SUPPORT_PHONE_NUMBER');
    }
// todo use it in .base.twig
    public function getStartPageBackground()
    {
        return $this->getByKey('BACKGROUND_STARTPAGE');
    }

    public function getOutgoingPortalLink()
    {
        return $this->getByKey('OUTGOING_PORTAL_LINK');
    }
}
