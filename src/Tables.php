<?php


namespace calderawp\DB;

use WpDbTools\Action\MySqlTableCreator;
use WpDbTools\Db\WpDbAdapter;
use WpDbTools\Factory\TableSchemaFactory;
use WpDbTools\Type\TableSchema;

class Tables
{


	/**
	 * @var MySqlTableCreator
	 */
	protected $tableCreator;

	/**
	 * @var TableSchemaFactory
	 */
	protected $schemaFactory;

	/**
	 * @var WpDbAdapter
	 */
	protected $databaseAdapter;


	protected $tables;

	/**
	 *
	 */
	public function __construct()
	{
		$this->databaseAdapter = WpDbAdapter::from_globals();
		$this->tableCreator = new MySqlTableCreator(
			$this->databaseAdapter,
			MySqlTableCreator::IF_NOT_EXISTS
		);
		$this->schemaFactory = TableSchemaFactory::from_globals();
	}


	/**
	 * @param $identifier
	 * @param string|TableSchema $schema
	 * @return Tables
	 */
	public function addTableSchema($identifier, $schema)
	{
		if (is_string($schema)) {
			$schema = $this->schemaFactory->create_from_yaml($schema);
		}

		return $this->addTableSchemaToCollection($identifier, $schema);
	}

	/**
	 * @param TableSchema $tableSchema
	 * @return bool
	 */
	public function createTable(TableSchema $tableSchema)
	{
		return $this->createIfNotExists($tableSchema);
	}


	/**
	 * @param string $identifier
	 * @return TableSchema|null
	 */
	public function getTableSchema($identifier)
	{
		return array_key_exists($identifier, $this->tables)
			? $this->tables[$identifier]
			: null;
	}

	/**
	 * @param string $identifier
	 * @param TableSchema $schema
	 * @return $this
	 */
	protected function addTableSchemaToCollection($identifier, TableSchema $schema)
	{
		$this->tables[$identifier] = $schema;
		return $this;
	}


	/**
	 * @return TableSchemaFactory
	 */
	public function getSchemaFactory()
	{
		return $this->schemaFactory;
	}

	/**
	 * @return WpDbAdapter
	 */
	public function getDatabaseAdapter()
	{
		return $this->databaseAdapter;
	}

	/**
	 * @return MySqlTableCreator
	 */
	public function getTableCreator()
	{
		return $this->tableCreator;
	}


	/**
	 * @param TableSchema $tableSchema
	 * @return bool
	 */
	protected function createIfNotExists(TableSchema $tableSchema)
	{
		return $this->tableCreator->create_table($tableSchema);
	}
}
