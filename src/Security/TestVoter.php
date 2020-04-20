<?php


namespace App\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TestVoter extends \Symfony\Component\Security\Core\Authorization\Voter\Voter
{

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject)
    {
       dump($attribute,$subject);
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        // TODO: Implement voteOnAttribute() method.
    }
}