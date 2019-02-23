<?php

namespace calderawp\DB\Contracts;

use calderawp\DB\Table;
use calderawp\interop\Attribute;
use WpDbTools\Db\Database;
use WpDbTools\Type\TableSchema;

interface FactoryContract
{
    /**
     * Create a column from an attribute
     *
     * @param Attribute $attribute
     *
     * @return array
     */
    public function columnSchema(Attribute $attribute): array;

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
    ): TableSchema;

    /**
     * Create a generic database table
     *
     * @param TableSchema $table
     * @param Database    $databaseAdapter
     *
     * @return \WpDbTools\Type\Table
     */
    public function databaseTable(TableSchema $table, Database $databaseAdapter): \WpDbTools\Type\Table;

    /**
     * Create a WordPress database table
     *
     * @param TableSchema $tableSchema
     * @param \wpdb       $wpdb
     *
     * @return Table
     */
    public function wordPressDatabaseTable(TableSchema $tableSchema, \wpdb $wpdb): Table;
}
