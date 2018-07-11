<?php

namespace Drupal\Tests\commerce_migrate_magento\Kernel\Migrate\magento2;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\profile\Entity\Profile;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;
use Drupal\Tests\commerce_migrate_magento\Kernel\Migrate\CsvTestBase;

/**
 * Tests shipping profile migration.
 *
 * @requires module migrate_plus
 *
 * @group commerce_migrate
 * @group commerce_migrate_magento2
 */
class ProfileShippingTest extends CsvTestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'action',
    'address',
    'commerce',
    'commerce_migrate',
    'commerce_migrate_magento',
    'commerce_order',
    'commerce_price',
    'commerce_store',
    'commerce_shipping',
    'entity',
    'entity_reference_revisions',
    'field',
    'inline_entity_form',
    'migrate_plus',
    'options',
    'physical',
    'profile',
    'state_machine',
    'system',
    'telephone',
    'text',
    'user',
    'views',
  ];

  /**
   * Filename of the test fixture.
   *
   * @var string
   */
  protected $fixture = 'public://import/magento2_customer_address_20180618_003449.csv';

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installSchema('system', 'sequences');
    $this->installConfig(['system']);
    $this->installConfig(['address']);
    $this->installEntitySchema('user');
    $this->installEntitySchema('profile');
    $this->installConfig('commerce_order');

    $this->executeMigrations([
      'magento2_user',
      'magento2_profile_type',
    ]);

    // Add address field to shipping type.
    $field_instance = [
      'field_name' => 'address',
      'entity_type' => 'profile',
      'bundle' => 'shipping',
      'label' => 'Shipping',
    ];
    $field = FieldConfig::create($field_instance);
    $field->save();

    $field_storage_definition = [
      'field_name' => 'phone',
      'entity_type' => 'profile',
      'type' => 'telephone',
      'cardinality' => 1,
    ];
    $storage = FieldStorageConfig::create($field_storage_definition);
    $storage->save();

    $field_instance = [
      'field_name' => 'phone',
      'entity_type' => 'profile',
      'bundle' => 'shipping',
      'label' => 'Shipping',
    ];
    $field = FieldConfig::create($field_instance);
    $field->save();

    $this->executeMigration('magento2_profile_shipping');
  }

  /**
   * Test profile migration.
   */
  public function testProfileBilling() {
    $this->assertProfile(1, '1', 'shipping', 'und', TRUE, NULL, NULL);
    $profile = Profile::load(1);
    $address = $profile->get('address')->first()->getValue();
    $this->assertAddressField($address, 'US', 'Michigan', 'Calder', NULL, '49628-7978', NULL, '6146 Honey Bluff Parkway', 'Apartment 242', 'Veronica', NULL, 'Costello', '');
    $phone = [
      ['value' => '(555) 229-3326'],
    ];
    $this->assertSame($phone, $profile->get('phone')->getValue());

    $this->assertProfile(2, '2', 'shipping', 'und', TRUE, NULL, NULL);
    $profile = Profile::load(2);
    $address = $profile->get('address')->first()->getValue();
    $this->assertAddressField($address, 'US', 'Maryland', 'Towson', NULL, '21210', NULL, '123 Hawk Way', NULL, 'Tui', NULL, 'Song', '');
    $phone = [
      ['value' => '111-2222'],
    ];
    $this->assertSame($phone, $profile->get('phone')->getValue());
    // Test revisions.
    /** @var \Drupal\profile\Entity\ProfileInterface $profile_revision */
    $profile_revision = \Drupal::entityTypeManager()
      ->getStorage('profile')
      ->loadRevision(3);
    $this->assertNull($profile_revision);
  }

}
