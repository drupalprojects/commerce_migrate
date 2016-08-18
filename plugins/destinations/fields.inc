<?php

/**
 * @file
 * Support for processing commerce fields (product reference, customer profile
 * reference, price)
 */

class MigrateCommerceCustomerProfileReferenceFieldHandler extends MigrateSimpleFieldHandler {
  public function __construct() {
    parent::__construct(array(
      'value_key' => 'profile_id',
      'skip_empty' => TRUE,
    ));
    $this->registerTypes(array('commerce_customer_profile_reference'));
  }

}

class MigrateCommerceLineItemReferenceFieldHandler extends MigrateSimpleFieldHandler {
  public function __construct() {
    parent::__construct(array(
      'value_key' => 'line_item_id',
      'skip_empty' => TRUE,
    ));
    $this->registerTypes(array('commerce_line_item_reference'));
  }

}

class MigrateCommerceProductReferenceFieldHandler extends MigrateSimpleFieldHandler {
  public function __construct() {
    parent::__construct(array(
      'value_key' => 'product_id',
      'skip_empty' => TRUE,
    ));
    $this->registerTypes(array('commerce_product_reference'));
  }

}

/**
 * Class MigrateCommercePriceFieldHandler.
 *
 * @see \MigrateFieldsEntityHandler
 */
class MigrateCommercePriceFieldHandler extends MigrateFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->registerTypes(array('commerce_price'));
  }

  /**
   * Returns a list of subfields.
   *
   * @param string $field_type
   *   One of registered field types.
   *
   * @return string[]
   *   An array of subfields.
   */
  public function fields($field_type) {
    // Declare our arguments to also be available as subfields.
    $fields = array(
      'currency_code' => t('Subfield: currency code'),
    );

    return $fields;
  }

  /**
   * Prepare field.
   *
   * @param object $entity
   *   Entity object.
   * @param array $field_info
   *   Field definition.
   * @param array $instance
   *   Field instance.
   * @param array $values
   *   Values of the field.
   *
   * @return array
   *   Drupal representation of a field.
   */
  public function prepare($entity, array $field_info, array $instance, array $values) {
    $arguments = isset($values['arguments']) ? $values['arguments'] : array();
    unset($values['arguments']);

    $currency_code = empty($arguments['currency_code']) ? commerce_default_currency() : $arguments['currency_code'];
    $components = empty($arguments['components']['arguments']) ? array() : array_filter($arguments['components']['arguments'], 'is_numeric');
    // Detect field language.
    $language = $this->getFieldLanguage($entity, $field_info, $arguments);
    // Initial price should not contain "amount" - it will be added later.
    $price = array(
      'currency_code' => $currency_code,
    );

    // If no price components going to be set - add "base_price".
    if (empty($components)) {
      $components['base_price'] = $values[0];
    }

    foreach ($components as $name => $component) {
      // Allow to component be a scalar amount value.
      if (!is_array($component)) {
        $component = array('amount' => $component);
      }

      // Extend component by default properties.
      $component += array(
        'included' => TRUE,
        'currency_code' => $currency_code,
      );

      // The "included" property - not a part of components array.
      $included = (bool) $component['included'];
      unset($component['included']);

      // Calculate decimal amount.
      $component['amount'] = commerce_currency_decimal_to_amount($component['amount'], $component['currency_code']);
      // Add a component.
      $price['data'] = commerce_price_component_add($price, $name, $component, $included, FALSE);
    }

    // Calculate total amount of all of the components.
    $total = commerce_price_component_total($price);
    // Copy price components.
    $total['data'] = $price['data'];

    if (isset($arguments['tax_rate'])) {
      $total['data']['include_tax'] = $arguments['tax_rate'];
    }

    return array($language => array($total));
  }

}
