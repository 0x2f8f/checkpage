<?php
namespace ItBlaster\MainBundle\Command;

use ItBlaster\MainBundle\Service\CheckService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckTestCodeCommand extends ContainerAwareCommand
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
            ->setName('check:test:code')
            ->setDescription('Код статуса ответа от artsofte.ru')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> предназначен проверки работоспособности сервиса проверки доступности сайта. Запрос отправляется на адрес artsofte.ru:

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $url = 'http://www.artsofte.ru/';

        $info = $this->getCheckService()->getCurlInfo($url);
        $this->log('<comment>Status code:</comment> '.$info['http_code']);
        $this->log('<comment>Total time:</comment> '.$info['total_time']);
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