<?php


namespace calderawp\DB;
use calderawp\DB\Contracts\FactoryContract;
use WpDbTools\Db\Database;
use WpDbTools\Db\WpDbAdapter;
use WpDbTools\Type\GenericTable;
use WpDbTools\Type\GenericTableSchema;
use \calderawp\interop\IteratableCollection as Collection;
use calderawp\interop\Attribute;
use WpDbTools\Type\TableSchema;

class Factory implements FactoryContract
{

    protected $wpAdapter;

    /**
     * Create a column from an attribute
     *
     * @param Attribute $attribute
     *
     * @return array
     */
    public function columnSchema(Attribute $attribute): array
    {

        $type = $attribute->getDataType();
        if('array' === $type ) {
            $type = 'string';
        }
        return [
        'name' => $attribute->getName(),
        'description' => $attribute->getSqlDescriptor(),
        'format' => $attribute->getFormat(),
        'type' => $type
        ];

    }

    /**
     * Create a table schema from an Attributes collection
     *
     * @param Attribute[] $attributes
     * @param string      $tableName
     * @param string      $primaryKey
     * @param array       $indices
     *
     * @return TableSchema
     * @throws \WpDbTools\Exception\Type\InvalidTableSchema
     */
    public function tableSchema(
        array $attributes,
        string $tableName,
        string $primaryKey = 'id',
        array $indices = []
    ): TableSchema {
        $schemas = [];
        foreach ($attributes as $attribute) {
            if (is_array($attribute)) {
                $attribute = Attribute::fromArray($attribute);
            }
            $schemas[ $attribute->getName() ] = $this->columnSchema($attribute);
        }

        $preparedIndices = [];
        if (!empty($indices)) {
            foreach ($indices as $index) {
                $preparedIndices[ $index ] = [
                'name' => $index,
                'description' => "KEY `$index` (`$index`)"
                ];
            }
        }
        $table = new GenericTableSchema(
            [
            GenericTableSchema::TABLE_NAME_INDEX => $tableName,
            GenericTableSchema::TABLE_SCHEMA_INDEX => $schemas,
            GenericTableSchema::TABLE_PRIMARY_KEY_INDEX => [
            $primaryKey
            ],
            GenericTableSchema::TABLE_INDICES_INDEX => $preparedIndices

            ]
        );
        return $table;
    }

    /**
     * Create a generic database table
     *
     * @param TableSchema $table
     * @param Database    $databaseAdapter
     *
     * @return \WpDbTools\Type\Table
     */
    public function databaseTable(TableSchema $table, Database $databaseAdapter): \WpDbTools\Type\Table
    {
        return new GenericTable($table, $databaseAdapter);
    }

    /**
     * Create a WordPress database table
     *
     * @param TableSchema $tableSchema
     * @param \wpdb       $wpdb
     *
     * @return Table
     */
    public function wordPressDatabaseTable(TableSchema $tableSchema, \wpdb $wpdb): Table
    {
        return new Table(new WpDbAdapter($wpdb), $tableSchema);
    }

}
