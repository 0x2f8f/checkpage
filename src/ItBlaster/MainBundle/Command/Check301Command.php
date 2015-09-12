<?php
namespace ItBlaster\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class Check301Command extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('check:301')
            ->setDescription('Выводит ссылки, которые отдают 301 редирект')
            ->addOption('custom-port',null,InputOption::VALUE_OPTIONAL,'Запросы по кастомному порту')
            ->addOption('user_name',null,InputOption::VALUE_OPTIONAL,'Логин пользователя')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> выводит ссылки, которые отдают 301 редирект:

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $is_custom_port = ($input->getOption('custom-port') == 'true');
        $check_service = $this->getCheckService();

        $project_list = $this->getProjectService()->getProjectsAll(true, $is_custom_port);
        if (count($project_list)) {
            $this->log('Проектов на проверку: <info>'.count($project_list).'</info>');
            foreach ($project_list as $project) {
                /** @var Project $project */
                $custom_port = $is_custom_port ? $project->getPort() : false;
                $project_links = $project->getLinks(true);
                //$this->log('<comment>'.$project->getTitle().'</comment> - <info>'.count($project_links).'</info> ссылок');
                if(count($project_links)) {
                    //идём по опубликованным ссылкам сайта
                    foreach ($project_links as $project_link) {
                        /** @var ProjectLink $project_link */
                        $project_link = $check_service->updateLink($project_link, $custom_port);
                        if ($project_link->getStatusCode() == 301) {
                            $this->log( '<comment>'.$project->getTitle().'</comment> '.$project_link->getTitle() . ' <comment>' . $project_link->getStatusCode() . '</comment> <info>' . $project_link->getTotalTime() . '</info>');
                        }
                    }

                }
                //$this->log('');
            }
        } else {
            $this->log('нет ни одного проекта на проверку');
        }
    }
}