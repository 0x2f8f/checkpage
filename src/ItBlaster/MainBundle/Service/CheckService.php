<?php

namespace ItBlaster\MainBundle\Service;

use ItBlaster\MainBundle\Model\ProjectLink;

class CheckService
{
    private $CURLOPT_TIMEOUT = 15;

    /**
     * Результат выполнения запроса на указанный адрес
     * Ответ получаем вместе с телом страницы
     *
     * @param $url
     * @param $custom_port
     * @return mixed
     */
    public function getCurlInfo($url, $custom_port = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,$this->getCurloptTimeout());

        //кастомный порт
        if ($custom_port) {
            curl_setopt($ch, CURLOPT_PORT, $custom_port);
        }

        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch);
        curl_close($ch);

        return $httpcode;
    }

    /**
     * Статус ответа от сервера по заданной ссылке
     * В ответе приходит только header без body страницы
     *
     * @param $url
     * @return mixed
     */
    public function getStatusCode($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,$this->getCurloptTimeout());
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpcode;
    }

    /**
     * Общее затрасенное время
     *
     * @param $url
     * @return mixed
     */
    public function getTotalTime($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,$this->getCurloptTimeout());
        $output = curl_exec($ch);
        $total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        curl_close($ch);

        return $total_time;
    }

    /**
     * Обновление информации по ссылке
     *
     * @param ProjectLink $project_link
     * @return ProjectLink
     * @throws \Exception
     * @throws \PropelException
     */
    public function updateLink(ProjectLink $project_link, $custom_port = false)
    {
        $link = $project_link->getLink();
        $info = $this->getCurlInfo($link, $custom_port);
        $project_link
            ->setStatus($info['http_code'] == '200')
            ->setStatusCode($info['http_code'])
            ->setLastCheck(time())
            ->setTotalTime($info['total_time']);
        if ($info['http_code'] == '301') {
            $project_link->setRedirectUrl($info['redirect_url']);
        }
        $project_link->save();
        return $project_link;
    }

    /**
     * HTML страницы
     *
     * @param $url
     * @return string
     */
    public function getHtml($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_NOBODY, false);    // we need body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,$this->getCurloptTimeout());
        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr( $response, $header_size );
        curl_close($ch);

        return $body;
    }

    private function getCurloptTimeout()
    {
        return $this->CURLOPT_TIMEOUT;
    }
}