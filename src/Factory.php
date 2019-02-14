<?php


namespace calderawp\DB;
use WpDbTools\Db\Database;
use WpDbTools\Db\WpDbAdapter;
use WpDbTools\Type\GenericTable;
use WpDbTools\Type\GenericTableSchema;
use \calderawp\interop\IteratableCollection as Collection;
use calderawp\interop\Attribute;
use WpDbTools\Type\TableSchema;

class Factory
{


	public function columnSchema(Attribute $attribute) : array
	{

		return [
			'name' => $attribute->getName(),
			'description' => $attribute->getSqlDescriptor(),
			'format' => $attribute->getFormat(),
			'type' => $attribute->getDataType()
		];

	}

	public function tableSchema(array $attributes, string $tableName,string $primaryKey = 'id', array $indices = []) : TableSchema
	{
		$schemas= [];
		foreach ( $attributes as $attribute ){
			if( is_array($attribute)){
				$attribute = Attribute::fromArray($attribute);
			}
			$schemas[$attribute->getName()] = $this->columnSchema($attribute);
		}

		$preparedIndices = [];
		if( ! empty( $indices )){
			foreach ($indices as $index ){
				$preparedIndices[$index] = $schemas[$index];
			}
		}
		$table = new GenericTableSchema([
			GenericTableSchema::TABLE_NAME_INDEX => $tableName,
			GenericTableSchema::TABLE_SCHEMA_INDEX => $schemas,
			GenericTableSchema::TABLE_PRIMARY_KEY_INDEX => [
				$primaryKey
			],
			GenericTableSchema::TABLE_INDICES_INDEX => $preparedIndices

		]);
		return $table;
	}

	public function databaseTable(TableSchema $table, Database $databaseAdapter ) : \WpDbTools\Type\Table
	{
		return new GenericTable($table,$databaseAdapter);
	}
	public function wordPressDatabaseTable(TableSchema $table, \wpdb $wpdb ) : \WpDbTools\Type\Table
	{
		return $this->databaseTable($table, new WpDbAdapter($wpdb));
	}
}
