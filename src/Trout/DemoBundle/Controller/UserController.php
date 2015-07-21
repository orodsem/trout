<?php
namespace Trout\DemoBundle\Controller;


use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trout\DemoBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Trout\DemoBundle\Form\UserType;

class UserController extends BaseController
{
    /**
     * @return array
     * @Rest\View
     */
    public function allAction()
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
    public function getAction(User $user)
    {
        return array('user' => $user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function newAction(Request $request)
    {
        return $this->processForm(new User());
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function editAction(User $user)
    {
        return $this->processForm($user);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    private function processForm(User $user)
    {
        $statusCode = $user->isNew() ? 201 : 204;

        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($this->getRequest());

        if (!$form->isSubmitted()) {
            $form->submit($this->getRequest());
        }

        if ($form->isValid()) {
            $this->save($user);

            $response = new JsonResponse();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            if (201 === $statusCode) {
                $response->headers->set('Location',
                    $this->generateUrl('trout_demo_user_get', array('id' => $user->getId()))
                );
            }

            return $response;
        }

        $form = View::create($form, 400);
        return $form;
    }
}