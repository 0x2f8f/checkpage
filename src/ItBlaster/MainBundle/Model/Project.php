<?php

namespace ItBlaster\MainBundle\Model;

use ItBlaster\MainBundle\Model\om\BaseProject;

class Project extends BaseProject
{
    /**
     * E-mail пользователя
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->getUser() ? $this->getUser()->getEmail() : '';
    }

    /**
     * Ссылки проекта
     *
     * @return \PropelObjectCollection
     */
    public function getLinks()
    {
        $c = new \Criteria();
        $c->addAscendingOrderByColumn(ProjectLinkPeer::TITLE);
        return $this->getProjectLinks($c);
    }
}
