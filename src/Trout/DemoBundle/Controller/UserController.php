<?php
namespace Trout\DemoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Trout\DemoBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends Controller
{
    /**
     * @return array
     * @Rest\View
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('TroutDemoBundle:User')->findAll();

        return array('users' => $users);
    }

    /**
     * @param User $user
     * @return array
     * @Rest\View
     * @ParamConverter("user", class="TroutDemoBundle:User")
     */
    public function getUserAction(User $user)
    {
        return array('user' => $user);
    }
}