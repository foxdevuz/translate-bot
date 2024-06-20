<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

class TranslationService
{
    /**
     * @throws GuzzleException
     */
    public static function translate($source, $target, $text) {
        $url = "https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e";

        $client = new Client([
            'headers' => [
                'User-Agent' => 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1'
            ]
        ]);

        $response = $client->post($url, [
            'form_params' => [
                'sl' => $source,
                'tl' => $target,
                'q' => $text
            ]
        ]);

        $result = json_decode($response->getBody()->getContents());

        return $result->sentences[0]->trans;
    }
}
