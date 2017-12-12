<?php

namespace Drupal\audit_log\StorageBackend;

use Drupal\audit_log\AuditLogEventInterface;
use Drupal\audit_log\Entity\AuditLog;

/**
 * Writes audit events to a custom database table.
 *
 * @package Drupal\audit_log\StorageBackend
 */
class Database implements StorageBackendInterface {

  /**
   * {@inheritdoc}
   */
  public function save(AuditLogEventInterface $event) {
    $log = AuditLog::create([
      'entity_id' => $event->getEntity()->id(),
      'entity_type' => $event->getEntity()->getEntityTypeId(),
      'event' => $event->getEventType(),
      'previous_state' => $event->getPreviousState(),
      'current_state' => $event->getCurrentState(),
      'message' => $event->getMessage(),
      'variables' => serialize($event->getMessagePlaceholders()),
    ])->save();
  }

}
