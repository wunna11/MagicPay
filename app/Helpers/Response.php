<?php

namespace App\Helpers;


class Response
{
    public function __construct($message, $data)
    {
        $this->message = $message;
        $this->data = $data;
    }
    public function success()
    {
        return response()->json([
            'result' => 1,
            'message' => $this->message,
            'data' => $this->data
        ]);
    }

    public function fail()
    {
        return response()->json([
            'result' => 0,
            'message' => $this->message,
            'data' => $this->data
        ]);
    }
}
