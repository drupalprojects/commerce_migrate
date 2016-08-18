<?php
/**
 * @file
 * Represents "products.csv".
 */

/**
 * Class CommerceMigrateExampleProductsCsv.
 */
abstract class CommerceMigrateExampleProductsCsv extends \CommerceMigrateExampleMigration {

  const FILE = 'products.csv';

  /**
   * {@inheritdoc}
   */
  public static function csvColumns() {
    return array(
      'sku' => 'SKU',
      'title' => 'Title',
      'price' => 'Price',
      'currency_code' => 'Currency code',
      'image' => 'Image',
      'description' => 'Description',
    );
  }

}
