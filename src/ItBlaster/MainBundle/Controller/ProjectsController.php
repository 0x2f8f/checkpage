<?php

namespace ItBlaster\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectsController extends Controller
{
    public function listAction(Request $request)
    {
        return $this->render('ItBlasterMainBundle:Projects:list.html.twig', array());
    }
}
