Web Crawler using Laravel Dusk
===================

[![tests](https://github.com/katsana/dusk-crawler/workflows/tests/badge.svg?branch=master)](https://github.com/katsana/dusk-crawler/actions?query=workflow%3Atests+branch%3Amaster)
[![Latest Stable Version](https://poser.pugx.org/katsana/dusk-crawler/v/stable)](https://packagist.org/packages/katsana/dusk-crawler)
[![Total Downloads](https://poser.pugx.org/katsana/dusk-crawler/downloads)](https://packagist.org/packages/katsana/dusk-crawler)
[![Latest Unstable Version](https://poser.pugx.org/katsana/dusk-crawler/v/unstable)](https://packagist.org/packages/katsana/dusk-crawler)
[![License](https://poser.pugx.org/katsana/dusk-crawler/license)](https://packagist.org/packages/katsana/dusk-crawler)

**Laravel Dusk** enables developer to run browser automation but it does lack the ability to navigate actions based on response received on the browser. If you need to handle failure you have to wait until the timeout expired and handle the generic exception.

**Dusk Crawler** solve this by adding `inspectUsing()` method to allow developer to inspect for success or fail status using [Promise by ReactPHP](https://github.com/reactphp/promise).

## Installation

Dusk Crawler can be installed via composer:

    composer require "katsana/dusk-crawler"

### Usages

**Dusk Crawler** just add two new macros to `Laravel\Dusk\Browser`:

| Method           | Description 
|:-----------------|:------------- 
| `inspectUsing()` | Similar to `waitUsing()` but allow you to either return `abort(string $exception)`/`reject(\Throwable $throwable)` (fail) or `resolve()` (success) state based on available elements or texts on the browser.
| `crawler`        | Return an instance of `Symfony\Component\DomCrawler\Crawler` to allow developer to crawl the result.

#### Example

Let say you want to crawl Packagist to search for some package, and the input is dynamic. 

```php
use DuskCrawler\Dusk;
use DuskCrawler\Inspector;
use DuskCrawler\Exceptions\InspectionFailed;
use Laravel\Dusk\Browser;

function searchPackagist(string $packagist) {
    $dusk = new Dusk('search-packagist');

    $dusk->headless()->disableGpu()->noSandbox();
    $dusk->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

    $dusk->start();

    $dusk->browse(function ($browser) use ($packagist) {
        $browser->visit('https://packagist.org/');

        $promise = $browser->type('search_query[query]', $packagist, '{enter}')
            ->inspectUsing(15, function (Browser $browser, Inspector $inspector) {
                $searchList = $browser->resolver->findOrFail('.search-list');

                if (! $searchList->isDisplayed() || $searchList->getText() == '') {
                    // result not ready, just return false.
                    return false;
                }

                if ($searchList->getText() == 'No packages found.') {
                    return $inspector->abort('No packages found!');
                }

                return $inspector->resolve();
            });

        $promise->then(function ($browser) {
            // Crawl the page on success.
            $packages = $browser->crawler()
              ->filter('div.package-item')->each(function ($div) {
                return $div->text();
            });
      
            dump($packages);
        })->otherwise(function (InspectionFailed $exception) {
            // Handle abort state.
            dump("No result");
        })->done();
    });

    $dusk->stop();
}

searchPackagist('dusk-crawler');

Dusk::closeAll();
```

