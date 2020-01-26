<?php

namespace Laravie\DuskCrawler;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\Chrome\SupportsChrome;
use Laravel\Dusk\Concerns\ProvidesBrowser;

class Dusk
{
    use ProvidesBrowser,
        SupportsChrome;

    /**
     * A list of remote web driver arguments.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $arguments;

    /**
     * Set the maximum time of a request to remote WebDriver server.
     *
     * @var int
     */
    protected $requestTimeout;

    /**
     * Set timeout for the connect phase to remote WebDriver server in ms.
     *
     * @var int
     */
    protected $connectTimeout;

    /**
     * Initialises the dusk browser and starts the chrome driver.
     *
     * @return void
     */
    public function __construct()
    {
        $this->arguments = Collection::make();
        $this->setRequestTimeout(30000);
        $this->setConnectTimeout(30000);
    }

    /**
     * Start the browser.
     *
     * @return $this
     */
    public function start()
    {
        static::startChromeDriver();

        return $this;
    }

    /**
     * Stop the browser.
     *
     * @return $this
     */
    public function stop()
    {
        try {
            $this->closeAll();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            static::stopChromeDriver();
            return $this;
        }
    }

    /**
     * Set the request timeout.
     *
     * @return $this
     */
    public function setRequestTimeout(int $timeout)
    {
        $this->requestTimeout = $timeout;

        return $this;
    }

    /**
     * Set the connect timeout.
     *
     * @return $this
     */
    public function setConnectTimeout(int $timeout)
    {
        $this->connectTimeout = $timeout;

        return $this;
    }

    /**
     * Run the browser in headless mode.
     *
     * @return $this
     */
    public function headless()
    {
        return $this->addArgument('--headless');
    }

    /**
     * Disable the browser using gpu.
     *
     * @return $this
     */
    public function disableGpu()
    {
        return $this->addArgument('--disable-gpu');
    }

    /**
     * Disable the sandbox.
     *
     * @return $this
     */
    public function noSandbox()
    {
        return $this->addArgument('--no-sandbox');
    }

    /**
     * Set the initial browser window size.
     *
     * @param $width The browser width in pixels.
     * @param $height The browser height in pixels.
     * @return $this
     */
    public function windowSize(int $width, int $height)
    {
        return $this->addArgument('--window-size=' . $width . ',' . $height);
    }

    /**
     * Set the user agent.
     *
     * @param $useragent The user agent to use.
     * @return $this
     */
    public function userAgent(string $useragent)
    {
        return $this->addArgument('--user-agent=' . $useragent);
    }

    /**
     * Add a browser option.
     *
     * @return $this
     */
    protected function addArgument(string $argument)
    {
        if ($this->arguments->contains($argument)) {
            return;
        }

        $this->arguments->push($argument);

        return $this;
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions())->addArguments($this->arguments->toArray());

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            ),
            $this->connectTimeout,
            $this->requestTimeout
        );
    }
}
