<?php

namespace Drupal\commerce_migrate\Plugin\migrate\source\commerce\d7;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Drupal 7 commerce_line_item source from database.
 *
 * @MigrateSource(
 *   id = "d7_line_item",
 *   source = "commerce_order"
 * )
 */

class LineItem extends FieldableEntity {
  public function fields() {
    return [
      'line_item_id' => t('Line Item ID'),
      'order_id' => t('Order ID'),
      'type' => t('Type'),
      'line_item_label' => t('Line Item Label'),
      'quantity' => t('Quantity'),
      'created' => t('Created'),
      'changed' => t('Changes'),
      'data' => t('Data'),
    ];
  }

  /**
   * @inheritDoc
   */
  public function getIds() {
    $ids['line_item_id']['type'] = 'integer';
    $ids['line_item_id']['alias'] = 'li';

    return $ids;
  }

  /**
   * @inheritDoc
   */
  public function query() {
    $query = $this->select('commerce_line_item', 'li')
      ->fields('li', array_keys($this->fields()));
    $query->condition('type', 'product');

    return $query;
  }

  /**
   * @inheritDoc
   */
  public function prepareRow(Row $row) {
    // Get Field API field values.
    foreach (array_keys($this->getFields('commerce_line_item', $row->getSourceProperty('type'))) as $field) {
      $nid = $row->getSourceProperty('line_item_id');
      $vid = $row->getSourceProperty('revision_id');
      $row->setSourceProperty($field, $this->getFieldValues('commerce_line_item', $field, $nid, $vid));
    }
    return parent::prepareRow($row);
  }
}