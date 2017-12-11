<?php

namespace Drupal\audit_log\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Audit log entities.
 */
class AuditLogViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Custom.
    return $data;
  }

}
