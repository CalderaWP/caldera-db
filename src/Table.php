<?php


namespace calderawp\DB;

use WpDbTools\Type\Result;
use calderawp\DB\Exceptions\InvalidColumnException;
use WpDbTools\Db\Database;
use WpDbTools\Db\WpDbAdapter;
use WpDbTools\Type\GenericStatement;
use WpDbTools\Type\TableSchema;
use calderawp\caldera\DataSource\Contracts\SourceContract;
/**
 * Class Table
 *
 * Provides queries on a table that its schema allows
 */
class Table implements SourceContract
{

    /**
     * @type string
     */
    const ANNONYMIZER = 'XXXXXXXXXXXXXX';


    /**
     * @var WpDbAdapter|Database
     */
    private $database;

    /**
     * @var TableSchema
     */
    private $tableSchema;
    /**
     * Table constructor.
     *
     * @param Database    $database
     * @param TableSchema $tableSchema
     */
    public function __construct(Database $database, TableSchema $tableSchema)
    {
        $this->database = $database;
        $this->tableSchema = $tableSchema;
    }

    /**
     * @inheritdoc 
     */
    public function create(array $data):int
    {
        $tableName = $this->getTableName();
        $data = $this->allowedDataOnly($data, false);
        $values = [];
        foreach ($data as $key => $datum) {
            $attribute = $this->getColumnAttribute($key);
            $values[$key] = "{$attribute['format']}";
        }

        $columns = trim(implode(', ', array_keys($values)));
        $values = trim(implode(', ', array_values($values)));
        $statement = new GenericStatement(
            "INSERT INTO {$tableName} ({$columns}) VALUES ({$values})"
        );


        $this->database
            ->query_statement($statement, $this->values($data, false));
        return $this->database->last_insert_id($statement);
    }


    /**
     * @inheritdoc 
     */

    public function read(int $id): Result
    {
        return $this->findById($id);
    }

    /**
     * @inheritdoc 
     */
    public function update(int $id, array $data) : Result
    {
        $tableName = $this->getTableName();
        $primaryKey = $this->getPrimaryKey();
        $data = $this->allowedDataOnly($data, false);
        $data[$this->getPrimaryKey()] = $id;
        $columns = [];
        foreach ($data as $key => $datum) {
            $attribute = $this->getColumnAttribute($key);
            $columns[] = "{$key} = {$attribute['format']}";
        }
        $columns = trim(implode(', ', $columns));
        $where = $this->whereStatement($primaryKey, $id);
        $statement = new GenericStatement(
            "UPDATE {$tableName} SET {$columns} WHERE {$where}"
        );
        $result = $this->database
            ->query_statement(
                $statement, array_merge(
                    $this->values($data, false),
                    [$id]
                )
            );
        return $result;
    }

    /**
     * @inheritdoc 
     */
    public function anonymize(int $id, string  $column):Result
    {
        if (!$this->isAllowedKey($column)) {
            throw new InvalidColumnException();
        }
        $data = [
        $column => self::ANNONYMIZER
        ];


        return $this->update($id, $data);
    }


