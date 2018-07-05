<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\commerce1;

use Drupal\Tests\migrate_drupal\Kernel\d7\MigrateDrupal7TestBase;
use Drupal\migrate\MigrateExecutable;

/**
 * Base class for Commerce 1 migration tests.
 */
abstract class Commerce1TestBase extends MigrateDrupal7TestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'action',
    'profile',
    'address',
    'entity',
    'entity_reference_revisions',
    'inline_entity_form',
    'state_machine',
    'text',
    'views',
    'commerce',
    'commerce_price',
    'commerce_store',
    'commerce_order',
    'commerce_migrate',
    'commerce_migrate_commerce',
  ];

  /**
   * Gets the path to the fixture file.
   */
  protected function getFixtureFilePath() {
    return __DIR__ . '/../../../../fixtures/ck2.php';
  }

  /**
   * Executes store migrations.
   */
  protected function migrateStore() {
    $this->installEntitySchema('commerce_store');
    $this->executeMigrations([
      'd7_filter_format',
      'd7_user_role',
      'd7_user',
      'commerce1_currency',
      'commerce1_store',
      'commerce1_default_store',
    ]);
  }

  /**
   * Executes rollback on a single migration.
   *
   * @param string|\Drupal\migrate\Plugin\MigrationInterface $migration
   *   The migration to rollback, or its ID.
   */
  protected function executeRollback($migration) {
    if (is_string($migration)) {
      $this->migration = $this->getMigration($migration);
    }
    else {
      $this->migration = $migration;
    }
    (new MigrateExecutable($this->migration, $this))->rollback();
  }

}