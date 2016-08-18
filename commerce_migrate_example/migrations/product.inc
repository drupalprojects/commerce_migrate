<?php
/**
 * @file
 * Import products into the "product" product type.
 */

/**
 * Class CommerceExampleProductMigration.
 *
 * This simple migration assumes that a product type called "product"
 * already exists, and has the sku, title, commerce_price, and field_image
 * fields.
 */
class CommerceExampleProductMigration extends CommerceMigrateExampleProductsCsv {

  /**
   * {@inheritdoc}
   */
  public function __construct($arguments = array()) {
    parent::__construct($arguments);

    // Rather than specifying the type directly here, we would probably use
    // arguments, but instead this just specifies the 'product' product type
    // to make it obvious what's going on.
    $this->buildMap(MigrateDestinationEntityAPI::getKeySchema('commerce_product'));
    $this->setDescription(t('Import products from CSV file (with no header).'));
    $this->setDestination(new MigrateDestinationEntityAPI('commerce_product', 'product'));
    $this->addSimpleMappings(array('sku', 'title'));
    $this->addFieldMapping('commerce_price', 'price');

    $this->addFieldMapping('commerce_price:currency_code', 'currency_code')
      ->defaultValue(commerce_default_currency());

    $this->addFieldMapping('field_image', 'image');

    $this->addFieldMapping('field_image:file_class')
      ->defaultValue('MigrateFileUri');

    $this->addFieldMapping('field_image:source_dir')
      ->defaultValue($this->getModulePath() . '/images');
  }

}
