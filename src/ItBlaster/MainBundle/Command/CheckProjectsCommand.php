<?php
namespace ItBlaster\MainBundle\Command;

use ItBlaster\MainBundle\Model\Project;
use ItBlaster\MainBundle\Model\ProjectLink;
use ItBlaster\MainBundle\Service\CheckService;
use ItBlaster\MainBundle\Service\MailService;
use ItBlaster\MainBundle\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckProjectsCommand extends ContainerAwareCommand
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
            ->setName('check:projects')
            ->setDescription('Проверка проектов')
            ->addOption('custom-port',null,InputOption::VALUE_OPTIONAL,'Запросы по кастомному порту')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> предназначен проверки доступности ссылок проектов:

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $is_custom_port = ($input->getOption('custom-port') == 'true');

        $project_service = $this->getProjectService();
        $check_service = $this->getCheckService();

        $project_list = $project_service->getProjectsAll(true, $is_custom_port);
        if (count($project_list)) {
            $this->log('Проектов на обновление: <info>'.count($project_list).'</info>');
            foreach ($project_list as $project) {
                /** @var Project $project */
                $custom_port = $is_custom_port ? $project->getPort() : false;
                $project_links = $project->getLinks(true);
                $this->log('<comment>'.$project->getTitle().'</comment> - <info>'.count($project_links).'</info> ссылок');
                if(count($project_links)) {
                    $bad_links = array(); //плохие ссылки

                    //идём по опубликованным ссылкам сайта
                    foreach ($project_links as $project_link) {
                        /** @var ProjectLink $project_link */
                        $project_link = $check_service->updateLink($project_link, $custom_port);
                        $this->log((!$project_link->getStatus()?'-----!!!!!!----- ':'').$project_link->getTitle().' <comment>'.$project_link->getStatusCode().'</comment> <info>'.$project_link->getTotalTime().'</info>');
                        if(!$project_link->getStatus()) {
                            $bad_links[$project_link->getId()] = $project_link;
                        }
                    }

                    if (!$custom_port) {
                        //если есть плохие ссылки, и проект был со статусом "всё ок", то отправляем уведомление на почту
                        if (count($bad_links) && $project->getStatus()) {
                            $project
                                ->setStatus(0)
                                ->save();
                            $this->log('---------------------------------> Отправляем плохое письмо');
                            $this->resultSendMail($this->sendBadMail($project, $bad_links, $custom_port)); //отправляем письмо
                        } else if (!count($bad_links) && !$project->getStatus()) { //нет плохих ссылок, но были, то отправляем письмо, что всё хорошо
                            $project
                                ->setStatus(1)
                                ->save();
                            $this->log('---------------------------------> Отправляем хорошее письмо');
                            $this->resultSendMail($this->sendGoodMail($project, $custom_port)); //отправляем письмо
                        }
                    }
                }
                $this->log('');
            }
        } else {
            $this->log('нет ни одного проекта на обновление');
        }
    }

    /**
     * Пишем о результатах отправки письма
     *
     * @param $result_send
     */
    private function resultSendMail($result_send)
    {
        $this->log($result_send ? 'письмо успешно отправлено' : 'при отправке письма возникли проблемы') ;
    }

    /**
     * Отправка уведомления, что сайт полёг
     *
     * @param Project $project
     * @param array $bad_links
     * @return bool
     */
    private function sendBadMail(Project $project, array $bad_links, $custom_port = false)
    {
        $mail_service = $this->getMailService();
        $custom_port_text = $custom_port ? 'порт: '.$custom_port : '';
        $subject = "Проблемы с доступностью сайта ".$project->getTitle().' ('.$project->getlink().$custom_port_text.')';
        $body = "При проверке ссылок сайта <a href='".$project->getlinkUrl()."'>".$project->getTitle().'</a> '.$custom_port_text.' были недоступны следующие ссылки:<br /><br />
        <table border="1">
            <tr>
                <th>Ссылка</th>
                <th>Код ответа</th>
                <th>Время ответа</th>
            </tr>';
        foreach ($bad_links as $bad_link) {
            /** @var ProjectLink $bad_link */
            $body.='<tr>
                        <td><a href="'.$bad_link->getlinkUrl().'" target="_blank">'.$bad_link->getTitle().'</a></td>
                        <td>'.$bad_link->getStatusCode().'</td>
                        <td>'.$bad_link->getTotalTime().'</td>
                    </tr>';
        }
        $body.="</table><br />
            --------<br />
            <a href='http://checkpage.ru'>CheckPage.ru</a>";
        $email = $project->getUserEmail();
        return $mail_service->sendeMail($subject,$body,array($email));
    }

    /**
     * Отправка уведомления, что с сайтом всё ОК
     *
     * @param Project $project
     * @return bool
     */
    private function sendGoodMail(Project $project, $custom_port = false)
    {
        $mail_service = $this->getMailService();
        $custom_port_text = $custom_port ? 'порт: '.$custom_port : '';
        $subject = "Доступ к сайту ".$project->getTitle(). $custom_port_text.' полностью восстановлен';
        $body = "Доступ к сайту <a href='".$project->getlinkUrl()."'>".$project->getTitle()."</a> (".$custom_port_text.") полностью восстановлен.<br /><br />--------<br /><a href='http://checkpage.ru'>CheckPage.ru</a>";
        $email = $project->getUserEmail();
        return $mail_service->sendeMail($subject,$body,array($email));
    }

    /**
     * Сервис проверки доступности ссылок
     *
     * @return CheckService
     */
    private function getCheckService()
    {
        return $this->getContainer()->get('check_service');
    }

    /**
     * Сервис для работы с проектами
     *
     * @return ProjectService
     */
    private function getProjectService()
    {
        return $this->getContainer()->get('project_service');
    }

    /**
     * Почтовый сервис
     *
     * @return MailService
     */
    private function getMailService()
    {
        return $this->getContainer()->get('mail_service');
    }
}