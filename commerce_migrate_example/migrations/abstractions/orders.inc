<?php
/**
 * @file
 * Represents "orders.csv".
 */

/**
 * Class CommerceMigrateExampleOrdersCsv.
 */
abstract class CommerceMigrateExampleOrdersCsv extends \CommerceMigrateExampleMigration {

  const FILE = 'orders.csv';

  /**
   * {@inheritdoc}
   */
  public static function csvColumns() {
    return array(
      'order_number' => 'Number',
      'status' => 'Number',
      'mail' => 'Number',
      'hostname' => 'Hostname',
    );
  }

}
