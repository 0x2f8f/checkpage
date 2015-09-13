<?php
namespace ItBlaster\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class MainTestCommand extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('main:test')
            ->setDescription('The task  for testing the  functional')
//            ->addArgument('name',InputArgument::OPTIONAL,'Who do you want to greet?')
//            ->addOption('yell',null,InputOption::VALUE_NONE,'If set, the task will yell in uppercase letters')
            ->setHelp(<<<EOF
Таск <info>%command.name%</info> предназначен для тестирования разрабатываемого функционала:

<info>php %command.full_name%</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $url = 'https://www.sipnet.ru/search?query=sipnet#loginPasswordRecover';

        $check_service = $this->getContainer()->get('check_service');
        $html = $check_service->getHtml($url);

        if ($html!==false) {
            $crawler = new Crawler($html);
            $find_text = "Дешевая связь в роуминге";
            $p = $crawler->filter('html:contains("' . $find_text . '")')->count();
            dump($p);
        }
    }
}