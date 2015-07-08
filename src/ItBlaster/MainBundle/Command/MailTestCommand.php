<?php
namespace ItBlaster\MainBundle\Command;

use ItBlaster\MainBundle\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailTestCommand extends ContainerAwareCommand
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
            ->setName('mail:test')
            ->setDescription('отправка тестового письма')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> предназначен для тестирования работы почты

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $email = '0x2f8f@gmail.com';
        $mail_service = $this->getMailService();
        $result_send = $mail_service->sendeMail('Тестовое письмо','Тестовое содержимое письма',array($email));
        if ($result_send) {
            $this->log('Письмо успешно отправлено на <info>'.$email.'</info>');
        } else {
            $this->log('При отправке письма возникли проблемы');
        }
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