<?php

namespace Audit;

use Audit\Sites\Site;
use Audit\Sites\SiteData;
use Buzz\Client\Curl;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @file
 * Client class implementation.
 */

/**
 * Client class.
 */
class Client {

  /**
   * Curl client definition.
   *
   * @var \Buzz\Client\Curl
   */
  protected $curl;

  protected $options;

  /**
   * Client constructor.
   */
  public function __construct(InputInterface $input, array $options = []) {
    $this->curl = new Curl();
    $debug = $input->getOption('debug');

    // Default curl options.
    $this->options = $options + [
      CURLOPT_VERBOSE => $debug,
      CURLOPT_SSL_VERIFYHOST => !$debug,
      CURLOPT_SSL_VERIFYPEER => !$debug,
    ];
  }

  /**
   * Grab site.
   *
   * @param \Audit\Sites\Site $site
   * @param \Closure $page_process
   * @param \Closure $finish
   *
   * @throws \Exception
   */
  public function grab(Site $site, \Closure $page_process, \Closure $finish) {
    do {

      $request = new Request('GET', $site->getUrl());

      $response = $this->curl->sendRequest($request, $this->options);

      $site_data = new SiteData($response);

      if ($site_data->getStatusCode() != 200) {
        throw new \Exception('Incorrect status code', 1);
      }

      if (!$site_data->jsonStatus()) {
        throw new \Exception('Structure of json is incorrect', 2);
      }
      $page_process($site_data);

    } while ($site_data->nextPage());

    $finish();
  }

}
