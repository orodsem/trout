<?php
namespace Trout\DemoBundle\Controller;


use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Trout\DemoBundle\Entity\File;
use Trout\DemoBundle\Entity\Profile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Trout\DemoBundle\Form\ProfileType;

class ProfileController extends Controller
{
    /**
     * @return array
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
     * @return JsonResponse
     */
    public function addAction()
    {
        return $this->processForm(new Profile());
    }

    /**
     * @param Profile $profile
     * @return JsonResponse
     */
    public function editAction(Profile $profile)
    {
        return $this->processForm($profile);
    }

    public function uploadImageAction(Profile $profile)
    {
        $request = $this->getRequest();

        if (!$request->files->has('profilePhoto')) {
            $response = new JsonResponse();
            $response->setStatusCode(400);
            return $response;
        }

        $uploadedFile = $request->files->get('profilePhoto');
        $file = new File();
        $file->setFile($uploadedFile);
        $this->save($file);

        $profile->setProfilePhoto($file);
        $this->save($profile);
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

    /**
     * @param $entity
     */
    private function save($entity)
    {
        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();
    }
}