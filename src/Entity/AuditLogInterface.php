<?php

namespace Drupal\audit_log\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Audit log entities.
 *
 * @ingroup audit_log
 */
interface AuditLogInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Audit log name.
   *
   * @return string
   *   Name of the Audit log.
   */
  public function getName();

  /**
   * Sets the Audit log name.
   *
   * @param string $name
   *   The Audit log name.
   *
   * @return \Drupal\audit_log\Entity\AuditLogInterface
   *   The called Audit log entity.
   */
  public function setName($name);

  /**
   * Gets the Audit log creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Audit log.
   */
  public function getCreatedTime();

  /**
   * Sets the Audit log creation timestamp.
   *
   * @param int $timestamp
   *   The Audit log creation timestamp.
   *
   * @return \Drupal\audit_log\Entity\AuditLogInterface
   *   The called Audit log entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Audit log published status indicator.
   *
   * Unpublished Audit log are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Audit log is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Audit log.
   *
   * @param bool $published
   *   TRUE to set this Audit log to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\audit_log\Entity\AuditLogInterface
   *   The called Audit log entity.
   */
  public function setPublished($published);

}
