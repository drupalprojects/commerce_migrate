<?php
/**
 * @file
 * As simple as possible migrations.
 *
 * You can run these migration with:
 * - drush mi CommerceExampleProduct
 *
 * To roll back migrations:
 * - drush migrate-rollback CommerceExampleProductDisplay
 *
 * To see the status of all migrations and what migrations are available:
 * - drush ms
 *
 * Please make sure you don't get one of the products into the cart while
 * you're rolling back, as this makes no end of messiness. Products in the
 * cart are (not supposed to be able) to be deleted.
 */

/**
 * Implements hook_migrate_api().
 */
function commerce_migrate_example_migrate_api() {
  $info = array(
    'api' => 2,
    'groups' => array(
      'commerce_example' => array(
        'title' => t('Commerce Example Imports'),
      ),
    ),
  );

  $info['migrations']['CommerceExampleProduct'] = array(
    'class_name' => 'CommerceExampleProductMigration',
    'group_name' => 'commerce_example',
  );

  $info['migrations']['CommerceExampleProductDisplay'] = array(
    'class_name' => 'CommerceExampleProductDisplayMigration',
    'group_name' => 'commerce_example',
    'dependencies' => array(
      'CommerceExampleProduct',
    ),
  );

  $info['migrations']['CommerceExampleProductLineItem'] = array(
    'class_name' => 'CommerceExampleProductLineItemMigration',
    'group_name' => 'commerce_example',
    'dependencies' => array(
      'CommerceExampleProduct',
    ),
  );

  $info['migrations']['CommerceExampleOrder'] = array(
    'class_name' => 'CommerceExampleOrderMigration',
    'group_name' => 'commerce_example',
    'dependencies' => array(
      'CommerceExampleProductLineItem',
    ),
  );

  return $info;
}

