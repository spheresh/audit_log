<?php

/**
 * @file
 */

use Audit\Controller;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

require __DIR__ . '/vendor/autoload.php';

$controller = new Controller();

(new Application('echo', '1.0.0'))
  ->register('echo')
  ->addArgument('url', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The site url.')
  ->addOption('from', NULL, InputOption::VALUE_REQUIRED, 'Start date/time', strtotime("-1 day"))
  ->addOption('to', NULL, InputOption::VALUE_REQUIRED, 'End date/time', time())
  ->addOption('debug', NULL, InputOption::VALUE_NONE)
  ->setCode(function (InputInterface $input, OutputInterface $output) use ($controller) {
    $controller->run($input, $output);
  })
  ->getApplication()
// Single command application.
  ->setDefaultCommand('echo', TRUE)
  ->run();
