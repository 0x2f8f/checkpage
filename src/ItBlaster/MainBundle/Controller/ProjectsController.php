<?php

namespace ItBlaster\MainBundle\Controller;

use ItBlaster\MainBundle\Model\Project;
use ItBlaster\MainBundle\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
        $user_project_list = $this->getProjectService()->getProjectList($this->getUser());

        return $this->render('ItBlasterMainBundle:Projects:list.html.twig', [
            'project_list'  => $user_project_list
        ]);
    }

    /**
     * Страница проекта
     *
     * @param Request $request
     * @param $project_name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, $project_name)
    {
        $project = $this->getProject($project_name); //ищем проект

        return $this->render('ItBlasterMainBundle:Projects:show.html.twig', [
            'project'  => $project
        ]);
    }

    /**
     * Добавление проекта
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('project');
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $project = $this->addProject($form);

                //редиректим на страницу просмотра проекта
                return $this->redirect($this->generateUrl('project-show', array(
                    'project_name' => $project->getSlug()
                )));
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('ItBlasterMainBundle:Projects:add.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    /**
     * Редактирование проекта
     *
     * @param Request $request
     * @param $project_name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $project_name)
    {
        $project = $this->getProject($project_name); //ищем проект
        $this->checkPermissions($project); //проверяем наличие прав у пользователя на проект

        $form = $this->createForm('project', $project);
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                /** @var Project $project */
                $project = $form->getData();
                $project->save();

                //редиректим на страницу просмотра проекта
                return $this->redirect($this->generateUrl('project-show', array(
                    'project_name' => $project->getSlug()
                )));
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('ItBlasterMainBundle:Projects:edit.html.twig', [
            'form'      => $form->createView(),
            'project'   => $project
        ]);
    }

    /**
     * Удаление проекта
     *
     * @param Request $request
     * @param $project_name
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $project_name)
    {
        $project = $this->getProject($project_name); //ищем проект
        $this->checkPermissions($project); //проверяем наличие прав у пользователя на проект
        $project->delete(); //удаляем проект

        return $this->redirect($this->generateUrl('projects')); //редиректим на страницу списка проектов
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

    /**
     * Создание объекта "Проект" в базе
     *
     * @param $form
     * @return Project
     * @throws \Exception
     * @throws \PropelException
     */
    private function addProject($form)
    {
        $data = $form->getData();
        $project = new Project();
        $project
            ->setTitle($data['title'])
            ->setActive(true)
            ->setUser($this->getUser())
            ->save();
        return $project;
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
