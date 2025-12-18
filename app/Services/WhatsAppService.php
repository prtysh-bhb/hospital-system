<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;

class WhatsAppService
{
    protected $client;
    protected $config;
    protected $baseUrl;
    protected $headers;

    public function __construct()
    {
        $this->config = config('whatsapp');
        $this->baseUrl = $this->config['api_url'];

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30.0,
            'verify' => false, // Only for development
        ]);

        $this->headers = [
            'Authorization' => 'Bearer ' . $this->config['access_token'],
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Validate WhatsApp phone number format
     */
    public function validatePhoneNumber(string $phoneNumber): array
    {
        // Remove all non-digits
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (strlen($cleaned) < 10) {
            return [
                'valid' => false,
                'message' => 'Phone number too short'
            ];
        }
        
        if (strlen($cleaned) > 15) {
            return [
                'valid' => false,
                'message' => 'Phone number too long'
            ];
        }
        
        // Check if it starts with country code
        if (!preg_match('/^\d{10,15}$/', $cleaned)) {
            return [
                'valid' => false,
                'message' => 'Invalid phone number format'
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Phone number is valid',
            'formatted' => $cleaned
        ];
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage(string $to, array $message, string $type = 'text')
    {
        try {
            // Validate phone number first
            $validation = $this->validatePhoneNumber($to);
            if (!$validation['valid']) {
                throw new \Exception($validation['message']);
            }
            
            $to = $validation['formatted'];
            
            $url = "/{$this->config['phone_number_id']}/messages";

            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => $type,
            ];

            // Add message content based on type
            switch ($type) {
                case 'text':
                    $payload['text'] = ['body' => $message['body']];
                    break;

                case 'image':
                    $payload['image'] = [
                        'link' => $message['link'],
                        'caption' => $message['caption'] ?? null,
                    ];
                    break;

                case 'document':
                    $payload['document'] = [
                        'link' => $message['link'],
                        'filename' => $message['filename'],
                        'caption' => $message['caption'] ?? null,
                    ];
                    break;

                case 'template':
                    $payload['template'] = [
                        'name' => trim($message['name']),
                        'language' => ['code' => trim($message['language'])],
                        // 'components' => $message['components'] ?? [],
                    ];
                    // dd($payload);
                    break;

                case 'interactive':
                    $payload['interactive'] = $message['interactive'];
                    break;
            }
            
            $response = $this->client->post($url, [
                'headers' => $this->headers,
                'json' => $payload,
            ]);
            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            // Log full Guzzle error for debugging
            $errorBody = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();
            Log::error('WhatsApp API Error: ' . $errorBody);

            // Provide readable messages
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                switch ($statusCode) {
                    case 400:
                        throw new \Exception('Bad Request: Check phone number format or Phone Number ID.');
                    case 401:
                        throw new \Exception('Unauthorized: Access token is invalid or expired.');
                    case 403:
                        throw new \Exception('Forbidden: Your app may not have permission to perform this action.');
                    case 404:
                        throw new \Exception('Not Found: Endpoint or resource not found.');
                    case 429:
                        throw new \Exception('Too Many Requests: You are being rate limited by WhatsApp API.');
                    default:
                        // Catch other HTTP errors
                        throw new \Exception("WhatsApp API returned status {$statusCode}: " . $errorBody);
                }
            }

            // Catch network or other Guzzle exceptions
            throw new \Exception('WhatsApp API Error: ' . $e->getMessage());
        }
    }

    /**
     * Upload media to WhatsApp
     */
    public function uploadMedia(UploadedFile $file)
    {
        try {
            $mimeType = $file->getMimeType(); // image/jpeg

            // Map MIME → WhatsApp type
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($mimeType, 'audio/')) {
                $type = 'audio';
            } else {
                $type = 'document';
            }

            $url = "/{$this->config['phone_number_id']}/media";

            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config['access_token'],
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getPathname(), 'rb'),
                        'filename' => $file->getClientOriginalName(),
                        'headers' => [
                            'Content-Type' => $mimeType, // VERY IMPORTANT
                        ],
                    ],
                    [
                        'name' => 'type',
                        'contents' => $type,
                    ],
                    [
                        'name' => 'messaging_product',
                        'contents' => 'whatsapp',
                    ],
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            Log::error('WhatsApp Media Upload Error: ' . $e->getResponse()->getBody());
            throw new \Exception('Media upload failed');
        }
    }

    /**
     * Get media URL
     */
    public function getMediaUrl(string $mediaId)
    {
        try {
            // Build the URL with query params
            $query = [
                'phone_number_id' => $this->config['phone_number_id'], // your WhatsApp Business phone ID
            ];

            // Send GET request to WhatsApp Graph API
            $response = $this->client->get("/{$mediaId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config['access_token'],
                ],
                'query' => $query,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Check if 'url' exists
            if (!isset($data['url'])) {
                Log::error('WhatsApp Get Media Error: URL not found', ['response' => $data]);
                return null;
            }

            return [
                'media_id' => $data['id'],
                'url' => $data['url'],
                'mime_type' => $data['mime_type'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp Get Media Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete media
     */
    public function deleteMedia(string $mediaId)
    {
        try {
            $url = "/{$mediaId}";

            $response = $this->client->delete($url, [
                'headers' => $this->headers,
            ]);

            return $response->getStatusCode() === 200;

        } catch (RequestException $e) {
            Log::error('WhatsApp Delete Media Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create message template
     */
    public function createTemplate(array $data)
    {
        try {
            $url = "/{$this->config['business_account_id']}/message_templates";

            $payload = [
                'name' => strtolower(trim($data['name'])),
                'language' => $data['language'], // en_US
                'category' => $data['category'],
                'components' => [],
            ];

            // HEADER (optional)
            if (!empty($data['header'])) {
                $payload['components'][] = [
                    'type' => 'HEADER',
                    'format' => 'TEXT',
                    'text' => $data['header'],
                ];
            }

            // BODY (required)
            $payload['components'][] = [
                'type' => 'BODY',
                'text' => $data['body'],
            ];

            // FOOTER (optional)
            if (!empty($data['footer'])) {
                $payload['components'][] = [
                    'type' => 'FOOTER',
                    'text' => $data['footer'],
                ];
            }

            // BUTTONS (optional)
            if (!empty($data['buttons'])) {
                $payload['components'][] = [
                    'type' => 'BUTTONS',
                    'buttons' => $data['buttons'],
                ];
            }

            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config['access_token'],
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            $error = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();

            \Log::error('WhatsApp Template Error: ' . $error);
            throw new \Exception($error);
        }
    }

    /**
     * Get message template list
     */
    public function getTemplates()
    {
        try {
            $url = "/{$this->config['business_account_id']}/message_templates";

            $response = $this->client->get($url, [
                'headers' => $this->headers,
            ]);

            return json_decode($response->getBody(), true);
        }catch (RequestException $e) {
            $error = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();

            Log::error('WhatsApp Get Templates Error: ' . $e->getMessage());
            throw new \Exception($error);
        }
    }

    /**
     * Delete message template
     */
    public function deleteTemplate(string $templateName)
    {
        try {
            $url = "/{$this->config['business_account_id']}/message_templates?name={$templateName}";

            $response = $this->client->delete($url, [
                'headers' => $this->headers,
            ]);

            return $response->getStatusCode() === 200;

        } catch (RequestException $e) {
            $error = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();

            \Log::error('WhatsApp Delete Template Error: ' . $error);
            return false;
        }
    }

    /**
     * Verify webhook
     */
    public function verifyWebhook(string $mode, string $token, string $challenge)
    {
        if ($mode === 'subscribe' && $token === $this->config['verify_token']) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }
}