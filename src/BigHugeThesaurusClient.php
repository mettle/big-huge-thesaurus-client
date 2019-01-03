<?php

namespace Mettleworks\BigHugeThesaurusClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\NotFoundException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\InactiveKeyException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\MissingWordsException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\NotWhitelistedException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\UsageExceededException;

/**
 * Class BugHugeThesaurusClient
 *
 * The main class for API consumption
 *
 * @package Mettleworks\BigHugeThesaurusClient
 */
class BigHugeThesaurusClient
{
    /**
     * Client
     *
     * @var Client
     */
    protected $client;

    /**
     * API key
     *
     * @var string
     */
    protected $key;

    /**
     * Base URI
     *
     * @var string
     */
    protected $base_uri = 'https://words.bighugelabs.com/api/2/{key}/';

    /**
     * Raw response
     *
     * @var string
     */
    protected $rawResponse;

    /**
     * Remote exceptions to handle
     * @var array
     */
    protected $remoteExceptions = [
        InactiveKeyException::REASON => InactiveKeyException::class,
        MissingWordsException::REASON => MissingWordsException::class,
        NotWhitelistedException::REASON => NotWhitelistedException::class,
        UsageExceededException::REASON => UsageExceededException::class
    ];

    /**
     * Create a new instance of the client
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Sets the client to be used for querying the API endpoints
     *
     * @param Client $client
     * @return $this
     */
    public function setHttpClient(Client $client = null)
    {
        if ($client === null) {
            $client = new Client([
                'base_uri' => $this->getBaseUri(),
                'http_errors' => true
            ]);
        }

        $this->client = $client;

        return $this;
    }

    /**
     * Returns either the instance of the Guzzle client that has been defined, or null
     * @return Client|null
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * Return the base uri of the API
     * @return mixed
     */
    public function getBaseUri()
    {
        return str_replace('{key}', $this->key, $this->base_uri);
    }

    /**
     * Lookup the word on Big Huge Thesaurus
     * @param string $word
     * @return ThesaurusResponse
     * @throws NotFoundException
     * @throws RemoteException
     * @throws GuzzleException
     * @see    https://words.bighugelabs.com/api.php
     */
    public function lookup(string $word)
    {
        try {
            if (!$this->getHttpClient()) {
                $this->setHttpClient();
            }

            $response = $this->client->request('GET', $word . '/json');

            $this->rawResponse = $response->getBody()->getContents();

            return $this->parseResponse($this->rawResponse);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 404) {
                throw new NotFoundException();
            }

            throw $e;
        } catch (ServerException $e) {
            if ($e->getResponse()->getStatusCode() == 500 and in_array($e->getResponse()->getReasonPhrase(), array_keys($this->remoteExceptions))) {
                throw new $this->remoteExceptions[$e->getResponse()->getReasonPhrase()]();
            }

            throw $e;
        }
    }

    /**
     * Get only synonyms of word
     *
     * @param  string $word
     * @throws NotFoundException
     * @throws RemoteException
     * @throws GuzzleException
     * @return array
     */
    public function synonymsOf(string $word): array
    {
        $response = $this->lookup($word);

        return $response->getSynonyms();
    }

    /**
     * Get only antonyms of word
     *
     * @param  string $word
     * @throws NotFoundException
     * @throws RemoteException
     * @throws GuzzleException
     * @return array
     */
    public function antonymsOf(string $word): array
    {
        $response = $this->lookup($word);

        return $response->getAntonyms();
    }

    /**
     * Get only similar terms of word
     *
     * @param  string $word
     * @throws NotFoundException
     * @throws RemoteException
     * @throws GuzzleException
     * @return array
     */
    public function similarTermsOf(string $word): array
    {
        $response = $this->lookup($word);

        return $response->getSimilarTerms();
    }

    /**
     * Get only related terms of word
     *
     * @param  string $word
     * @throws NotFoundException
     * @throws RemoteException
     * @throws GuzzleException
     * @return array
     */
    public function relatedTermsOf(string $word): array
    {
        $response = $this->lookup($word);

        return $response->getRelatedTerms();
    }

    /**
     * Get the raw response
     *
     * @return ThesaurusResponse
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * Parse the response into a ThesaurusResponse
     *
     * @param  string $response
     * @return ThesaurusResponse
     */
    protected function parseResponse($response)
    {
        $parsed = json_decode($response, true);

        return new ThesaurusResponse($parsed);
    }
}
