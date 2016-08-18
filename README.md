# Commerce Migrate

General-purpose migration framework extending [Migrate](https://www.drupal.org/project/migrate) module for bringing store information into Drupal Commerce.

- Migrate destination field handlers for commerce fields (reference fields, price field)
- Migrate destination plugin for commerce product types

Commerce Migrate depends on `Migrate Extras` for `Entity API` and `Address Field` integration.

## Ubercart migration

Commerce Migrate Ubercart has moved to its own project - https://www.drupal.org/project/commerce_migrate_ubercart

It can migrate `6.x` and `7.x` Ubercart stores from either the existing Drupal database or a remote database.

## Price fields

Tax rates, currencies and price components can be migrated as subfields.

```php
class CommerceExampleMigration extends Migration {
  public function __construct($arguments = array()) {
    parent::__construct($arguments);

    $this->addFieldMapping('commerce_price', 'price');

    $this->addFieldMapping('commerce_price:currency_code')
      ->defaultValue('GBP');

    $this->addFieldMapping('commerce_price:tax_rate', 'price_tax')
      ->description(t('The tax rate is in the "price_tax" field in the source.'));

    $this->addFieldMapping('commerce_unit_price:components:shipping', 'shipping_price')
      ->callbacks(array($this, 'priceComponentShipping'));
  }

  /**
   * @return string|string[]|mixed
   *   An amount, an array with "amount", "currency_code"
   *   and "included" properties (required only "amount"),
   *   or any non-numeric value for skipping the component.
   */
  protected function priceComponentShipping($amount) {
    return $amount > 0 ? $amount : FALSE;
  }
}
```

## Testing

```shell
php scripts/run-tests.sh --verbose "Commerce Migrate"
```

To see imported data on existing site, you should:

```shell
drush si minimal -y
drush en commerce_order commerce_product_reference commerce_shipping commerce_migrate_example -y
drush mreg
drush migrate-import --group=commerce_example
```

## Resources

The Migrate handbook page at https://www.drupal.org/node/415260

- http://cyrve.com/import
- http://www.gizra.com/content/data-migration-part-1
- http://www.gizra.com/content/data-migration-part-2
