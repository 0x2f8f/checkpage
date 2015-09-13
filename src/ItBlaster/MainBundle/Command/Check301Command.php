<?php
namespace ItBlaster\MainBundle\Command;

use FOS\UserBundle\Propel\UserQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ItBlaster\MainBundle\Model\ProjectLink;

class Check301Command extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('check:301')
            ->setDescription('Выводит ссылки, которые отдают 301 редирект')
            ->addOption('custom-port',null,InputOption::VALUE_OPTIONAL,'Запросы по кастомному порту')
            ->addOption('user-name',null,InputOption::VALUE_OPTIONAL,'Логин пользователя')
            ->addOption('trade-rehost',null,InputOption::VALUE_OPTIONAL,'Перегенерация доменов на trad')
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
        if ($trade_rehost = ($input->getOption('trade-rehost') == 'true')) {
            $rehosts = [];
        }
        $check_service = $this->getCheckService();

        if ($user_name = $input->getOption('user-name')) {
            if (!$user = UserQuery::create()->findOneByUsername($user_name)) {
                $this->logError('Пользователь с именем '.$user_name.' не найден');
                die();
            } else {
                $project_list = $this->getProjectService()->getProjectList($user, true, $is_custom_port);
            }
        } else {
            $project_list = $this->getProjectService()->getProjectsAll(true, $is_custom_port);
        }

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
                            if (!$trade_rehost) {
                                $this->log('<comment>' . $project->getTitle() . '</comment> ' . $project_link->getTitle() . ' <comment>' . $project_link->getStatusCode() . '</comment> <info>' . $project_link->getTotalTime() . '</info> редиректит на <comment>' . $project_link->getRedirectUrl() . '</comment>');
                            } else {
                                if ($project_link->getRedirectUrl() == 'http://4x4.toyota-ekaterinburg.ru/') {
                                    $rehosts[]=$project->getTitle();
                                }
                            }
                        }
                    }

                }
            }

            //ребилдинг доменов для трейда
            if ($trade_rehost && count($rehosts)) {
                $hosts = "";
                foreach ($rehosts as $host) {
                    if ($hosts) {
                        $hosts.=',';
                    }
                    $hosts.=$host;
                }
                $command = "generate:hosts --domains={".$hosts."} --rebuild={".$hosts."}";
                $this->log($command);
            }

        } else {
            $this->log('нет ни одного проекта на проверку');
        }
    }
}