<?php

namespace Drupal\audit_log_rest\Normalizer;

use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Normalizes/denormalizes Drupal config entity objects into an array structure.
 */
class AuditLogNormalizer extends NormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\audit_log\Entity\AuditLog'];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \Drupal\audit_log\Entity\AuditLog $object */
    return [
      'id' => $object->id(),
      'uuid' => $object->uuid(),
      'user_id' => $object->getOwnerId(),
      'entity_id' => $object->entity_id->value,
      'event' => $object->event->value,
      'previous_state' => $object->previous_state->value,
      'current_state' => $object->current_state->value,
      'message' => $object->message->value,
      'created' => $object->created->value,
      'changed' => $object->changed->value,
    ];
  }

}
