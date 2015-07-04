<?php

namespace ItBlaster\MainBundle\Model;

use ItBlaster\MainBundle\Model\om\BaseProjectLink;

class ProjectLink extends BaseProjectLink
{
    /**
     * Укороченная ссылка
     *
     * @param int $length
     * @return string
     */
    public function getLinkShort($length = 50)
    {
        return  strlen($this->getLink()) > $length ?
                substr($this->getLink(), 0, $length).'...' :
                $this->getLink();
    }
}
