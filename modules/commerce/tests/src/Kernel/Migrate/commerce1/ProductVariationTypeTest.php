<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\commerce1;

use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests product variation type migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce1
 */
class ProductVariationTypeTest extends Commerce1TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'path',
    'inline_entity_form',
    'commerce_product',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('view');
    $this->installEntitySchema('commerce_product_variation');
    $this->executeMigration('commerce1_product_variation_type');
  }

  /**
   * Test product variation type migration from Drupal 7 to 8.
   *
   * Product variation types in Drupal 8 are product types in Drupal 7.
   */
  public function testProductVariationType() {
    $type = [
      'id' => 'bags_cases',
      'label' => 'Bags & Cases',
      'order_item_type_id' => 'product',
      'is_title_generated' => FALSE,
    ];
    $this->assertProductVariationTypeEntity($type['id'], $type['label'], $type['order_item_type_id'], $type['is_title_generated']);
    $type = [
      'id' => 'drinks',
      'label' => 'Drinks',
      'order_item_type_id' => 'product',
      'is_title_generated' => FALSE,
    ];
    $this->assertProductVariationTypeEntity($type['id'], $type['label'], $type['order_item_type_id'], $type['is_title_generated']);
    $type = [
      'id' => 'hats',
      'label' => 'Hats',
      'order_item_type_id' => 'product',
      'is_title_generated' => FALSE,
    ];
    $this->assertProductVariationTypeEntity($type['id'], $type['label'], $type['order_item_type_id'], $type['is_title_generated']);
    $type = [
      'id' => 'shoes',
      'label' => 'Shoes',
      'order_item_type_id' => 'product',
      'is_title_generated' => FALSE,
    ];
    $this->assertProductVariationTypeEntity($type['id'], $type['label'], $type['order_item_type_id'], $type['is_title_generated']);
    $type = [
      'id' => 'storage_devices',
      'label' => 'Storage Devices',
      'order_item_type_id' => 'product',
      'is_title_generated' => FALSE,
    ];
    $this->assertProductVariationTypeEntity($type['id'], $type['label'], $type['order_item_type_id'], $type['is_title_generated']);
    $type = [
      'id' => 'tops',
      'label' => 'Tops',
      'order_item_type_id' => 'product',
      'is_title_generated' => FALSE,
    ];
    $this->assertProductVariationTypeEntity($type['id'], $type['label'], $type['order_item_type_id'], $type['is_title_generated']);
    $type = [
      'id' => 'product',
      'label' => 'Product',
      'order_item_type_id' => 'default',
      'is_title_generated' => FALSE,
    ];
    $this->assertProductVariationTypeEntity($type['id'], $type['label'], $type['order_item_type_id'], $type['is_title_generated']);
  }

}