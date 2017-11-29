<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\field\Entity\FieldConfig;

/**
 * Migrate field instance tests.
 *
 * @requires module migrate_plus
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class FieldInstanceTest extends Ubercart6TestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'path',
    'commerce_product',
    'field',
    'migrate_plus',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->migrateContentTypes();
    $this->executeMigrations([
      'd6_ubercart_product_type',
      'd6_field',
      'd6_field_instance',
    ]);
  }

  /**
   * Tests migration of field instances to products.
   */
  public function testFieldInstanceMigration() {
    $field = FieldConfig::load('commerce_product.product.field_image_cache');
    $this->assertInstanceOf(FieldConfig::class, $field);
    $field = FieldConfig::load('commerce_product.product.field_image_cache');
    $this->assertInstanceOf(FieldConfig::class, $field);
    $field = FieldConfig::load('commerce_product.product.field_integer');
    $this->assertInstanceOf(FieldConfig::class, $field);
    $field = FieldConfig::load('commerce_product.product.field_sustain');
    $this->assertInstanceOf(FieldConfig::class, $field);
    $field = FieldConfig::load('commerce_product.ship.field_engine');
    $this->assertInstanceOf(FieldConfig::class, $field);
    $field = FieldConfig::load('commerce_product.ship.field_image_cache');
    $this->assertInstanceOf(FieldConfig::class, $field);
    $field = FieldConfig::load('commerce_product.ship.field_integer');
    $this->assertInstanceOf(FieldConfig::class, $field);

    $field = FieldConfig::load('node.page.field_integer');
    $this->assertInstanceOf(FieldConfig::class, $field);
  }

}
