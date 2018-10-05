<?php

namespace DomingoLlanes\StrongParametersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('StrongParametersBundle:Default:index.html.twig');
    }
}
