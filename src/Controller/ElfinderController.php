<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\MediaUserInstance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ElfinderController extends AbstractController
{

    /**
     * @Route("/elfinder")
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     */
    public function show(UserInterface $user)
    {
        // set the $instance for the setting from packages/fm_elfinder.yaml
        $role = $this->get('security.token_storage')->getToken()->getUser()->getRoles();

        if (!$role[0]) {
            echo 'Must be logged in for use this service';
        }

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
                $homeFolder = 'dumm';
        }


        return $this->forward(
            'FM\ElfinderBundle\Controller\ElFinderController:show',
            array(
                'instance'   => $instance,
                'homeFolder' => $homeFolder,
            )
        );

    }
}
