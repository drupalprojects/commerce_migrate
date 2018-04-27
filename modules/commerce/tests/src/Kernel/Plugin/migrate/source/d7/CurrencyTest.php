<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Plugin\migrate\source\d7;

use Drupal\Tests\migrate\Kernel\MigrateSqlSourceTestBase;

/**
 * Tests the d7 commerce currency source plugin.
 *
 * @covers \Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7\Currency
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class CurrencyTest extends MigrateSqlSourceTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'commerce',
    'commerce_price',
    'migrate_drupal',
    'commerce_migrate_commerce',
  ];

  /**
   * {@inheritdoc}
   */
  public function providerSource() {
    $tests = [];

    // The source data.
    $tests[0]['source_data']['variable'] = [
      [
        'name' => 'commerce_default_currency',
        'value' => 's:3:"NZD";',
      ],
    ];

    $tests[0]['expected_data'] = [
      [
        'commerce_default_currency' => 'NZD',
        'currency_name' => 'New Zealand Dollar',
        'numeric_code' => '554',
      ],
    ];
    $tests[0]['expected_count'] = NULL;
    $tests[0]['configuration'] = [
      'variables' =>
        [
          'commerce_default_currency',
        ],
    ];
    return $tests;
  }

}
