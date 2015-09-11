<?php
namespace ItBlaster\MainBundle\Command;

use FOS\UserBundle\Propel\UserQuery;
use ItBlaster\MainBundle\Model\Project;
use ItBlaster\MainBundle\Model\ProjectQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProjectSetportCommand extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('project:set-port')
            ->setDescription('Добавление кастомного порта проектам')
            ->addOption('port',null,InputOption::VALUE_REQUIRED,'Порт')
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

        if (!$port = $input->getOption('port')) {
            $this->logError('необходимо указать переметр <info>port</info>'); die();
        }

        if ($project_id = $input->getOption('project_id')) {
            if (!$project = ProjectQuery::create()->findOneById($project_id)) {
                $this->logError('Project by id='.$project_id.' not found');
            } else {
                $this->addCustomPort($project, $port); //прописываем кастомный порт
            }
        } elseif ($user_name = $input->getOption('user')) {
            if (!$user = UserQuery::create()->findOneByUsername($user_name)) {
                $this->logError('Пользователь с именем <info>'.$user_name.'</info> не найден');
            } else {
                $projects = $this->getProjectService()->getProjectList($user);
                if (!count($projects)) {
                    $this->logError('У пользователя <info>'.$user_name.'</info> не найден ни один проект');
                } else {
                    foreach ($projects as $project_item) {
                        $this->addCustomPort($project_item, $port);
                    }
                }
            }
        } else {
            $this->logError('необходимо указать хотя бы один параметр (<info>project_id</info> или <info>user</info>)');
        }
    }

    /**
     * Проставляем кастомный порт проекту
     *
     * @param Project $project
     * @param $port
     * @throws \Exception
     * @throws \PropelException
     */
    private function addCustomPort(Project $project, $port)
    {
        $project
            ->setPort($port)
            ->save();
        $this->log('<info>SUCCESS</info> Кастомный порт <comment>'.$port.'</comment> у проекта <comment>'.$project->getTitle().'</comment> успешно прописан');
    }
}