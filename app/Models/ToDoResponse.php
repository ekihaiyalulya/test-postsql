<?php

namespace App\Models;

class TodoResponse
{
    public bool $success;
    public string $message;
    public $payload;

    public function __construct(bool $success, string $message, $payload)
    {
        $this->success = $success;
        $this->message = $message;
        $this->payload = $payload;
    }

    public function to_json()
    {
        $res = [
            "success" => $this->success,
            "message" => $this->message,
            "payload" => $this->payload
        ];
        return $res;
    }
}
