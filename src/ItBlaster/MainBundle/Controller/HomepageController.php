<?php

namespace ItBlaster\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->redirect($this->generateUrl('projects'), 301);
        //return $this->render('ItBlasterMainBundle:Homepage:index.html.twig', array());
    }
}
