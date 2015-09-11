<?php
namespace ItBlaster\MainBundle\Command;

trait CommandTrait
{
    protected $output;

    /**
     * Вывод ссобщения в консоль
     *
     * @param $message
     */
    protected function log($message)
    {
        //$output->writeln('<info>green color</info>');
        //$output->writeln('<comment>yellow text</comment>');
        $this->output->writeln($message);
    }

    /**
     * Вывод в лог ошибки
     *
     * @param $error
     */
    protected function logError($error)
    {
        $this->log('<comment>ERROR</comment> '.$error);
    }

    /**
     * Сервис для работы с проектами
     *
     * @return ProjectService
     */
    protected function getProjectService()
    {
        return $this->getContainer()->get('project_service');
    }
}