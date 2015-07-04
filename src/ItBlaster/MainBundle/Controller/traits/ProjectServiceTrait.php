<?php
namespace ItBlaster\MainBundle\Controller\traits;

use ItBlaster\MainBundle\Service\ProjectService;

trait ProjectServiceTrait {

    private $project_service = null; //Сервис для работы с проектами

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

    /**
     * Проверяем наличие прав у пользователя на проект
     *
     * @param Project $project
     */
    private function checkPermissions(Project $project)
    {
        if (!$this->getProjectService()->hasCredential($this->getUser(), $project)) {
            throw $this->createNotFoundException('Access denied');
        }
    }

    /**
     * Вовзвращает проект по алиасу
     *
     * @param $project_name
     * @return Project
     */
    private function getProject($project_name)
    {
        $project = $this->getProjectService()->getProject($project_name);
        if (!$project) {
            throw $this->createNotFoundException('Project not fonud');
        }
        return $project;
    }
}