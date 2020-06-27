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

    public function getSupportEmailAddress()
    {
        return $this->options['SUPPORT_EMAIL_ADDRESS'];
    }

    public function getCopiedReviverEmailAddress()
    {
        return $this->options['CC_EMAIL'];
    }

}