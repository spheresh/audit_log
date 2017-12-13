<?php

namespace Audit\Sites;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Site class.
 */
class Site {

  /**
   * Current page.
   *
   * @var int
   */
  public $page = 0;

  /**
   * Site url.
   *
   * @var string
   */
  public $url;

  /**
   * Console input.
   *
   * @var \Symfony\Component\Console\Input\InputInterface
   */
  protected $input;

  /**
   * Site constructor.
   *
   * @param $url
   * @param \Symfony\Component\Console\Input\InputInterface $input
   *
   * @internal param string $string
   */
  public function __construct($url, InputInterface $input) {
    $this->url = $url;
    $this->input = $input;
  }

  /**
   * Increase page number.
   */
  public function nextPage() {
    return $this->page++;
  }

  /**
   * Gets a site url with query.
   */
  public function getUrl() {

    $option = [];
    $option['_format'] = 'json';
    $option['from'] = $this->input->getOption('from');
    $option['to'] = $this->input->getOption('to');
    $option['page'] = $this->nextPage();
    $query = http_build_query($option);

    return $this->url . '/auditlog/v1?' . $query;
  }

}
