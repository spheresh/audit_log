<?php

namespace Drupal\audit_log_rest\Plugin\rest\resource;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "audit_log_rest",
 *   label = @Translation("Audit log rest resource"),
 *   uri_paths = {
 *     "canonical" = "/auditlog/v1"
 *   }
 * )
 */
class AuditLogRestResource extends ResourceBase {

  /**
   * Status success.
   */
  const STATUS_SUCCESS = 1;

  /**
   * Default entity type.
   */
  const ENTITY_TYPE = 'audit_log';

  /**
   * Entity storage definition.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * Constructs a new AuditLogRestResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    EntityManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->storage = $entity_manager->getStorage(self::ENTITY_TYPE);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('audit_log_rest'),
      $container->get('entity.manager')

    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get() {
    try {
      $request = \Drupal::request();
      $limit = $request->query->get('limit', 10);

      /** @var \Drupal\Core\Entity\Query\QueryInterface $ids */
      $query = $this->storage->getQuery();
      $query->addTag('audit_log_rest');

      if ($request->query->has('from')) {
        $query->condition('created', $request->query->get('from'), '>=');
      }
      if ($request->query->has('to')) {
        $query->condition('created', $request->query->get('to'), '<=');
      }

      $query->pager($limit);

      /** @var \Drupal\Core\Database\Driver\mysql\Select $select */
      $rows = $query->execute();

      $entities = $this->storage->loadMultiple($rows);
    }
    catch (\Exception $e) {
      return new ResourceResponse([
        'error' => $e->getCode(),
        'message' => $e->getMessage(),
      ], 500);
    }
    return new ResourceResponse([
      'status' => self::STATUS_SUCCESS,
      'limit' => $limit,
      'entities' => $entities,
    ], 200);
  }

}
