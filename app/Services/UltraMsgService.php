<?php

namespace App\Services;

use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Interfaces\MessageSenderInterface;

class UltraMSGService implements MessageSenderInterface
{
    protected string $baseUrl;
    protected string $token;
    protected string $instanceId;

    public function __construct()
    {
        $config = config('services.ultramsg');

        $this->baseUrl = rtrim($config['api_url'], '/');
        $this->token = $config['token'];
        $this->instanceId = $config['instance_id'];
    }

    /**
     * Normalize phone number
     */
    private function normalizePhoneNumber(string $phone): string
    {
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phone);

        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = ltrim($phoneNumber, '0');
        }

        return $phoneNumber;
    }

    /**
     * Send message (text, image, video, audio, document, sticker, template)
     */
    public function sendMessage(string $to, array $message, string $type = 'text')
    {
        try {
            $to = $this->normalizePhoneNumber($to);
            $endpoint = $this->resolveEndpoint($type);

            // If message contains a template_id, handle it as a template message
            if (isset($message['template_id'])) {
                return $this->sendTemplateMessage($to, $message);
            }

            // Default: handle normal messages
            $url = "{$this->baseUrl}/{$this->instanceId}/{$endpoint}";
            $payload = $this->buildPayload($to, $message, $type);

            Log::info('UltraMSG Request', ['url' => $url, 'payload' => $payload]);

            $response = Http::asForm()->post($url, $payload);
            $result = $response->json();

            if ((isset($result['success']) && $result['success'] === false) || isset($result['error'])) {
                throw new \Exception($result['error'] ?? $result['message'] ?? 'Unknown error from UltraMSG');
            }

            Log::info('UltraMSG Response', $result);
            return $result;

        } catch (\Exception $e) {
            Log::error('UltraMSG sendMessage failed', ['error' => $e->getMessage()]);
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Send message using a template
     */
    private function sendTemplateMessage(string $to, array $message)
    {
        // Assuming 'template_id' refers to a template from the database (whatsapp_templates table)
        $template = $this->getTemplateById($message['template_id']);
        if (!$template) {
            throw new \Exception('Template not found.');
        }

        // Parse and replace placeholders in the template message
        $replacedMessage = $this->replacePlaceholders($template['message'], $message['placeholders']);

        $payload = [
            'token' => $this->token,
            'to' => $to,
            'body' => $replacedMessage, // Replace the dynamic values in the message
        ];

        // Log the request for sending the message
        Log::info('UltraMSG Template Request', ['to' => $to, 'message' => $replacedMessage]);

        // Send the message via the UltraMSG API
        $url = "{$this->baseUrl}/{$this->instanceId}/messages/chat";
        $response = Http::asForm()->post($url, $payload);
        $result = $response->json();

        if ((isset($result['success']) && $result['success'] === false) || isset($result['error'])) {
            throw new \Exception($result['error'] ?? $result['message'] ?? 'Unknown error from UltraMSG');
        }

        Log::info('UltraMSG Template Response', $result);
        return $result;
    }

    /**
     * Replace placeholders with actual values
     */
    private function replacePlaceholders(string $templateMessage, array $placeholders): string
    {
        foreach ($placeholders as $key => $value) {
            $templateMessage = str_replace('{{' . $key . '}}', $value, $templateMessage);
        }
        return $templateMessage;
    }

    /**
     * Get template by ID from the database
     */
    private function getTemplateById(string $templateId)
    {
        // Assuming you have a model WhatsAppTemplate to get the template from your DB
        return WhatsappTemplate::where('id', $templateId)->first();
    }

    /**
     * Resolve endpoint by message type
     */
    private function resolveEndpoint(string $type): string
    {
        return match ($type) {
            'text' => 'messages/chat',
            'image' => 'messages/image',
            'video' => 'messages/video',
            'audio' => 'messages/audio',
            'document' => 'messages/document',
            'sticker' => 'messages/sticker',
            default => throw new \Exception("Unsupported message type: {$type}")
        };
    }

    /**
     * Build payload for UltraMSG
     */
    private function buildPayload(string $to, array $message, string $type): array
    {
        $payload = [
            'token' => $this->token,
            'to' => $to,
        ];

        switch ($type) {
            case 'text':
                $payload['body'] = $message['body'] ?? '';
                break;

            case 'image':
            case 'video':
            case 'audio':
            case 'document':
            case 'sticker':
                $payload['link'] = $message['link'] ?? '';
                $payload['caption'] = $message['caption'] ?? '';
                break;
        }

        return $payload;
    }
}
