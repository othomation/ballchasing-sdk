<?php

namespace Lucie\BallchasingLaravel\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Lucie\BallchasingLaravel\Contracts\BallchasingClientInterface;
use Lucie\BallchasingLaravel\DTOs\GroupCollection;
use Lucie\BallchasingLaravel\DTOs\GroupDetails;
use Lucie\BallchasingLaravel\DTOs\ReplayCollection;
use Lucie\BallchasingLaravel\DTOs\ReplayDetails;
use Lucie\BallchasingLaravel\Exceptions\BallchasingException;
use Psr\Http\Message\ResponseInterface;

class BallchasingClient implements BallchasingClientInterface
{
    private Client $httpClient;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://ballchasing.com/api',
        private readonly int $timeout = 30,
        private readonly int $retryAttempts = 3,
        private readonly int $retryDelay = 500,
        ?Client $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => rtrim($this->baseUrl, '/') . '/',
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization' => $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getReplays(array $filters = []): ReplayCollection
    {
        $response = $this->makeRequest('GET', 'replays', [
            'query' => $filters
        ]);

        return ReplayCollection::fromArray($response);
    }

    public function getReplay(string $replayId): ReplayDetails
    {
        $response = $this->makeRequest('GET', "replays/{$replayId}");

        return ReplayDetails::fromArray($response);
    }

    public function uploadReplay(string $filePath, array $metadata = []): ReplayDetails
    {
        if (!file_exists($filePath)) {
            throw BallchasingException::fileNotFound($filePath);
        }

        $response = $this->makeRequest('POST', 'replays', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
                ...array_map(fn($key, $value) => [
                    'name' => $key,
                    'contents' => $value,
                ], array_keys($metadata), $metadata)
            ]
        ]);

        return ReplayDetails::fromArray($response);
    }

    public function deleteReplay(string $replayId): bool
    {
        $this->makeRequest('DELETE', "/replays/{$replayId}");

        return true;
    }

    public function updateReplay(string $replayId, array $data): ReplayDetails
    {
        $response = $this->makeRequest('PATCH', "/replays/{$replayId}", [
            'json' => $data
        ]);

        return ReplayDetails::fromArray($response);
    }

    public function getGroups(array $filters = []): GroupCollection
    {
        $response = $this->makeRequest('GET', 'groups', [
            'query' => $filters
        ]);

        return GroupCollection::fromArray($response);
    }

    public function getGroup(string $groupId): GroupDetails
    {
        $response = $this->makeRequest('GET', "groups/{$groupId}");

        return GroupDetails::fromArray($response);
    }

    public function createGroup(array $data): GroupDetails
    {
        $response = $this->makeRequest('POST', 'groups', [
            'json' => $data
        ]);

        return GroupDetails::fromArray($response);
    }

    public function updateGroup(string $groupId, array $data): GroupDetails
    {
        $response = $this->makeRequest('PATCH', "/groups/{$groupId}", [
            'json' => $data
        ]);

        return GroupDetails::fromArray($response);
    }

    public function deleteGroup(string $groupId): bool
    {
        $this->makeRequest('DELETE', "/groups/{$groupId}");

        return true;
    }

    public function getMaps(): array
    {
        return $this->makeRequest('GET', 'maps');
    }

    private function makeRequest(string $method, string $endpoint, array $options = []): array
    {
        $attempts = 0;

        while ($attempts <= $this->retryAttempts) {
            try {
                $response = $this->httpClient->request($method, $endpoint, $options);

                return $this->handleResponse($response);
            } catch (ClientException $e) {
                if ($e->getResponse()->getStatusCode() === 401) {
                    throw BallchasingException::invalidApiKey();
                }

                throw BallchasingException::apiRequestFailed(
                    $this->extractErrorMessage($e->getResponse()),
                    $e->getResponse()->getStatusCode()
                );
            } catch (ServerException $e) {
                $attempts++;

                if ($attempts > $this->retryAttempts) {
                    throw BallchasingException::apiRequestFailed(
                        'Server error after maximum retries',
                        $e->getResponse()->getStatusCode()
                    );
                }

                usleep($this->retryDelay * 1000);
            } catch (GuzzleException $e) {
                throw BallchasingException::apiRequestFailed($e->getMessage());
            }
        }

        throw BallchasingException::apiRequestFailed('Maximum retry attempts exceeded');
    }

    private function handleResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw BallchasingException::invalidResponse(json_last_error_msg());
        }

        return $data;
    }

    private function extractErrorMessage(ResponseInterface $response): string
    {
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return "HTTP {$response->getStatusCode()}: {$body}";
        }

        return $data['error'] ?? $data['message'] ?? "HTTP {$response->getStatusCode()}: {$body}";
    }
}