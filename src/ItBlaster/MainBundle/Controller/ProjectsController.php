<?php

namespace ItBlaster\MainBundle\Controller;

use ItBlaster\MainBundle\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectsController extends Controller
{
    private $project_service = null;

    /**
     * Список проектов пользователя
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $project_service = $this->getProjectService();
        $user_project_list = $project_service->getProjectList($this->getUser());
        return $this->render('ItBlasterMainBundle:Projects:list.html.twig', [
            'project_list'  => $user_project_list
        ]);
    }

    public function showAction(Request $request, $project_name)
    {
        $project_service = $this->getProjectService();
        $project = $project_service->getProject($project_name);

        if (!$project) {
            throw $this->createNotFoundException('Project not fonud');
        }

        return $this->render('ItBlasterMainBundle:Projects:show.html.twig', [
            'project'  => $project
        ]);
    }

    public function addAction(Request $request)
    {

    }

    /**
     * Возвращает сервис по работе с проектами
     *
     * @return ProjectService
     */
    private function getProjectService()
    {
        if (!$this->project_service) {
            $this->project_service = $this->container->get('project_service');
        }
        return $this->project_service;
    }
}
