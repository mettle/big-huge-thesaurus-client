<?php

namespace Mettleworks\BigHugeThesaurusClient;

use PHPUnit\Framework\TestCase;

class ThesaurusResponseTest extends TestCase
{
    /** @test */
    public function itShouldReturnOnlyTheSynonymsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEquals(array_merge($rawResponse['noun']['syn'], $rawResponse['verb']['syn']), $response->getSynonyms());
    }

    /** @test */
    public function itShouldReturnOnlyTheNounSynonymsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEquals($rawResponse['noun']['syn'], $response->getSynonyms('noun'));
    }

    /** @test */
    public function itShouldReturnOnlyTheVerbSynonymsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEquals($rawResponse['verb']['syn'], $response->getSynonyms('verb'));
    }

    /** @test */
    public function itShouldReturnOnlyTheAntonymsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEquals(array_merge($rawResponse['noun']['ant'], $rawResponse['verb']['ant']), $response->getAntonyms());
    }

    /** @test */
    public function itShouldReturnOnlyTheNounAntonymsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEquals($rawResponse['noun']['ant'], $response->getAntonyms('noun'));
    }

    /** @test */
    public function itShouldReturnOnlyTheVerbAntonymsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEquals($rawResponse['verb']['ant'], $response->getAntonyms('verb'));
    }

    /** @test */
    public function itShouldReturnOnlyTheSimilarTermsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/flat.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEquals($rawResponse['adjective']['sim'], $response->getSimilarTerms('adjective'));
    }

    /** @test */
    public function itShouldReturnOnlyTheUserSuggestedTermsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/flat.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEmpty($response->getRelatedTerms());
    }

    /** @test */
    public function itShouldReturnOnlyTheRelatedTermsFromTheResponse()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertEmpty($response->getRelatedTerms());
    }

    /** @test */
    public function itShouldReturnTheRawResponseWhenConvertingToArray()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertIsArray($response->toArray());
        $this->assertEquals($rawResponse, $response->toArray());
    }

    /** @test */
    public function itShouldConvertTheResponseBackToJson()
    {
        $rawResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/love.json'), true);

        $response = new ThesaurusResponse($rawResponse);

        $this->assertJson($response->toJson());
    }
}
