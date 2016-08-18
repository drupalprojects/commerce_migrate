<?php
/**
 * @file
 * Import orders and attach line items with products to them.
 */

/**
 * Class CommerceExampleOrderMigration.
 */
class CommerceExampleOrderMigration extends CommerceMigrateExampleOrdersCsv {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $arguments) {
    parent::__construct($arguments);

    $this->buildMap(MigrateDestinationEntityAPI::getKeySchema('commerce_order'));
    $this->setDescription(t('Import orders with line items from CSV file (with no header).'));
    $this->setDestination(new MigrateDestinationEntityAPI('commerce_order', 'commerce_order'));
    $this->addSimpleMappings(array_keys(static::csvColumns()));
    $this->addUnmigratedDestinations(array(
      'uid',
      'type',
      'path',
      'owner',
      'created',
      'changed',
      'commerce_line_items',
      'commerce_order_total',
      'commerce_customer_billing',
      'commerce_order_total:currency_code',
    ));
  }

  /**
   * {@inheritdoc}
   *
   * @see MigrateDestinationEntity::prepare()
   */
  public function prepare(stdClass $entity, stdClass $row) {
    $order_id = db_select('commerce_order', 'co')
      ->fields('co', array('order_id'))
      ->condition('order_number', $row->order_number)
      ->execute()
      ->fetchField();

    $entity->commerce_line_items[LANGUAGE_NONE] = self::seekLineItems($order_id);
  }

  /**
   * {@inheritdoc}
   *
   * @see Migration::createStubWrapper()
   */
  public function createStub(Migration $migration, array $source_id) {
    $order = entity_create($this->destination->getEntityType(), array(
      'uid' => 0,
      'mail' => 'stub-order@example.com',
      'status' => 'pending',
      'order_number' => 'STUB-' . implode('-', $source_id),
    ));

    commerce_order_save($order);

    return isset($order->order_id) ? array($order->order_id, $order->revision_id) : FALSE;
  }

  /**
   * Returns line items IDs for the order.
   *
   * @param string|int $order_id
   *   The ID of an order.
   *
   * @return array[]
   *   Value for a "commerce_line_items" field.
   */
  private static function seekLineItems($order_id) {
    $line_items = array();
    // The line items migration ain't have stub creation
    // because we aren't interested in orders with stub.
    $line_item_ids = db_select('commerce_line_item', 'cli')
      ->fields('cli', array('line_item_id'))
      ->condition('order_id', $order_id)
      ->execute()
      ->fetchCol();

    foreach ($line_item_ids as $line_item_id) {
      $line_items[] = array('line_item_id' => $line_item_id);
    }

    return $line_items;
  }

}
