<?php
namespace ItBlaster\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ProjectSetportCommand extends ContainerAwareCommand
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

    protected function configure()
    {
        $this
            ->setName('project:set-port')
            ->setDescription('Добавление кастомного порта проектам')
            ->addOption('project_id',null,InputOption::VALUE_OPTIONAL,'Id проекта')
            ->addOption('user',null,InputOption::VALUE_OPTIONAL,'Логин пользователя')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> добавляет кастомный порт проекту, либо проектам пользователя

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        if ($input->getOption('project_id')) {

        } elseif ($input->getOption('user')) {

        } else {
            $this->log('<comment>ERROR</comment> необходимо указать хотя бы один параметр (<info>project_id</info> или <info>user</info>)');
        }
    }

    private function addCustomPort($project, $port)
    {
        
    }
}