    /**
     * @inheritdoc 
     */
    public function delete(int $id):bool
    {
        $primaryKey = $this->getPrimaryKey();
        $tableName = $this->getTableName();
        $statement = new GenericStatement("DELETE FROM {$tableName} WHERE `{$primaryKey}` = %d");
        try {
            $result = $this->database
                ->query_statement($statement, [$id]);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @inheritdoc 
     */
    public function findWhere(string  $column, $value) :Result
    {
        try {
            $data = $this->whereColumns([$column], [$value]);
        } catch (InvalidColumnException $e) {
            throw $e; //catch it & throw it back, 2 up no down
        }

        $tableName = $this->getTableName();
        $values = $this->valuesString($data);
        $where = $this->whereStatement($column, $values);
        $statement = new GenericStatement(
            "SELECT * FROM {$tableName} WHERE {$where}"
        );
        $result = $this->database
            ->query_statement($statement, $this->values($data));
        return $result;
    }

    /**
     * @inheritdoc 
     */
    public function findById(int $id):Result
    {
        $primaryKey = $this->getPrimaryKey();
        $tableName = $this->getTableName();
        $statement = new GenericStatement("SELECT * FROM {$tableName} WHERE `{$primaryKey}` = %d");
        $result = $this->database
            ->query_statement($statement, [$id]);
        return $result;
    }

    public function findIn(array $ins, string $column): Result
    {
        // TODO: Implement findIn() method.
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableSchema->name();
    }

    /**
     * Get all columns for table
     *
     * @return array[]
     */
    public function getColumnAttributes()
    {
        return $this->tableSchema->schema();
    }

    /**
     * @return TableSchema
     */
    public function getSchema()
    {
        return $this->tableSchema;
    }

    /**
     * @param  array $data
     * @return string
     */
    public function valuesString(array $data)
    {

        $prepared = [];
        foreach ($this->allowedDataOnly($data) as $key => $value) {
            $attribute = $this->getColumnAttribute($key);
            $prepared[] = $attribute['format'];
        }

        return implode($prepared, ', ');
    }

    /**
     * Values to pass to query
     *
     * Handles order and removing invalid keys
     *
     * @param  array $data
     * @param  bool  $withPrimary Optional. If false, primary key is removed from array.
     * @return array
     */
    public function values(array $data, $withPrimary = true)
    {
        $data = array_values($this->allowedDataOnly($data, $withPrimary));
        return $data;
    }

    /**
     * @param  $data
     * @return array
     */
    protected function allowedKeysFromData($data)
    {
        $keys = array_keys($data);
        foreach ($keys as $keyIndex => $key) {
            if (!$this->isAllowedKey($key)) {
                unset($keys[$keyIndex]);
            }
        }
        return $keys;
    }

    /**
     * @param  string $key Key to check
     * @return bool
     */
    public function isAllowedKey($key)
    {
        return array_key_exists($key, $this->getColumnAttributes());
    }

    /**
     * Find this table's primary key
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        $primaryKey = $this->tableSchema->primary_key();
        return $primaryKey[0];
    }

    /**
     * Detect if key is the primary key of table
     *
     * @param  string $key
     * @return bool
     */
    public function isPrimaryKey($key)
    {
        return $this->isAllowedKey($key) && $key === $this->getPrimaryKey();
    }

    /**
     * Reduce an array of data to only valid data
     *
     * @param  array $data
     * @param  bool  $withPrimary
     * @return array
     */
    protected function allowedDataOnly($data, $withPrimary = true)
    {
        foreach ($data as $key => $datum) {
            if (!$this->isAllowedKey($key)
                || (false === $withPrimary && $this->isPrimaryKey($key) )
            ) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * Prepare column/values for use creating WHERE queries
     *
     * @param  array $columns Array of columns to search where in
     * @param  array $values  Array of columns values to search by
     * @return array
     * @throws InvalidColumnException
     */
    protected function whereColumns(array $columns, array $values)
    {
        foreach ($columns as $column) {
            if (!$this->isAllowedKey($column)) {
                throw new InvalidColumnException($column);
            }
        }
        return array_combine($columns, $values);
    }

    /**
     * Get a column attribute
     *
     * @param  $attributeName
     * @return array
     */
    protected function getColumnAttribute($attributeName)
    {
        $column = $this->getColumnAttributes()[$attributeName];
        $column['format'] = !empty($column['format']) ? $column['format'] : '%s';
        return $column;
    }

    /**
     * Implode array of ids for IN() query
     *
     * @param  array $values
     * @return string
     */
    public function implodeIn(array $values)
    {
        return trim(implode($values, ','));
    }

    /**
     * @param  $column
     * @param  $values
     * @return string
     */
    protected function whereStatement($column, $values)
    {
        $where = "{$column} = {$values}";
        return $where;
    }
}
