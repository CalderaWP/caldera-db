<?php


namespace calderawp\DB\Contracts;

use WpDbTools\Type\TableSchema;


interface CreatesTablesContract
{

	/**
	 * @param TableSchema $tableSchema
	 *
	 * @return bool
	 */
	public function createTable(TableSchema $tableSchema);
}
