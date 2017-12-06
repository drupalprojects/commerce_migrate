<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests commerce field formatter settings migration.
 *
 * @requires module migrate_plus
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class FieldFormatterSettingsTest extends Commerce1TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'comment',
    'datetime',
    'field',
    'file',
    'image',
    'link',
    'menu_ui',
    'node',
    'path',
    'taxonomy',
    'telephone',
    'text',
    'commerce_product',
    'migrate_plus',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('node');
    $this->installEntitySchema('comment');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('profile');
    $this->installEntitySchema('view');
    $this->installEntitySchema('commerce_product');
    $this->installEntitySchema('commerce_product_variation');
    $this->installConfig(static::$modules);
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
      'd7_view_modes',
      'd7_field_formatter_settings',
    ]);
  }

  /**
   * Asserts various aspects of a view display.
   *
   * @param string $id
   *   The view display ID.
   */
  protected function assertEntity($id) {
    $display = EntityViewDisplay::load($id);
    $this->assertInstanceOf(EntityViewDisplayInterface::class, $display);
  }

  /**
   * Asserts various aspects of a particular component of a view display.
   *
   * @param string $display_id
   *   The view display ID.
   * @param string $component_id
   *   The component ID.
   * @param string $type
   *   The expected component type (formatter plugin ID).
   * @param string $label
   *   The expected label of the component.
   * @param int $weight
   *   The expected weight of the component.
   */
  protected function assertComponent($display_id, $component_id, $type, $label, $weight) {
    $component = EntityViewDisplay::load($display_id)->getComponent($component_id);
    $this->assertTrue(is_array($component));
    $this->assertSame($type, $component['type']);
    $this->assertSame($label, $component['label']);
    $this->assertSame($weight, $component['weight']);
  }

  /**
   * Asserts that a particular component is NOT included in a display.
   *
   * @param string $display_id
   *   The display ID.
   * @param string $component_id
   *   The component ID.
   */
  protected function assertComponentNotExists($display_id, $component_id) {
    $component = EntityViewDisplay::load($display_id)->getComponent($component_id);
    $this->assertTrue(is_null($component));
  }

  /**
   * Tests migration of D7 field formatter settings.
   */
  public function testMigration() {
    $this->assertEntity('comment.comment_node_ad_push.default');
    $this->assertEntity('comment.comment_node_bags_cases.default');
    $this->assertEntity('comment.comment_node_blog_post.default');
    $this->assertEntity('comment.comment_node_drinks.default');
    $this->assertEntity('comment.comment_node_hats.default');
    $this->assertEntity('comment.comment_node_page.default');
    $this->assertEntity('comment.comment_node_shoes.default');
    $this->assertEntity('comment.comment_node_slideshow.default');
    $this->assertEntity('comment.comment_node_storage_devices.default');
    $this->assertEntity('comment.comment_node_tops.default');
    $this->assertEntity('commerce_order_item.default.default');

    $this->assertEntity('commerce_product.bags_cases.default');
    $this->assertComponent('commerce_product.bags_cases.default', 'body', 'text_default', 'hidden', -4);
    $this->assertComponent('commerce_product.bags_cases.default', 'variations', 'commerce_add_to_cart', 'above', 10);
    $this->assertEntity('commerce_product.default.default');
    $this->assertEntity('commerce_product.drinks.default');
    $this->assertEntity('commerce_product.hats.default');
    $this->assertEntity('commerce_product.shoes.default');
    $this->assertEntity('commerce_product.storage_devices.default');
    $this->assertEntity('commerce_product.tops.default');

    $this->assertEntity('commerce_product_variation.bags_cases.default');
    $this->assertEntity('commerce_product_variation.drinks.default');
    $this->assertComponent('commerce_product_variation.drinks.default', 'field_images', 'image', 'above', 1);
    $this->assertComponent('commerce_product_variation.drinks.default', 'price', 'commerce_price_default', 'above', 0);
    $this->assertEntity('commerce_product_variation.hats.default');
    $this->assertEntity('commerce_product_variation.shoes.default');
    $this->assertEntity('commerce_product_variation.storage_devices.default');
    $this->assertEntity('commerce_product_variation.tops.default');
    $this->assertEntity('node.ad_push.default');
    $this->assertEntity('node.ad_push.teaser');

    // Tests node formatter settings.
    $this->assertEntity('node.ad_push.default');
    $this->assertComponent('node.ad_push.default', 'field_image', 'image', 'hidden', -1);
    $this->assertComponent('node.ad_push.default', 'field_link', 'link', 'above', 2);
    $this->assertComponent('node.ad_push.default', 'field_tagline', 'string', 'above', 3);
    $this->assertEntity('node.ad_push.teaser');

    $this->assertEntity('node.bags_cases.default');
    $this->assertEntity('node.bags_cases.teaser');

    $this->assertEntity('node.blog_post.default');
    $this->assertComponent('node.blog_post.default', 'body', 'text_default', 'hidden', 1);
    $this->assertEntity('node.blog_post.teaser');
    $this->assertComponent('node.blog_post.teaser', 'body', 'text_summary_or_trimmed', 'hidden', 1);

    // @todo: The product_list view is missing for all products.
    // https://www.drupal.org/project/commerce_migrate/issues/2927330
    $this->assertEntity('node.drinks.default');
    // $this->assertEntity('node.drinks.product_list');
    $this->assertEntity('node.drinks.teaser');

    $this->assertEntity('node.hats.default');
    // $this->assertEntity('node.hats.product_list');
    $this->assertEntity('node.hats.teaser');

    $this->assertEntity('node.page.default');
    $this->assertComponent('node.page.default', 'body', 'text_default', 'hidden', 0);
    $this->assertEntity('node.page.teaser');
    $this->assertComponent('node.page.teaser', 'body', 'text_summary_or_trimmed', 'hidden', 0);

    $this->assertEntity('node.shoes.default');
    // $this->assertEntity('node.shoes.product_list');
    $this->assertEntity('node.shoes.teaser');

    $this->assertEntity('node.slideshow.default');
    $this->assertEntity('node.slideshow.teaser');

    $this->assertEntity('node.storage_devices.default');
    // $this->assertEntity('node.storage_devices.product_list');
    $this->assertEntity('node.storage_devices.teaser');

    $this->assertEntity('node.tops.default');
    // $this->assertEntity('node.tops.product_list');
    $this->assertEntity('node.tops.teaser');

    // Tests node formatter settings.
    $this->assertEntity('taxonomy_term.category.default');
    $this->assertEntity('taxonomy_term.collection.default');
  }

}
