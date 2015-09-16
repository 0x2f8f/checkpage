<?php
namespace ItBlaster\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckReminderCommand extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('check:reminder')
            ->setDescription('Напоминание, что проекты не доступны')
            ->addOption('user-name',null,InputOption::VALUE_OPTIONAL,'Логин пользователя')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> Отправляет пользователь напоминание, что его проекты до сих пор недоступны

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $project_service = $this->getProjectService();

        if ($user_name = $input->getOption('user-name')) {
            if (!$user = UserQuery::create()->findOneByUsername($user_name)) {
                $this->logError('Пользователь с именем '.$user_name.' не найден');
                die();
            } else {
                $project_list = $project_service->getProjectList($user, true, false, true);
            }
        } else {
            $project_list = $project_service->getProjectsAll(true, false, true);
        }

        if (count($project_list)) {
            foreach ($project_list as $project) {
                $this->log($project->getTitle());
                $project_links = $project->getLinks(true, true);
                //TODO: далее собираем плохие ссылки в одну кучу и отправляем письмо
            }
        } else {
            $this->log('со всеми проектами всё ок');
        }
    }
}