<?php
namespace ItBlaster\MainBundle\Command;

use ItBlaster\MainBundle\Service\CheckService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class CheckTestContainsCommand extends ContainerAwareCommand
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
            ->setName('check:test:contains')
            ->setDescription('Проверка наличие текста "Infinet – Корпоративный сайт" на странице результатов поиска по портфолио artsofte.ru')
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

        $url = 'https://www.sipnet.ru/contacts/russia';
        $find_text = "Заключение договоров";
        $result = 0;

        $check_service = $this->getContainer()->get('check_service');
        $html = $check_service->getHtml($url);
        //dump($html);
        if ($html!==false) {
            $crawler = new Crawler($html);
            $result = $crawler->filter('html:contains("'.$find_text.'")')->count();
            dump($result);
        }

        $this->log($find_text.' - '.($result ? '<info>OK</info>' : '<comment>FAIL</comment>'));
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