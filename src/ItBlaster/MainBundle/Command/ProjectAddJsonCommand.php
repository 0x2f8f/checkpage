<?php
namespace ItBlaster\MainBundle\Command;

use FOS\UserBundle\Propel\UserQuery;
use ItBlaster\MainBundle\Service\CheckService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class ProjectAddJsonCommand extends Command
{
    protected $output;

    /**
     * Вывод ссобщения в консоль
     *
     * @param $messagea
     */
    protected function log($message)
    {
        //$output->writeln('<info>green color</info>');
        //$output->writeln('<comment>yellow text</comment>');
        $this->output->writeln($message);
    }

    protected function configure()
    {
        $this
            ->setName('project:add:json')
            ->setDescription('Добавление проектов на основе json')
//            ->addArgument('path',InputArgument::OPTIONAL,'Ссылка на json')
            ->addOption('user',null,InputOption::VALUE_OPTIONAL,'Пользователь (логин)')
            ->addOption('path',null,InputOption::VALUE_OPTIONAL,'Ссылка на json')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> добавляет проекты из json'а

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        if (!$user_name = $input->getOption('user')) {
            $this->error('Параметр user не указан');
        }

        if (!$user = UserQuery::create()->findOneByUsername($user_name)) {
            $this->error('Пользовать с именем '.$user_name.' не найден');
        }


        if (!$path = $input->getOption('path')) {
            $this->error('Параметр path не указан');
        }
    }

    public function error($error_message = '', $die = true)
    {
        $this->log('<comment>Error:</comment> '.$error_message);
        if ($die) {
            die();
        }
    }

    /**
     * Сервис ChecService
     *
     * @return CheckService
     */
    private function getCheckService()
    {
        return $this->getContainer()->get('check_service');
    }
}