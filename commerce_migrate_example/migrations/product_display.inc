<?php
/**
 * @file
 * Import nodes of "product_display" content type.
 */

/**
 * Class CommerceExampleProductDisplayMigration.
 *
 * This is hard-wired to the node type "product_display" with a
 * product reference field of type field_product.
 */
class CommerceExampleProductDisplayMigration extends CommerceMigrateExampleProductsCsv {

  /**
   * {@inheritdoc}
   */
  public function __construct($arguments = array()) {
    parent::__construct($arguments);

    $this->buildMap(MigrateDestinationNode::getKeySchema());
    $this->setDescription(t('Import product display nodes from CSV file (with no header).'));
    $this->setDestination(new MigrateDestinationNode('product_display'));
    // For a multivalued import, see the field_migrate_example_country mapping
    // in the Migrate Example in beer.inc.
    // Here we do a single SKU per product node.
    $this->addSimpleMappings(array('title'));
    $this->addFieldMapping('body', 'description');

    $this->addFieldMapping('field_product', 'sku')
      ->sourceMigration('CommerceExampleProduct');
  }

}
