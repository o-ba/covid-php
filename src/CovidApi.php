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

    /** @var string */
    protected $endpoint = '';

    /** @var float */
    protected $timeout;

    /** @var string[] */
    protected $sources = [];

    public function __construct(string $endpoint = '', float $timeout = 5.0)
    {
        $this->endpoint = $endpoint ?: static::ENDPOINT;
        $this->timeout = $timeout;
    }

    public function getSources(): array
    {
        if ($this->sources === []) {
            $this->sources = $this->request('sources')['sources'] ?? [];
        }

        return $this->sources;
    }

    public function getLatest(): array
    {
        return $this->request('latest');
    }

    public function getAllLocations(bool $includeTimelines = false, string $source = ''): array
    {
        return $this->request('locations', $this->getQueryParameters($includeTimelines, $source));
    }

    public function findByCountryCode(string $countryCode, bool $includeTimelines = false, string $source = ''): array
    {
        return $this->request('locations', $this->getQueryParameters($includeTimelines, $source, $countryCode));
    }

    public function findByLocation(int $locationId, bool $includeTimelines = false, string $source = ''): array
    {
        return $this->request('locations/' . $locationId, $this->getQueryParameters($includeTimelines, $source));
    }

    protected function getQueryParameters(
        bool $includeTimelines = false,
        string $source = '',
        string $countryCode = ''
    ): array {
        $queryParameters['timelines'] = $includeTimelines;

        if ($source !== '' && \in_array($source, $this->getSources(), true)) {
            $queryParameters['source'] = $source;
        }

        if ($countryCode !== '') {
            $queryParameters['country_code'] = \strtoupper(\substr(\trim($countryCode), 0, 2));
        }

        return ['query' => $queryParameters];
    }

    protected function request(string $uri, array $options = []): array
    {
        $client = new Client([
            'base_uri' => $this->endpoint,
            'timeout' => $this->timeout,
        ]);

        try {
            $response = $client->request('GET', $uri, $options);
        } catch (GuzzleException $e) {
            return [];
        }

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        return \json_decode($response->getBody()->getContents() ?? '', true) ?? [];
    }
}