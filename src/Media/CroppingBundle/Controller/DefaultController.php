<?php

namespace Media\CroppingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MediaCroppingBundle:Default:index.html.twig', array('name' => $name));
    }
}
