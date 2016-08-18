<?php
/**
 * @file
 * A simple base class that contains things common to all
 * CommerceMigrateExample classes.
 */

/**
 * Class CommerceMigrateExampleMigration.
 */
abstract class CommerceMigrateExampleMigration extends \Migration {

  /**
   * The name of CSV file.
   */
  const FILE = '';

  /**
   * Module path.
   *
   * @var string
   */
  private $modulePath = '';

  /**
   * {@inheritdoc}
   */
  public function __construct(array $arguments) {
    parent::__construct($arguments);

    $this->modulePath = drupal_get_path('module', 'commerce_migrate_example');

    $csv_file = $this->modulePath . '/files/' . static::FILE;
    $csv_columns = array();

    if (!file_exists($csv_file)) {
      throw new \Exception(t('The "@csv_file" CSV file does not exists.', array(
        '@csv_file' => $csv_file,
      )));
    }

    foreach (static::csvColumns() as $drupal_field => $csv_header) {
      $csv_columns[] = array($drupal_field, $csv_header);
    }

    // Create a MigrateSource object, which manages retrieving the input data.
    $this->source = new \MigrateSourceCSV($csv_file, $csv_columns);
  }

  /**
   * Create a map object for tracking the relationships between source rows.
   *
   * @param array $destination_schema
   *   Values of any MigrateDestination::getKeySchema().
   */
  protected function buildMap($destination_schema) {
    // Create a map object for tracking the relationships between source rows.
    // The first column in CSV - the primary index.
    $columns = static::csvColumns();

    $this->map = new \MigrateSQLMap(
      $this->machineName,
      array(
        key($columns) => array(
          'type' => 'varchar',
          'length' => 24,
          'not null' => TRUE,
          'description' => current($columns),
        ),
      ),
      $destination_schema
    );
  }

  /**
   * Returns path to example module.
   *
   * @return string
   *   Path to example module.
   */
  protected function getModulePath() {
    return $this->modulePath;
  }

  /**
   * Provide the names of the incoming CSV file columns.
   *
   * WARNING! Column names MUST BE IN ORDER to CSV file structure!
   *
   * @return string[]
   *   An associative array where key is a machine name of a field from
   *   CSV file and value - human-readable name of a CSV column.
   */
  public static function csvColumns() {
    return array();
  }

}
