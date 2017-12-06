<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\FieldConfigInterface;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests commerce field instance migration.
 *
 * @requires module migrate_plus
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class FieldInstanceTest extends Commerce1TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'comment',
    'datetime',
    'file',
    'image',
    'link',
    'menu_ui',
    'node',
    'system',
    'taxonomy',
    'telephone',
    'text',
    'path',
    'commerce_product',
    'migrate_plus',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installConfig(static::$modules);
    $this->installEntitySchema('commerce_product');
    $this->installEntitySchema('profile');
    $this->installEntitySchema('node_type');

    $this->executeMigrations([
      'd7_user_role',
      'd7_user',
      'd7_node_type',
      'd7_comment_type',
      'd7_taxonomy_vocabulary',
      'd7_commerce_billing_profile',
      'd7_commerce_product_variation_type',
      'd7_commerce_product_type',
      'd7_field',
      'd7_field_instance',
    ]);
  }

  /**
   * Asserts various aspects of a field config entity.
   *
   * @param string $id
   *   The entity ID in the form ENTITY_TYPE.BUNDLE.FIELD_NAME.
   * @param string $expected_label
   *   The expected field label.
   * @param string $expected_field_type
   *   The expected field type.
   * @param bool $is_required
   *   Whether or not the field is required.
   * @param bool $expected_translatable
   *   Whether or not the field is expected to be translatable.
   */
  protected function assertEntity($id, $expected_label, $expected_field_type, $is_required, $expected_translatable) {
    list ($expected_entity_type, $expected_bundle, $expected_name) = explode('.', $id);

    /** @var \Drupal\field\FieldConfigInterface $field */
    $field = FieldConfig::load($id);
    $this->assertInstanceOf(FieldConfigInterface::class, $field);
    $this->assertEquals($expected_label, $field->label());
    $this->assertEquals($expected_field_type, $field->getType());
    $this->assertEquals($expected_entity_type, $field->getTargetEntityTypeId());
    $this->assertEquals($expected_bundle, $field->getTargetBundle());
    $this->assertEquals($expected_name, $field->getName());
    $this->assertEquals($is_required, $field->isRequired());
    $this->assertEquals($expected_entity_type . '.' . $expected_name, $field->getFieldStorageDefinition()->id());
    $this->assertEquals($expected_translatable, $field->isTranslatable());
  }

  /**
   * Asserts the settings of a link field config entity.
   *
   * @param string $id
   *   The entity ID in the form ENTITY_TYPE.BUNDLE.FIELD_NAME.
   * @param int $title_setting
   *   The expected title setting.
   */
  protected function assertLinkFields($id, $title_setting) {
    $field = FieldConfig::load($id);
    $this->assertSame($title_setting, $field->getSetting('title'));
  }

  /**
   * Tests migrating D7 field instances to field_config entities.
   */
  public function testFieldInstances() {
    // Comment field instances.
    $this->assertEntity('comment.comment_node_ad_push.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_bags_cases.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_blog_post.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_drinks.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_hats.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_page.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_shoes.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_slideshow.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_storage_devices.comment_body', 'Comment', 'text_long', TRUE, FALSE);
    $this->assertEntity('comment.comment_node_tops.comment_body', 'Comment', 'text_long', TRUE, FALSE);

    // Commerce product field instances.
    $this->assertEntity('commerce_product.bags_cases.body', 'Body', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.stores', 'Stores', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.variations', 'Variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.field_brand', 'Brand', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.field_category', 'Category', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.field_collection', 'Collection', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.field_gender', 'Gender', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.field_product', 'Product variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.bags_cases.title_field', 'Title', 'string', TRUE, FALSE);
    $this->assertEntity('commerce_product.default.body', 'Body', 'text_with_summary', FALSE, TRUE);
    $this->assertEntity('commerce_product.default.stores', 'Stores', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.default.variations', 'Variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.drinks.body', 'Body', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('commerce_product.drinks.stores', 'Stores', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.drinks.variations', 'Variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.drinks.field_brand', 'Brand', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.drinks.field_category', 'Category', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.drinks.field_collection', 'Collection', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.drinks.field_gender', 'Gender', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.drinks.field_product', 'Product variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.drinks.title_field', 'Title', 'string', TRUE, FALSE);
    $this->assertEntity('commerce_product.hats.body', 'Body', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('commerce_product.hats.stores', 'Stores', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.hats.variations', 'Variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.hats.field_brand', 'Brand', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.hats.field_category', 'Category', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.hats.field_collection', 'Collection', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.hats.field_gender', 'Gender', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.hats.field_product', 'Product variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.hats.title_field', 'Title', 'string', TRUE, FALSE);
    $this->assertEntity('commerce_product.shoes.body', 'Body', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('commerce_product.shoes.stores', 'Stores', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.shoes.variations', 'Variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.shoes.field_brand', 'Brand', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.shoes.field_category', 'Category', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.shoes.field_collection', 'Collection', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.shoes.field_gender', 'Gender', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.shoes.field_product', 'Product variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.shoes.title_field', 'Title', 'string', TRUE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.body', 'Body', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.stores', 'Stores', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.variations', 'Variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.field_brand', 'Brand', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.field_category', 'Category', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.field_collection', 'Collection', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.field_gender', 'Gender', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.field_product', 'Product variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.storage_devices.title_field', 'Title', 'string', TRUE, FALSE);
    $this->assertEntity('commerce_product.tops.body', 'Body', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('commerce_product.tops.stores', 'Stores', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.tops.variations', 'Variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.tops.field_brand', 'Brand', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.tops.field_category', 'Category', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.tops.field_collection', 'Collection', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.tops.field_gender', 'Gender', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product.tops.field_product', 'Product variations', 'entity_reference', TRUE, FALSE);
    $this->assertEntity('commerce_product.tops.title_field', 'Title', 'string', TRUE, FALSE);

    // Commerce product variation field instances.
    $this->assertEntity('commerce_product_variation.bags_cases.title_field', 'Title', 'string', TRUE, TRUE);
    $this->assertEntity('commerce_product_variation.bags_cases.commerce_price', 'Price', 'commerce_price', TRUE, FALSE);
    $this->assertEntity('commerce_product_variation.bags_cases.field_color', 'Color', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product_variation.bags_cases.field_images', 'Images', 'image', FALSE, TRUE);
    $this->assertEntity('commerce_product_variation.drinks.field_color', 'Color', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product_variation.drinks.field_images', 'Images', 'image', FALSE, TRUE);
    $this->assertEntity('commerce_product_variation.hats.field_hat_size', 'Size', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product_variation.hats.field_images', 'Images', 'image', FALSE, TRUE);
    $this->assertEntity('commerce_product_variation.shoes.field_color', 'Color', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product_variation.shoes.field_images', 'Images', 'image', FALSE, TRUE);
    $this->assertEntity('commerce_product_variation.shoes.field_shoe_size', 'Size', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product_variation.storage_devices.field_images', 'Images', 'image', FALSE, TRUE);
    $this->assertEntity('commerce_product_variation.storage_devices.field_storage_capacity', 'Capacity', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product_variation.tops.field_color', 'Color', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('commerce_product_variation.tops.field_images', 'Images', 'image', FALSE, TRUE);
    $this->assertEntity('commerce_product_variation.tops.field_top_size', 'Size', 'entity_reference', FALSE, FALSE);

    // Node field instances.
    $this->assertEntity('node.page.body', 'Body', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('node.blog_post.body', 'Description', 'text_with_summary', FALSE, FALSE);
    $this->assertEntity('node.blog_post.field_blog_category', 'Category', 'entity_reference', FALSE, FALSE);
    $this->assertEntity('node.blog_post.field_image', 'Image', 'image', TRUE, FALSE);
    $this->assertEntity('node.blog_post.title_field', 'Title', 'string', TRUE, FALSE);
    $this->assertEntity('node.slideshow.field_headline', 'Headline', 'string', FALSE, FALSE);
    $this->assertEntity('node.slideshow.field_image', 'Image', 'image', TRUE, FALSE);
    $this->assertLinkFields('node.slideshow.field_link', DRUPAL_DISABLED);
    $this->assertEntity('node.slideshow.title_field', 'Title', 'string', TRUE, FALSE);
    $this->assertEntity('node.ad_push.field_image', 'Image', 'image', TRUE, FALSE);
    $this->assertLinkFields('node.ad_push.field_link', DRUPAL_DISABLED);
    $this->assertEntity('node.ad_push.title_field', 'Title', 'string', TRUE, FALSE);
  }

}
