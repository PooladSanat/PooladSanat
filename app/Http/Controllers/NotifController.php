<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class NotifController extends Controller
{


    public function toChatAPI()
    {
        $data = [
            'phone' => '989199621504', // Receivers phone
            'body' => 'Laravel', // Message
        ];
        $json = json_encode($data); // Encode data to JSON
        $url = 'https://eu105.chat-api.com/instance127679/sendMessage?token=sayx3n8u4b4klakp';
        $options = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/json',
            'content' => $json
        ]
        ]);
        $result = file_get_contents($url, false, $options);
        return $result;
    }
}
