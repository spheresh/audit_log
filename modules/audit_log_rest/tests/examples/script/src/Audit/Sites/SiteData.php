<?php

namespace Audit\Sites;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * SiteData class.
 */
class SiteData {

  /**
   * Curl response.
   *
   * @var \Psr\Http\Message\ResponseInterface
   */
  protected $response;

  /**
   * Data storage.
   *
   * @var mixed
   */
  protected $data;

  /**
   * SiteData constructor.
   */
  public function __construct(ResponseInterface $responce) {
    $this->response = $responce;

  }

  /**
   * Gets a HTML status code.
   */
  public function getStatusCode() {
    return $this->response->getStatusCode();
  }

  /**
   * Check a REST status.
   */
  public function jsonStatus() {

    $json = new JsonEncoder();
    $this->data = $json->decode($this->response->getBody(), 'json');
    if (!isset($this->data['status']) || !isset($this->data['limit']) || !isset($this->data['entities'])) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Check amount of entities.
   *
   * The amount that a resource can give at one time is limited.
   * When this limit is reached, we send another request.
   *
   * @return bool
   */
  public function nextPage() {
    return $this->data['limit'] == count($this->data['entities']);
  }

  /**
   * Create a tml file with content.
   *
   * @TODO Delete this file.
   *
   * @return bool|string
   */
  public function getContent() {
    $csvh = fopen($tmp = tempnam('/tmp', 'content.txt'), 'w');
    $d = ',';
    $e = '"';
    foreach ($this->data['entities'] as $result) {
      fputcsv($csvh, $result, $d, $e);
    };
    fclose($csvh);
    return file_get_contents($tmp);
  }

}
