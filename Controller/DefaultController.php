<?php

namespace Httpi\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HttpiCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
