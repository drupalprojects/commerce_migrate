<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\commerce_product\Entity\ProductType;

/**
 * Tests line item migration.
 *
 * @group commerce_migrate_commerce
 */
class ProductTypeTest extends Commerce1TestBase {

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
    error_reporting(E_ALL);
    parent::setUp();
    // @todo Execute the d7_field and d7_field_instance migrations?
    $this->executeMigrations([
      'd7_product_display_type',
    ]);
  }

  /**
   * Test product type migration from Drupal 7 to 8.
   */
  public function testProductType() {
    $bags_cases = ProductType::load('bags_cases');
    $this->assertNotNull($bags_cases);
    $this->assertEquals('Bags & Cases', $bags_cases->label());
    $this->assertEquals('A <em>Bags & Cases</em> is a content type which contain product variations.', $bags_cases->getDescription());

    $tops = ProductType::load('tops');
    $this->assertNotNull($tops);
    $this->assertEquals('Tops', $tops->label());
    $this->assertEquals('A <em>Tops</em> is a content type which contain product variations.', $tops->getDescription());

    /** @var \Drupal\Core\Entity\EntityFieldManager $field_manager */
    $field_manager = \Drupal::service('entity_field.manager');
    $field_definitions = $field_manager->getFieldDefinitions('commerce_product', 'tops');

    // Check that stores, variations, and body were added.
    $this->assertTrue(isset($field_definitions['body']));
    $this->assertTrue(isset($field_definitions['variations']));
    $this->assertTrue(isset($field_definitions['stores']));
  }

}