<?php

namespace ItBlaster\MainBundle\Service\base;

abstract class BaseProjectService
{
    protected $request;

    public function __construct($request_stack)
    {
        $this->setRequest($request_stack->getCurrentRequest());
    }

    /**
     * @return mixed
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request_stack
     */
    protected function setRequest($request_stack)
    {
        $this->request = $request_stack;
    }
}