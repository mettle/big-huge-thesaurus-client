<?php

namespace Mettleworks\BigHugeThesaurusClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use Mettleworks\BigHugeThesaurusClient\Exceptions\InactiveKeyException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\MissingWordsException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\NotFoundException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\NotWhitelistedException;
use Mettleworks\BigHugeThesaurusClient\Exceptions\UsageExceededException;

class BigHugeThesaurusClientTest extends TestCase
{
    protected $mockHandler;
    protected $httpClient;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        $this->httpClient = new Client([
            'handler' => $this->mockHandler,
        ]);
    }

    /** @test */
    public function itShouldReturnTheBugHugeThesaurusApiBaseUrl()
    {
        $api_key = 'my_very_random_key';

        $apiClient = $this->createClient($api_key);

        $this->assertEquals("https://words.bighugelabs.com/api/2/{$api_key}/", $apiClient->getBaseUri());
    }

    /** @test */
    public function itShouldAutomaticallySetAnInstanceOfGuzzleClientIfNotProvided()
    {
        $apiClient = new BigHugeThesaurusClient('123');
        $apiClient->setHttpClient(null);

        $this->assertInstanceOf(Client::class, $apiClient->getHttpClient());
    }

    /** @test */
    public function itShouldThrowASpecificExceptionIfTheProvidedWordIsNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->mockHandler->append($this->getClientException(404, 'Not Found'));

        $apiClient = $this->createClient('123');

        $apiClient->lookup('love');
    }

    /** @test */
    public function itShouldThrowASpecificExceptionIfTheKeyIsEmpty()
    {
        $this->expectException(InactiveKeyException::class);

        $this->mockHandler->append($this->getServerException(500, InactiveKeyException::REASON));

        $apiClient = $this->createClient('');

        $apiClient->lookup('love');
    }

    /** @test */
    public function itShouldThrowASpecificExceptionIfTheUserExceedsTheUsage()
    {
        $this->expectException(UsageExceededException::class);

        $this->mockHandler->append($this->getServerException(500, UsageExceededException::REASON));

        $apiClient = $this->createClient('123');

        $apiClient->lookup('love');
    }

    /** @test */
    public function itShouldThrowASpecificExceptionIfTheUserSubmitsNoWords()
    {
        $this->expectException(MissingWordsException::class);

        $this->mockHandler->append($this->getServerException(500, MissingWordsException::REASON));

        $apiClient = $this->createClient('123');

        $apiClient->lookup('');
    }

    /** @test */
    public function itShouldThrowASpecificExceptionIfTheIpAddressOfTheUserIsBlocked()
    {
        $this->expectException(NotWhitelistedException::class);

        $this->mockHandler->append($this->getServerException(500, NotWhitelistedException::REASON));

        $apiClient = $this->createClient('123');

        $apiClient->lookup('love');
    }

    /** @test */
    public function itShouldThrowAGuzzleExceptionIfTheErrorIsGeneric()
    {
        $this->expectException(GuzzleException::class);

        $this->mockHandler->append($this->getServerException(500, 'Generic error'));

        $apiClient = $this->createClient('123');

        $apiClient->lookup('love');
    }

    /** @test */
    public function itShouldProvideTheThesaurusResponseIfTheLookupSucceeds()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/fixtures/love.json')));

        $apiClient = $this->createClient('123');

        $response = $apiClient->lookup('love');

        $this->assertInstanceOf(ThesaurusResponse::class, $response);
    }

    /** @test */
    public function itShouldReturnTheRawResponseFromTheClient()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/fixtures/love.json')));

        $apiClient = $this->createClient('123');

        $apiClient->lookup('love');

        $this->assertIsString($apiClient->getRawResponse());
    }

    /** @test */
    public function itShouldReturnOnlyTheSynonymsOfWord()
    {
        $source = file_get_contents(__DIR__ . '/fixtures/love.json');

        $this->mockHandler->append(new Response(200, [], $source));

        $apiClient = $this->createClient('123');

        $synonyms = $apiClient->synonymsOf('love');

        $decoded = json_decode($source, true);

        $this->assertIsArray($synonyms);
        $this->assertEquals(array_merge($decoded['noun']['syn'], $decoded['verb']['syn']), $synonyms);
    }

    /** @test */
    public function itShouldReturnOnlyTheAntonymsOfWord()
    {
        $source = file_get_contents(__DIR__ . '/fixtures/love.json');

        $this->mockHandler->append(new Response(200, [], $source));

        $apiClient = $this->createClient('123');

        $antonyms = $apiClient->antonymsOf('love');

        $decoded = json_decode($source, true);

        $this->assertIsArray($antonyms);
        $this->assertEquals(array_merge($decoded['noun']['ant'], $decoded['verb']['ant']), $antonyms);
    }

    /** @test */
    public function itShouldReturnOnlyTheSimilarTermsOfWord()
    {
        $source = file_get_contents(__DIR__ . '/fixtures/love.json');

        $this->mockHandler->append(new Response(200, [], $source));

        $apiClient = $this->createClient('123');

        $similarTerms = $apiClient->similarTermsOf('love');

        $this->assertIsArray($similarTerms);
        $this->assertEmpty($similarTerms);
    }

    /** @test */
    public function itShouldReturnOnlyTheRelatedTermsOfWord()
    {
        $source = file_get_contents(__DIR__ . '/fixtures/love.json');

        $this->mockHandler->append(new Response(200, [], $source));

        $apiClient = $this->createClient('123');

        $relatedTerms = $apiClient->relatedTermsOf('love');

        $this->assertIsArray($relatedTerms);
        $this->assertEmpty($relatedTerms);
    }

    /**
     * Create the BigHugeThesaurusClient instance
     * @param string $key
     * @return BigHugeThesaurusClient
     */
    protected function createClient(string $key)
    {
        $apiClient = new BigHugeThesaurusClient($key);
        $apiClient->setHttpClient($this->httpClient);

        return $apiClient;
    }

    /**
     * Provide a Guzzle ClientException
     * @param $code
     * @param string $reason
     * @return ClientException
     */
    protected function getClientException($code, string $reason)
    {
        return new ClientException(
            $reason,
            new Request('GET', '/'),
            new Response($code, [], null, '1.1', $reason)
        );
    }

    /**
     * Provide a Guzzle ServerException
     * @param $code
     * @param string $reason
     * @return ServerException
     */
    protected function getServerException($code, string $reason)
    {
        return new ServerException(
            $reason,
            new Request('GET', '/'),
            new Response($code, [], null, '1.1', $reason)
        );
    }
}
