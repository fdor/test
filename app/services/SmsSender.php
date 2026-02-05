<?php

namespace app\services;

use Exception;

/**
 * Сервис отправки смс
 */
class SmsSender
{
    /**
     * Отправка смс
     *
     * @param string $phone
     * @param string $text
     * @return bool
     * @throws Exception
     */
    public function send(string $phone, string $message): bool
    {
        $sender = 'INFORM';
        $apikey = getenv('SMS_API_KEY');

        $url = 'https://smspilot.ru/api.php'
            .'?send=' . urlencode($message)
            .'&to=' . urlencode($phone)
            .'&from=' . $sender
            .'&apikey=' . $apikey
            .'&format=json';

        $json = file_get_contents($url);
        $j = json_decode($json);

        if (!isset($j->error)) {
            return true;
        } else {
            throw new Exception($j->error->description_ru);
        }
    }
}
