<?php

namespace app\services;

use RuntimeException;
use yii\base\Component;

class SmsPilotClient extends Component
{
    public string $apiKey = '';
    public string $sender = '';
    public string $endpoint = 'https://smspilot.ru/api.php';
    public int $timeout = 10;

    public function send(string $phone, string $message): string
    {
        if ($this->apiKey === '') {
            throw new RuntimeException('Не задан SMSPILOT_API_KEY.');
        }

        $params = ['send' => $message, 'to' => $phone, 'apikey' => $this->apiKey, 'format' => 'json'];
        if ($this->sender !== '') {
            $params['from'] = $this->sender;
        }

        $curl = curl_init($this->endpoint . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986));
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $response = curl_exec($curl);
        if ($response === false) {
            $error = curl_error($curl);
            throw new RuntimeException('Ошибка соединения с SMSPilot: ' . $error);
        }
        $statusCode = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        $data = json_decode($response, true);
        if ($statusCode >= 400 || !is_array($data) || isset($data['error']) || empty($data['send'][0]['server_id'])) {
            $description = $data['error']['description_ru'] ?? $data['error']['description'] ?? 'Некорректный ответ SMSPilot';
            throw new RuntimeException((string) $description);
        }

        return (string) $data['send'][0]['server_id'];
    }
}
