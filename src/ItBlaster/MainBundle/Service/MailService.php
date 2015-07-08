<?php

namespace ItBlaster\MainBundle\Service;

use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;

class MailService
{
    protected $mailer;                  //Отправщик писем
    protected $mailer_user;             //E-mail отправителя
    protected $mailer_user_title;       //Имя отправителя
    protected $templating;              //Шаблонизатор

    /**
     * Инициализируем переменные
     *
     * @param $mailer               \Swift_Mailer
     * @param $mailer_user          string
     * @param $mailer_user_title    string
     */
    public function __construct($mailer, $mailer_user, $mailer_user_title, $templating)
    {
        $this->mailer               = $mailer;
        $this->mailer_user          = $mailer_user;
        $this->mailer_user_title    = $mailer_user_title;
        $this->templating           = $templating;
    }

    /**
     * Swift_Mailer
     *
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * E-mail отправителя
     *
     * @return string
     */
    public function getMailerUser()
    {
        return $this->mailer_user;
    }

    /**
     * Имя отправителя
     *
     * @return string
     */
    public function getMailerUserTitle()
    {
        return $this->mailer_user_title;
    }

    /**
     * Шаблонизатор
     *
     * @return DelegatingEngine
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * Отправка письма
     *
     * @return boolean
     * @throws \Exception
     */
    public function sendeMail($subject = '', $body = '', array $emails_to, $attachments = array())
    {
        //от
        $from = $this->getMailerUser();
        $from_title = $this->getMailerUserTitle();


        foreach ($emails_to as $i => $email) {
            $emails_to[$i] = trim($email);
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array(
                $from => $from_title
            ))
            ->setTo($emails_to)
            ->setBody($body)
            ->setContentType("text/html");

        foreach ($attachments as $attach ) {
            $message->attach(\Swift_Attachment::fromPath($attach));
        }

        return $this->getMailer()->send($message);
    }
}