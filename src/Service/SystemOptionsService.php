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


    /**
     * Return the value string for the given key
     * @param $key
     * @return SystemOptions
     */
    public function getByKey($key)
    {
        return $this->options[$key];
    }


    /**
     * Return the Support Mail Address for the Webpage Template Contact
     * @return SystemOptions
     */
    public function getSupportEmailAddress()
    {
        return $this->getByKey('SUPPORT_EMAIL_ADDRESS');
    }


    /**
     * The Absence Address of the Mail Server System
     * add a SFP entry to your mail server TXT for spam protection
     * @return SystemOptions
     */
    public function getMailSystemAbsenceAddress()
    {
        return $this->getByKey('MAIL_SYSTEM_ABSENCE_ADDRESS');
    }

    /**
     * For Developer Mode used mail address
     * @return SystemOptions
     */
    public function getTestEmailAddress()
    {
        return $this->getByKey('TEST_MAIL_ADDRESS');
    }

    /**
     * Address for mail Copy reviver if it required
     * @return SystemOptions
     */
    public function getCopiedReviverEmailAddress()
    {
        return $this->getByKey('CC_EMAIL');
    }

    /**
     * Website name display on top of the webpage
     * @return SystemOptions
     */
    public function getWebSiteName()
    {
        return $this->getByKey('WEB_SITE_NAME');
    }

    /**
     * The support phone number in template
     * @return SystemOptions
     */
    public function getSupportPhoneNumber()
    {
        return $this->getByKey('SUPPORT_PHONE_NUMBER');
    }

    /**
     * Startpage background image behind the search box
     * @return SystemOptions
     */
    public function getStartPageBackground()
    {
        return $this->getByKey('BACKGROUND_STARTPAGE');
    }

    /**
     * Full html a href tag for the outgoing link
     * to the other portal brombachsee in template
     * @return SystemOptions
     */
    public function getOutgoingPortalLink()
    {
        return $this->getByKey('OUTGOING_PORTAL_LINK');
    }
}
