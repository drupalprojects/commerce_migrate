<?php
/**
 * @file
 * Import line items of the "product" type.
 */

/**
 * Class CommerceExampleProductLineItemMigration.
 */
class CommerceExampleProductLineItemMigration extends CommerceMigrateExampleProductsCsv {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $arguments) {
    parent::__construct($arguments);

    $this->buildMap(MigrateDestinationEntityAPI::getKeySchema('commerce_line_item'));
    $this->setDescription(t('Import commerce line items with products from CSV file (with no header).'));
    $this->setDestination(new MigrateDestinationEntityAPI('commerce_line_item', 'product'));

    $this->addFieldMapping('order_id', 'order_number')
      ->sourceMigration('CommerceExampleOrder');

    $this->addFieldMapping('line_item_label', 'title');

    $this->addFieldMapping('commerce_unit_price', 'price');

    $this->addFieldMapping('commerce_unit_price:currency_code', 'currency_code')
      ->defaultValue(commerce_default_currency());

    $this->addFieldMapping('commerce_unit_price:components:shipping', 'price')
      // Add "shipping" price component instead of "base_price" if
      // condition in callback function will be satisfied.
      ->callbacks(array($this, 'priceComponentShipping'));

    $this->addFieldMapping('type', 'sku')
      // Detect line item type depending on SKU.
      ->callbacks(array($this, 'type'));

    $this->addFieldMapping('commerce_product', 'sku')
      ->sourceMigration('CommerceExampleProduct')
      // Attach product to line item only if type is "product".
      ->callbacks(array($this, 'shouldProductBeAttached'));

    $this->addFieldMapping('created')
      ->defaultValue(time());

    $this->addFieldMapping('quantity')
      ->defaultValue(5);

    $this->addUnmigratedDestinations(array(
      'commerce_display_path',
      // Total always calculated automatically.
      'commerce_total:currency_code',
      'commerce_total',
      'order',
      'path',
    ));

    $this->addUnmigratedSources(array(
      'image',
      'description',
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow($row) {
    // Import the line items only if product's SKU starts from "6".
    return $row->sku[0] == 6 && parent::prepareRow($row);
  }

  /**
   * Check line item type.
   *
   * @return string
   *   Type of line item.
   */
  protected function type() {
    // If second character of SKU is "0" then line item is of "product" type.
    return 0 == $this->sourceValues->sku[1] ? 'product' : 'shipping';
  }

  /**
   * Add "shipping" price component for "shipping" line item types.
   *
   * @param string $amount
   *   Shipping amount.
   *
   * @return string|bool
   *   An amount of shipping or any non-numeric value to skip.
   */
  protected function priceComponentShipping($amount) {
    return 'shipping' === $this->type() ? $amount : FALSE;
  }

  /**
   * Should product be attached to line item.
   *
   * @param string[] $map
   *   Migration map.
   *
   * @return string[]|bool
   *   Migration map or FALSE when product should not be attached.
   */
  protected function shouldProductBeAttached(array $map) {
    return 'product' === $this->type() ? $map : FALSE;
  }

}
