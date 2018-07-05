<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\process\commerce1;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Migrate commerce price from Commerce 1 to Commerce 2.
 *
 * @MigrateProcessPlugin(
 *   id = "commerce1_migrate_commerce_price"
 * )
 */
class CommercePrice extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $new_value = [
      'number' => bcdiv($value['amount'], bcpow(10, $value['fraction_digits']), $value['fraction_digits']),
      'currency_code' => $value['currency_code'],
    ];
    return $new_value;
  }

}