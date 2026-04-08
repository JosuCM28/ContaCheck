<?php

namespace App\Services;

use OpenAI\Client;
use OpenAI;
use GuzzleHttp\Client as GuzzleClient;

class DeepSeekService
{
    protected Client $client;

    public function __construct()
    {
        $timeout = (int) config('deepseek.timeout', 300);

        $this->client = OpenAI::factory()
            ->withApiKey(config('deepseek.api_key'))
            ->withBaseUri(config('deepseek.base_uri'))
            ->withHttpClient(new GuzzleClient([
                'timeout'         => $timeout,
                'connect_timeout' => 30,
            ]))
            ->make();
    }

    /**
     * Envía un mensaje al modelo deepseek-chat y devuelve la respuesta.
     *
     * @param  string|array  $messages  Texto simple o array de mensajes [{role, content}]
     * @param  array         $options   Opciones adicionales (temperature, max_tokens, etc.)
     */
    public function chat(string|array $messages, array $options = []): string
    {
        if (is_string($messages)) {
            $messages = [
                ['role' => 'user', 'content' => $messages],
            ];
        }

        $response = $this->client->chat()->create(array_merge([
            'model'    => config('deepseek.model', 'deepseek-chat'),
            'messages' => $messages,
        ], $options));

        return $response->choices[0]->message->content ?? '';
    }

    /**
     * Igual que chat() pero usa el modelo deepseek-reasoner (modo pensamiento).
     */
    public function reason(string|array $messages, array $options = []): string
    {
        if (is_string($messages)) {
            $messages = [
                ['role' => 'user', 'content' => $messages],
            ];
        }

        $response = $this->client->chat()->create(array_merge([
            'model'    => 'deepseek-reasoner',
            'messages' => $messages,
        ], $options));

        return $response->choices[0]->message->content ?? '';
    }

    /**
     * Devuelve el objeto de respuesta completo para mayor control.
     */
    public function raw(array $payload): \OpenAI\Responses\Chat\CreateResponse
    {
        return $this->client->chat()->create($payload);
    }
}
