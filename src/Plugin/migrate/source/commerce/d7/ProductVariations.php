<?php

namespace Drupal\commerce_migrate\Plugin\migrate\source\commerce\d7;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Drupal 7 commerce_product source from database.
 *
 * @MigrateSource(
 *   id = "d7_product",
 *   source = "commerce_product"
 * )
 */
class ProductVariations extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'product_id' => t('Product (variation) ID'),
      'sku' => t('SKU'),
      'title' => t('Title'),
      'type' => t('Type'),
      'created' => t('Created'),
      'changed' => t('Changes'),
      'data' => t('Data'),
    ];
  }

  /**
   * @inheritDoc
   */
  public function getIds() {
    $ids['product_id']['type'] = 'integer';
    $ids['product_id']['alias'] = 'p';

    return $ids;
  }

  /**
   * @inheritDoc
   */
  public function query() {
    $query = $this->select('commerce_product', 'p')
      ->fields('p', array_keys($this->fields()));

    return $query;
  }

  /**
   * @inheritDoc
   */
  public function prepareRow(Row $row) {
    foreach (array_keys($this->getFields('commerce_product', $row->getSourceProperty('type'))) as $field) {
      $product_id = $row->getSourceProperty('product_id');
      $revision_id = $row->getSourceProperty('revision_id');
      $row->setSourceProperty($field, $this->getFieldValues('commerce_product', $field, $product_id, $revision_id));
    }

    return parent::prepareRow($row);
  }

}