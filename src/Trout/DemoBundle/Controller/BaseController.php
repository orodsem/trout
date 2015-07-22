<?php

namespace Trout\DemoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController
 * Keep those functions used in all controllers in this class
 *
 * @package Trout\DemoBundle\Controller
 */
class BaseController extends Controller
{
    /**
     * @param $entity
     */
    public function save($entity)
    {
        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * @param $entity
     */
    public function delete($entity)
    {
        $this->getDoctrine()->getManager()->remove($entity);
        $this->getDoctrine()->getManager()->flush();
    }
}