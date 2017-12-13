<?php

namespace Audit;

use Audit\Sites\Site;
use Audit\Sites\SiteData;
use Audit\Storage\Destination;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Controller class.
 */
class Controller {

  /**
   * Controller constructor.
   */
  public function __construct() {
  }

  /**
   * Gets a curl client.
   */
  private function getClient($input) {
    return new Client($input);
  }

  /**
   * Gets all sites that should be grabbed.
   *
   * @TODO Currently, we get site from console, but it can be changed to config.
   *
   * @param \Symfony\Component\Console\Input\InputInterface $input
   *
   * @return array
   */
  private function getSites(InputInterface $input) {
    $urls = $input->getArgument('url') ?: [];
    return array_map(function ($url) use ($input) {
      return new Site($url, $input);
    }, $urls);
  }

  /**
   * Init process of grabbing sites.
   *
   * @param \Symfony\Component\Console\Input\InputInterface $input
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   */
  public function run(InputInterface $input, OutputInterface $output) {

    foreach ((array) $this->getSites($input) as $site) {
      try {

        /** @var \Audit\Sites\Site $site */
        $this->getClient($input)
          ->grab($site, function (SiteData $site_data) use ($site, $output) {
            // Called for each site. It can be called several times
            // if there is more than one page.
            Destination::putContent($site_data->getContent());
          }, function () use ($site, $output) {
            // Called when all pages has been handled.
            $output->writeln(strtr('New logs were received from @site', [
              '@site' => (string) $site->url,
            ]));

          });

      }
      catch (\Exception $e) {
        // @TODO Deside how we can to handle this error.
      }
    }

  }

}
