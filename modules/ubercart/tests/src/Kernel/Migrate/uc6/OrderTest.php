<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\uc6;

use Drupal\commerce_order\Entity\Order;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests order migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_uc6
 */
class OrderTest extends Ubercart6TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'path',
    'commerce_product',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('view');
    $this->installEntitySchema('profile');
    $this->installEntitySchema('commerce_product');
    $this->installEntitySchema('commerce_product_variation');
    $this->installEntitySchema('commerce_order');
    $this->installEntitySchema('commerce_order_item');
    $this->installConfig(['commerce_order']);
    $this->installConfig(['commerce_product']);
    $this->migrateStore();
    $this->startCollectingMessages();
    $this->executeMigrations([
      'language',
      'd6_node_type',
      'uc6_product_type',
      'd6_language_content_settings',
      'uc6_language_content_settings',
      'uc6_attribute_field',
      'uc6_product_attribute',
      'uc6_attribute_field_instance',
      'uc6_product_variation',
      'd6_node',
      'uc6_billing_profile',
      'uc6_order_product',
      'uc6_order',
    ]);
  }

  /**
   * Test order migration.
   */
  public function testOrder() {
    $order = [
      'id' => 1,
      'type' => 'default',
      'number' => '1',
      'store_id' => '1',
      'created_time' => '1492868907',
      'changed_time' => '1523578137',
      'completed_time' => '1523578137',
      'email' => 'fordprefect@example.com',
      'ip_address' => '10.1.1.2',
      'customer_id' => '3',
      'placed_time' => '1523578137',
      'total_price' => '35.000000',
      'total_price_currency' => 'NZD',
      'adjustments' => [],
      'label_value' => 'validation',
      'label_rendered' => 'validation',
      'order_items_ids' => ['3', '4'],
      'data' => [],
    ];
    $this->assertOrder($order);
    $order = [
      'id' => 2,
      'type' => 'default',
      'number' => '2',
      'store_id' => '1',
      'created_time' => '1492989920',
      'changed_time' => '1508916762',
      'completed_time' => '1508916762',
      'email' => 'trintragula@example.com',
      'label' => 'completed',
      'ip_address' => '10.1.1.2',
      'customer_id' => '5',
      'placed_time' => '1508916762',
      'total_price' => '1500.000000',
      'total_price_currency' => 'NZD',
      'adjustments' => [],
      'label_value' => 'completed',
      'label_rendered' => 'Completed',
      'order_items_ids' => ['2'],
      'data' => unserialize('a:2:{s:8:"new_user";a:1:{s:4:"name";s:11:"trintragula";}s:13:"complete_sale";s:8:"new_user";}'),
    ];
    $this->assertOrder($order);
    $order = [
      'id' => 3,
      'type' => 'default',
      'number' => '3',
      'store_id' => '1',
      'created_time' => '1511148641',
      'changed_time' => '1511149246',
      'completed_time' => '1511149246',
      'email' => 'zaphod@example.com',
      'label' => 'completed',
      'ip_address' => '10.1.1.2',
      'customer_id' => '4',
      'placed_time' => '1511149246',
      'total_price' => '20.000000',
      'total_price_currency' => 'NZD',
      'adjustments' => [],
      'label_value' => 'completed',
      'label_rendered' => 'Completed',
      'order_items_ids' => ['5'],
      'data' => unserialize('a:1:{s:13:"complete_sale";s:9:"logged_in";}'),
    ];

    $this->assertOrder($order);
    $order = [
      'id' => 4,
      'type' => 'default',
      'number' => '4',
      'store_id' => '1',
      'created_time' => '1502996811',
      // Changed time is overwritten by Commerce when the status is Draft. The
      // source changed time is '1502996997'.
      'changed_time' => '1523578318',
      'completed_time' => '1523578318',
      'email' => 'trillian@example.com',
      'label' => 'completed',
      'ip_address' => '10.1.1.2',
      'customer_id' => '2',
      'placed_time' => '1523578318',
      'total_price' => '6000000000.000000',
      'total_price_currency' => 'NZD',
      'adjustments' => [],
      'label_value' => 'draft',
      'label_rendered' => 'Draft',
      'order_items_ids' => ['6'],
      'data' => unserialize('a:1:{s:13:"complete_sale";s:9:"logged_in";}'),
    ];
    $this->assertOrder($order);

    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = Order::load(1);
    $this->assertNotNull($order->getBillingProfile());
    $this->assertNull($order->getData('cc_data'));
    $order = Order::load(2);
    $this->assertNotNull($order->getBillingProfile());
    $this->assertNull($order->getData('cc_data'));
  }

}
