<?php

namespace ItBlaster\MainBundle\Service;

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
}