<?php

namespace ItBlaster\MainBundle\Service;

use FOS\UserBundle\Propel\User;
use ItBlaster\MainBundle\Model\Project;
use ItBlaster\MainBundle\Model\ProjectQuery;
use ItBlaster\MainBundle\Service\base\BaseProjectService;

class ProjectService extends BaseProjectService
{
    /**
     * Список проектов пользователя
     *
     * @param User $user
     * @param bool $active
     * @return \PropelCollection
     * @throws \PropelException
     */
    public function getProjectList(User $user, $active = false)
    {
        return ProjectQuery::create()
            ->filterByUser($user)
            ->_if($active)
                ->filterByActive(true)
            ->_endif()
            ->orderByTitle()
            ->find();
    }

    /**
     * Конкретный проект
     *
     * @param $project_name
     * @param bool $active
     * @return mixed
     */
    public function getProject($project_name, $active = false)
    {
        return ProjectQuery::create()
            ->filterBySlug($project_name)
            ->_if($active)
                ->filterByActive(true)
            ->_endif()
            ->findOne();
    }

    /**
     * Проверка прав у пользователя на проект
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function hasCredential(User $user, Project $project)
    {
        return ($project->getUserId() == $user->getId() || $user->hasRole('ROLE_SUPER_ADMIN'));
    }

    /**
     * Все проекты пользователей
     *
     * @param bool $active
     * @return \PropelObjectCollection
     */
    public function getProjectsAll($active = true)
    {
        return ProjectQuery::create()
            ->_if($active)
                ->filterByActive(true)
            ->_endif()
            ->orderByTitle()
            ->find();
    }

    /**
     * Добавляем проект и ссылку
     *
     * @param User $user
     * @param $link
     * @return bool
     * @throws \Exception
     * @throws \PropelException
     */
    public function addProjectByLink(User $user, $link)
    {
        if ($this->projectIsset($link)) {
            return false;
        }

        $title = $link;
        $title_params = explode('//',$link);
        if (isset($title_params[1])) {
            $title_params2 = explode('/', $title_params[1]);
            $title = $title_params2[0];
        }

        //добавляем проект
        $project = new Project();
        $project
            ->setTitle($title)
            ->setActive(true)
            ->setLink($link)
            ->setUser($user)
            ->save();

        //проверяем что проект создался
        if (!$project) {
            throw new \Exception('Не удалось добавить проект '.$title);
        }

        //добавляем сслыку
        if (!$project->addLink($link, 'Главная')) {
            throw new \Exception('Не удалось добавить ссылку к проекту '.$title);
        }

        return true;
    }

    /**
     * Проверка что проект по указанной ссылке уже существует
     *
     * @param $link
     * @return int
     */
    public function projectIsset($link)
    {
        return ProjectQuery::create()
            ->filterByLink($link)
            ->count();
    }
}