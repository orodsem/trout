<?php
namespace Trout\DemoBundle\Controller;


use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Trout\DemoBundle\Entity\File;
use Trout\DemoBundle\Entity\JobOffer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Trout\DemoBundle\Entity\Profile;
use Trout\DemoBundle\Form\JobOfferType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class JobOfferController extends BaseController
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
        $jobOffers = $this->getDoctrine()->getRepository('TroutDemoBundle:JobOffer')->findAll();

        return array('jobOffers' => $jobOffers);
    }

    /**
     * @param JobOffer $jobOffer
     * @return array
     * @Rest\View
     * @ParamConverter("jobOffer", class="TroutDemoBundle:JobOffer")
     */
    public function getAction(JobOffer $jobOffer)
    {
        return array('jobOffer' => $jobOffer);
    }

    /**
     * @ApiDoc(
     *  description="Create a new JobOffer",
     *  input="TroutDemoBundle:JobOffer",
     *  output="Symfony\Component\HttpFoundation\JsonResponse",
     *  requirements={
     *      {
     *          "name"="company",
     *          "dataType"="string",
     *          "requirement"="",
     *          "description"="Company offering an job offer"
     *      },
     *      {
     *          "name"="salaryMinimum",
     *          "dataType"="float",
     *          "requirement"="",
     *          "description"="Minimum salary"
     *      },
     *      {
     *          "name"="salaryMaximum",
     *          "dataType"="float",
     *          "requirement"="",
     *          "description"="Maximum salary"
     *      }
     *  }
     * )
     *
     * @return JsonResponse
     */
    public function addAction()
    {
        return $this->processForm(new JobOffer());
    }

    /**
     * @param JobOffer $jobOffer
     * @return JsonResponse
     */
    public function editAction(JobOffer $jobOffer)
    {
        return $this->processForm($jobOffer);
    }

    /**
     * @param JobOffer $jobOffer
     * @return JsonResponse
     */
    protected function processForm(JobOffer $jobOffer)
    {
        // all entities must have getId()
        $statusCode = is_null($jobOffer->getId()) ? 201 : 204;

        $request = $this->getRequest();

        $form = $this->createForm(new JobOfferType() , $jobOffer);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            $form->submit($request);
        }

        if ($form->isValid()) {
            $this->save($jobOffer);

            $response = new JsonResponse();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            if (201 === $statusCode) {
                $response->headers->set('Location',
                    $this->generateUrl('trout_demo_job_offer_get', array('id' => $jobOffer->getId()))
                );
            }

            return $response;
        }

        $form = View::create($form, 400);
        return $form;
    }

    /**
     * Publish the job offer.
     *
     * @param JobOffer $jobOffer
     * @return JsonResponse
     */
    public function publishAction(JobOffer $jobOffer)
    {
        $jobOffer->setStatus(JobOffer::STATUS_PUBLISHED);
        $this->save($jobOffer);

        $response = new JsonResponse();
        return $response;
    }

    /**
     * Close the job offer.
     *
     * @param JobOffer $jobOffer
     * @return JsonResponse
     */
    public function closeAction(JobOffer $jobOffer)
    {
        $jobOffer->setStatus(JobOffer::STATUS_CLOSED);
        $this->save($jobOffer);

        $response = new JsonResponse();
        return $response;
    }

    /**
     * @param JobOffer $jobOffer
     * @param Profile $profile
     * @return JsonResponse
     *
     * @ParamConverter("jobOffer", class="TroutDemoBundle:JobOffer")
     * @ParamConverter("profile", class="TroutDemoBundle:Profile", options={"id" = "profileId"})
     */
    public function offerAction(JobOffer $jobOffer, Profile $profile)
    {
        $jobOffer->addProfile($profile);
        $this->save($jobOffer);

        $response = new JsonResponse();
        return $response;
    }

    /**
     * @param JobOffer $jobOffer
     * @return JsonResponse
     */
    public function deleteAction(JobOffer $jobOffer)
    {
        $this->delete($jobOffer);

        $response = new JsonResponse();
        $response->setStatusCode(204);
        return $response;
    }
}