<?php

namespace ItBlaster\MainBundle\Service;

use FOS\UserBundle\Propel\User;
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
}