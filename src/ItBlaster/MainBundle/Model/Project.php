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
        return $this->getEmail() ? $this->getEmail() : ($this->getUser() ? $this->getUser()->getEmail() : '');
    }

    /**
     * Ссылки проекта
     *
     * @param bool $active
     * @return \PropelObjectCollection
     */
    public function getLinks($active = false)
    {
        $c = new \Criteria();
        if ($active) {
            $c->add(ProjectLinkPeer::ACTIVE,true);
        }
        $c->addAscendingOrderByColumn(ProjectLinkPeer::TITLE);
        return $this->getProjectLinks($c);
    }
}
