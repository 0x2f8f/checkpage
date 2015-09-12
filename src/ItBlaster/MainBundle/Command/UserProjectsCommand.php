<?php
namespace ItBlaster\MainBundle\Command;

use FOS\UserBundle\Propel\UserQuery;
use ItBlaster\MainBundle\Model\Project;
use ItBlaster\MainBundle\Model\ProjectQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserProjectsCommand extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('user:projects')
            ->setDescription('Список проектов пользователя')
            ->addOption('user_name',null,InputOption::VALUE_OPTIONAL,'Логин пользователя')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> выводит список проектов по указанному имени пользователя

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        if (!$user_name = $input->getOption('user_name')) {
            $this->logError('необходимо указать пераметр <info>user_name</info>'); die();
        }
        if (!$user = UserQuery::create()->findOneByUsername($user_name)) {
            $this->logError('Пользователь с именем <info>'.$user_name.'</info> не найден');
        } else {
            $projects = $this->getProjectService()->getProjectList($user);
            if (!count($projects)) {
                $this->logError('У пользователя <info>'.$user_name.'</info> не найден ни один проект');
            } else {
                foreach ($projects as $project_item) {
                    /** @var Project $project_item */
                    $custom_port_text = $project_item->getPort() ? ' кастомный порт:<comment>'.$project_item->getPort().' </comment>' : '';
                    $this->log('id:<comment>'.$project_item->getId().'</comment> title:<comment>'.$project_item->getTitle().'</comment>'.$custom_port_text);
                }
            }
        }
    }

}