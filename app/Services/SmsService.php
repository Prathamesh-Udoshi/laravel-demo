<?php
namespace App\Services;

class SmsService
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(string $to, string $message): string
    {
        return "SMS successfully sent to {$to} (API Key used: {$this->apiKey}): '{$message}'";
    }
}
