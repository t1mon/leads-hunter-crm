<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class WebhookController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {
        //
    } //Конструктор

    public function getIncomingRequest() //Обработать входящие запросы от Telegram
    {

    } //getIncomingRequest

    public function setWebhook()
    {

    } //setWebhook

    public function deleteWebhook()
    {

    } //deleteWebhook

    public function getWebhookInfo()
    {

    } //getWebhookInfo
}
