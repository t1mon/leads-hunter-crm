<?php

namespace App\Services\Sms;

use App\Journal\Journal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsRu implements SmsSender
{
    private $url;
    private $apiId;

    public function __construct($apiId, $url = 'https://sms.ru/sms/send')
    {
        if (empty($apiId)){
            throw new \InvalidArgumentException('Sms apiId must be set');
        }

        $this->apiId = $apiId;
        $this->url = $url;
    }

    public function send($number, $text)
    {
        return Http::get('https://sms.ru/sms/send', [
            'api_id' => $this->apiId,
            'to' => preg_replace("/[^,.0-9]/", '', $number),
            'msg' => $text,
            'json' => 1
        ]);



    }
}
