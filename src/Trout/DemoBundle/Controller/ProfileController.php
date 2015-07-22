<?php
namespace Trout\DemoBundle\Controller;


use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Trout\DemoBundle\Entity\File;
use Trout\DemoBundle\Entity\Profile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Trout\DemoBundle\Form\ProfileType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProfileController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Returns all JobOffers"
     * )
     *
     * @return JsonResponse
     * @Rest\View
     */
    public function allAction()
    {
        $profiles = $this->getDoctrine()->getRepository('TroutDemoBundle:Profile')->findAll();

        return array('profiles' => $profiles);
    }

    /**
     * @param Profile $profile
     * @return array
     * @Rest\View
     * @ParamConverter("profile", class="TroutDemoBundle:Profile")
     */

    public function getAction(Profile $profile)
    {
        return array('profile' => $profile);
    }

    /**
     * @ApiDoc(
     *  description="Create a new profile",
     *  input="TroutDemoBundle:Profile",
     *  output="Symfony\Component\HttpFoundation\JsonResponse",
     *  requirements={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "requirement"="",
     *          "description"="First name it is also called given name or Christian name. It is the name your parents give you at birth."
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "requirement"="",
     *          "description"="A family name is typically a part of a person's personal name which, according to law or custom, is passed or given to children from one or both of their parents' family names"
     *      },
     *      {
     *          "name"="position",
     *          "dataType"="string",
     *          "requirement"="",
     *          "description"="The position currently occupied by a professional resource in the organisation he or she are employed by."
     *      }
     *  }
     * )
     *
     * @return JsonResponse
     */
    public function addAction()
    {
        return $this->processForm(new Profile());
    }

    /**
     * @ApiDoc(
     *  description="Edit a profile",
     *  input="TroutDemoBundle:Profile",
     *  output="Symfony\Component\HttpFoundation\JsonResponse",
     *  requirements={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "requirement"="",
     *          "description"="First name it is also called given name or Christian name. It is the name your parents give you at birth."
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "requirement"="",
     *          "description"="A family name is typically a part of a person's personal name which, according to law or custom, is passed or given to children from one or both of their parents' family names"
     *      },
     *      {
     *          "name"="position",
     *          "dataType"="string",
     *          "requirement"="",
     *          "description"="The position currently occupied by a professional resource in the organisation he or she are employed by."
     *      }
     *  }
     * )
     *
     * @param Profile $profile
     * @return JsonResponse
     */
    public function editAction(Profile $profile)
    {
        return $this->processForm($profile);
    }

    /**
     * @ApiDoc(
     *  description="Edit a profile",
     *  input="TroutDemoBundle:Profile",
     *  output="Symfony\Component\HttpFoundation\JsonResponse",
     *  requirements={
     *      {
     *          "name"="profilePhoto",
     *          "dataType"="TroutDemoBundle:File",
     *          "requirement"="",
     *          "description"="a profile photo really demonstrates your talent, look and personality."
     *      }
     *  },
     * statusCodes={
     *         200="Returned when successfully uploaded",
     *         404={
     *           "Returned when the profile photo not set",
     *         }
     *     }
     * )
     *
     * @param Profile $profile
     * @return JsonResponse
     */
    public function uploadImageAction(Profile $profile)
    {
        $request = $this->getRequest();
        $response = new JsonResponse();

        if (!$request->files->has('profilePhoto')) {
            $response->setStatusCode(404);
            return $response;
        }

        $uploadedFile = $request->files->get('profilePhoto');
        $file = new File();
        $file->setFile($uploadedFile);
        $this->save($file);

        $profile->setProfilePhoto($file);
        $this->save($profile);

        $response->setStatusCode(200);
        return $response;
    }

    /**
     * @ApiDoc(
     *     statusCodes={
     *         204="Returned when successfully deleted",
     *         404={
     *           "Returned when the profile is not found",
     *         }
     *     }
     * )
     *
     * @param Profile $profile
     * @return JsonResponse
     */
    public function deleteAction(Profile $profile)
    {
        $this->delete($profile);

        $response = new JsonResponse();
        $response->setStatusCode(204);
        return $response;
    }

    /**
     * @param Profile $profile
     * @return View|\Symfony\Component\Form\Form|JsonResponse
     */
    protected function processForm(Profile $profile)
    {
        // all entities must have getId()
        $statusCode = is_null($profile->getId()) ? 201 : 204;

        $request = $this->getRequest();

        $form = $this->createForm(new ProfileType() , $profile);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            $form->submit($request);
        }

        if ($form->isValid()) {
            $this->save($profile);

            $response = new JsonResponse();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            if (201 === $statusCode) {
                $response->headers->set('Location',
                    $this->generateUrl('trout_demo_profile_get', array('id' => $profile->getId()))
                );
            }

            return $response;
        }

        $form = View::create($form, 400);
        return $form;
    }
}