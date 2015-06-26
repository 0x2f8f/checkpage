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
}
