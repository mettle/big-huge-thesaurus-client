# BigHugetThesaurusClient

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

PHP Wrapper for the [Big Huge Labs Thesaurus API](http://words.bighugelabs.com/).

## Install

Via Composer

``` bash
$ composer require mettleworks/big-huge-thesaurus-client
```

## Usage

``` php
$API_KEY = 'Get an api key @ https://words.bighugelabs.com/api.php';

$client = new Mettleworks\BigHugeThesaurusClient($API_KEY);
$response = $client->lookup('love');
```

The response object is an instance of `ThesaurusResponse`, an object that wraps the original response and adds a few convenience methods. You can optionally pass an argument to each method to indicate if you want only nouns (`noun`), verbs (`verb`), adjectives (`adjective`) or adverbs (`adverb`) otherwise they will return all results combined. The signature of the methods is the following:

```php
/**
 * Get the synonyms from the response
 */
$response->getSynonyms($type = null): array

/**
 * Get the antonyms from the response
 */
public function getAntonyms($type = null): array

/**
 * Get the similar terms from the response
 */
public function getSimilarTerms($type = null): array

/**
 * Get related terms from the response
 */
public function getRelatedTerms($type = null): array

/**
 * Cast response to array (returns the original response)
 */ 
public function toArray(): array

/**
 * Cast response to JSON
 */
public function toJson()
```

The client throws specific exceptions when an error occurs. Those errors are documented on the [Big Huge Thesaurus Api Page](https://words.bighugelabs.com/api.php).

```php
try {
    $response = $client->lookup('love');
} catch (NotFoundException $ex) {
    // Not Found
} catch (UsageExceededException $ex) {
    // Usage exceeded
} catch (InactiveKeyException $ex) {
    // Key not active
} catch (MissingWordsException $ex) {
    // No words provided
} catch (NotWhitelistedException $ex) {
    // IP address blocked
}
```

Additionally if an error occurs outside the listed ones, the original Guzzle Exception is thrown.
 
## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email maurizio@mettle.io instead of using the issue tracker.

## Credits

- [Maurizio Bonani](https://github.com/mauricius)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.