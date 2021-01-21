<?php

namespace Mettleworks\BigHugeThesaurusClient;

class ThesaurusResponse
{
    /**
     * The Thesaurus response
     *
     * @var array
     */
    protected $response;

    /**
     * Constructor
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Get the synonyms from the response,
     * optionally filtered by type
     *
     * @param  mixed $type
     * @return array
     */
    public function getSynonyms($type = null): array
    {
        return $this->getEntities('Synonyms', $type);
    }

    /**
     * Get the antonyms from the response,
     * optionally filtered by type
     *
     * @param  mixed $type
     * @return array
     */
    public function getAntonyms($type = null): array
    {
        return $this->getEntities('Antonyms', $type);
    }

    /**
     * Get the simila terms from the response,
     * optionally filtered by type
     *
     * @param  mixed $type
     * @return array
     */
    public function getSimilarTerms($type = null): array
    {
        return $this->getEntities('SimilarTerms', $type);
    }

    /**
     * Get related terms from the response,
     * optionally filtered by type
     *
     * @param  mixed $type
     * @return array
     */
    public function getRelatedTerms($type = null): array
    {
        return $this->getEntities('RelatedTerms', $type);
    }

    /**
     * Get entities from the response
     *
     * @param  string $entity
     * @param  mixed  $type
     * @return array
     */
    protected function getEntities($entity, $type = null): array
    {
        $entities = [];

        if ($nouns = $this->extractNouns()) {
            $nounEntities = $this->{'extract' . $entity}($nouns);

            if ($type === 'noun') {
                return $nounEntities;
            }

            $entities = array_merge($entities, $nounEntities);
        }

        if ($verbs = $this->extractVerbs()) {
            $verbEntities = $this->{'extract' . $entity}($verbs);

            if ($type === 'verb') {
                return $verbEntities;
            }

            $entities = array_merge($entities, $verbEntities);
        }

        if ($adjectives = $this->extractAdjectives()) {
            $adjectiveEntities = $this->{'extract' . $entity}($adjectives);

            if ($type === 'adjective') {
                return $adjectiveEntities;
            }

            $entities = array_merge($entities, $adjectiveEntities);
        }

        if ($adverbs = $this->extractAdverbs()) {
            $adverbEntities = $this->{'extract' . $entity}($adverbs);

            if ($type === 'adverb') {
                return $adverbEntities;
            }

            $entities = array_merge($entities, $adverbEntities);
        }

        return $entities;
    }

    /**
     * Extract only the nouns from the response
     *
     * @return mixed
     */
    protected function extractNouns()
    {
        return $this->response['noun'] ?? null;
    }

    /**
     * Extract only the verbs from the response
     *
     * @return mixed
     */
    protected function extractVerbs()
    {
        return $this->response['verb'] ?? null;
    }

    /**
     * Extract only the adjectives from the response
     *
     * @return mixed
     */
    protected function extractAdjectives()
    {
        return $this->response['adjective'] ?? null;
    }

    /**
     * Extract only the adverbs from the response
     *
     * @return mixed
     */
    protected function extractAdverbs()
    {
        return $this->response['adverb'] ?? null;
    }

    /**
     * Extract synonyms from the entry
     *
     * @param  array $entry
     * @return array
     */
    protected function extractSynonyms(array $entry): array
    {
        return $entry['syn'] ?? [];
    }

    /**
     * Extract antonyms from the entry
     *
     * @param  array $entry
     * @return array
     */
    protected function extractAntonyms(array $entry): array
    {
        return $entry['ant'] ?? [];
    }

    /**
     * Extract similar terms from the entry
     *
     * @param  array $entry
     * @return array
     */
    protected function extractSimilarTerms(array $entry): array
    {
        return $entry['sim'] ?? [];
    }

    /**
     * Extract related terms from the entry
     *
     * @param  array $entry
     * @return array
     */
    protected function extractRelatedTerms(array $entry): array
    {
        return $entry['rel'] ?? [];
    }

    /**
     * Extract user suggestions from the entry
     *
     * @param  array $entry
     * @return array
     */
    protected function extractUserSuggestions(array $entry): array
    {
        return $entry['usr'] ?? [];
    }

    /**
     * Cast response to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * Cast response to JSON
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->response);
    }
}
