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

    /**
     * Добавление ссылки в проект
     *
     * @param $link
     * @param $title
     * @return mixed
     * @throws \Exception
     * @throws \PropelException
     */
    public function addLink($link, $title)
    {
        $project_link = new ProjectLink();
        $project_link
            ->setLink($link)
            ->setTitle($title)
            ->setActive(true)
            ->setProject($this)
            ->save();
        return $link;
    }

    /**
     * Адрес сайта с http
     *
     * @return string
     */
    public function getlinkUrl()
    {
        $link = $this->getLink();
        return strstr($link,'http') ? $link : 'http://'.$link;
    }
}
