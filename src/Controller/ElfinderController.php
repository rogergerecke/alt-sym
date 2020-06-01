<?php

namespace App\Controller;

use Exception;
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
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Select the config instance by the logged in user
     * ROLE and create the home folder for the user
     *
     * @Route("/elfinder")
     * @param UserInterface $user
     * @return Response
     * @throws Exception
     */
    public function show(UserInterface $user, Request $request)
    {
        $user = $this->security->getUser();

        if (null == $user) {
            exit('Not Logged in');
        }

        // if get the logged in user [ROLE_]
        $role = $user->getRoles();


        // load instance from config fm_elfinder.yaml
        switch ($role[0]) {
            case 'ROLE_ADMIN':
                $instance = 'admin';
                $homeFolder = '';// show the admin all dirs
                break;
            case 'ROLE_USER':
                $instance = 'user';
                $homeFolder = 'user_at_'.$user->getId();
                break;
            default:
                throw new Exception('ROLE not exist in config'.$role[0]);
        }

        // forward with instance and home folder to the real controller
        return $this->forward(
            'FM\ElfinderBundle\Controller\ElFinderController::show',
            array(
                'instance'   => $instance,
                'homeFolder' => $homeFolder,
            )
        );

    }

    /**
     * The route to control direct include in form by the Crud Controller
     * by use of the ->setFormType(ElFinderType::class) in Crud Controller.
     *
     * Catch the $instance parameter from uri to load the defined instance
     * from the config file fm_elfinder.yaml
     *
     * @Route("/elfinder/{instance}")
     * @param $instance
     * @param UserInterface $user
     * @param Request $request
     * @return Response
     */
    public function show_with($instance, UserInterface $user, Request $request)
    {
        $user = $this->security->getUser();

        if (null == $user) {
            exit('Not Logged in');
        }

        // get the logged in user [ROLE_]
        $role = $user->getRoles();

        // load instance from config fm_elfinder.yaml
        if ($role[0] == 'ROLE_ADMIN'){
            $homeFolder = '';
        }else{
            $homeFolder = 'user_at_'.$user->getId();
        }

        // forward with instance and home folder to the real controller
        return $this->forward(
            'FM\ElfinderBundle\Controller\ElFinderController::show',
            array(
                'instance'   => $instance,
                'homeFolder' => $homeFolder,
            )
        );

    }
}
