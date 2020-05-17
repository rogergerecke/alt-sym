<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * This Class overwrite the default ELfinderController
 * she catch the @route and build the user folder for the
 * instance end submit:forward to the ElFinderController with
 * the instance data.
 *
 * Class ElfinderController
 * @package App\Controller
 */
class ElfinderController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @Route("/elfinder")
     * @param UserInterface $user
     * @return Response
     */
    public function show(UserInterface $user, Request $request)
    {
        // get the logged in user [ROLE_]
        $role = $this->get('security.token_storage')->getToken()->getUser()->getRoles();

        if (!$role[0]) {
            echo 'Must be logged in for use this service';
        }

        // set the $instance for the setting from packages/fm_elfinder.yaml
        switch ($role[0]) {
            case 'ROLE_ADMIN':
                $instance = 'admin';
                $homeFolder = '';
                break;
            case 'ROLE_USER':
                $instance = 'user';
                $homeFolder = 'user_at_'.$user->getId();
                break;
            default:
                $instance = 'default';
                $homeFolder = 'dumm'; // empty not used
        }

        // $instance and forward
        return $this->forward(
            'FM\ElfinderBundle\Controller\ElFinderController:show',
            array(
                'instance'   => $instance,
                'homeFolder' => $homeFolder,
            )
        );

    }
}
