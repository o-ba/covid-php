<?php
declare(strict_types=1);

namespace Bo\CovidPHP;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class CovidApi provides a simple API wrapper for the coronavirus tracking API
 *
 * @see https://github.com/ExpDev07/coronavirus-tracker-api
 * @author Oliver Bartsch <bo@cedev.de>
 */
class CovidApi
{
    private const ENDPOINT = 'https://coronavirus-tracker-api.herokuapp.com/v2/';
    private const AVAILABLE_SOURCES = ['jhu', 'csbs'];

    /** @var string */
    protected $endpoint = '';

    /** @var int */
    protected $timeout;

    public function __construct(string $endpoint = '', int $timeout = 20)
    {
        $this->endpoint = $endpoint ?: static::ENDPOINT;
        $this->timeout = $timeout;
    }

    public function getLatest(): array
    {
        return $this->request('latest');
    }

    public function getAllLocations(bool $includeTimelines = false, string $source = ''): array
    {
        return $this->request('locations' . $this->getAdditionalParameters($includeTimelines, $source));
    }

    public function findByCountryCode(string $countryCode, bool $includeTimelines = false, string $source = ''): array
    {
        return $this->request('locations' . $this->getAdditionalParameters($includeTimelines, $source, $countryCode));
    }

    public function findByLocation(int $locationId, bool $includeTimelines = false, string $source = ''): array
    {
        return $this->request('locations/' . $locationId . $this->getAdditionalParameters($includeTimelines, $source));
    }

    protected function getAdditionalParameters(
        bool $includeTimelines = false,
        string $source = '',
        string $countryCode = ''
    ): string {
        $additionalParameters = [];

        if ($countryCode !== '') {
            $additionalParameters[] = 'country_code=' . \strtoupper(\trim($countryCode));
        }

        $additionalParameters[] = 'timelines=' . (int)$includeTimelines;

        if ($source !== '' && \in_array($source, static::AVAILABLE_SOURCES, true)) {
            $additionalParameters[] = 'source=' . $source;
        }

        return $additionalParameters !== [] ? '?' . \implode('&', $additionalParameters) : '';
    }

    protected function request(string $uri): array
    {
        $client = new Client([
            'base_uri' => $this->endpoint,
            'timeout' => $this->timeout,
        ]);

        try {
            $response = $client->request('GET', $uri);
        } catch (GuzzleException $e) {
            return [];
        }

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        return \json_decode($response->getBody()->getContents() ?? '', true) ?? [];
    }
}