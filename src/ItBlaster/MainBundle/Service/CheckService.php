<?php

namespace ItBlaster\MainBundle\Service;

use ItBlaster\MainBundle\Model\ProjectLink;

class CheckService
{
    /**
     * Результат выполнения запроса на указанный адрес
     * Ответ получаем вместе с телом страницы
     *
     * @param $url
     * @return mixed
     */
    public function getCurlInfo($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
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
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
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
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
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
    public function updateLink(ProjectLink $project_link)
    {
        $info = $this->getCurlInfo($project_link->getLink());
        $project_link
            ->setStatus($info['http_code'] == '200')
            ->setStatusCode($info['http_code'])
            ->setLastCheck(time())
            ->setTotalTime($info['total_time'])
            ->save();
        return $project_link;
    }
}