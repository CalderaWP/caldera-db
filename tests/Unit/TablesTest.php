<?php

namespace calderawp\DB\Tests\Unit;

use calderawp\DB\Tables;
use WpDbTools\Type\TableSchema;
use WpDbTools\Db\WpDbAdapter;
use WpDbTools\Db\Database;
use WpDbTools\Action\TableCreator;
use WpDbTools\Factory\TableSchemaFactory;

class TablesTest extends TestCase
{
    public function test__construct()
    {
        $dataBase = \Mockery::mock(WpDbAdapter::class);
        $tableCreator = \Mockery::mock(TableCreator::class);
        $schemaFactory = \Mockery::mock(TableSchemaFactory::class);
        $tables = new Tables(
            $dataBase,
            $tableCreator,
            $schemaFactory
        );
        $this->assertAttributeEquals($dataBase, 'databaseAdapter', $tables);
        $this->assertAttributeEquals($schemaFactory, 'schemaFactory', $tables);
        $this->assertAttributeEquals($tableCreator, 'tableCreator', $tables);
    }

    public function testGetTableCreator()
    {
        $dataBase = \Mockery::mock(WpDbAdapter::class);
        $tableCreator = \Mockery::mock(TableCreator::class);
        $schemaFactory = \Mockery::mock(TableSchemaFactory::class);
        $tables = new Tables(
            $dataBase,
            $tableCreator,
            $schemaFactory
        );
        $this->assertSame($tableCreator, $tables->getTableCreator());
    }

    public function testGetSchemaFactory()
    {
        $dataBase = \Mockery::mock(WpDbAdapter::class);
        $tableCreator = \Mockery::mock(TableCreator::class);
        $schemaFactory = \Mockery::mock(TableSchemaFactory::class);
        $tables = new Tables(
            $dataBase,
            $tableCreator,
            $schemaFactory
        );
        $this->assertSame($schemaFactory, $tables->getSchemaFactory());
    }

    public function testGetDatabaseAdapter()
    {
        $dataBase = \Mockery::mock(WpDbAdapter::class);
        $tableCreator = \Mockery::mock(TableCreator::class);
        $schemaFactory = \Mockery::mock(TableSchemaFactory::class);
        $tables = new Tables(
            $dataBase,
            $tableCreator,
            $schemaFactory
        );
        $this->assertSame($dataBase, $tables->getDatabaseAdapter());
    }

    public function testAddTableSchema()
    {
        $tables = new Tables(
            \Mockery::mock(WpDbAdapter::class),
            \Mockery::mock(TableCreator::class),
            \Mockery::mock(TableSchemaFactory::class)
        );
        $schema = \Mockery::mock(TableSchema::class);
        $tables->addTableSchema('spaceships', $schema);
        $this->assertAttributeEquals(['spaceships' => $schema], 'tables', $tables);
    }

    public function testGetTableSchema()
    {

        $tables = new Tables(
            \Mockery::mock(WpDbAdapter::class),
            \Mockery::mock(TableCreator::class),
            \Mockery::mock(TableSchemaFactory::class)
        );
        $schema = \Mockery::mock(TableSchema::class);
        $tables->addTableSchema('spaceships', $schema);

        $this->assertSame($schema, $tables->getTableSchema('spaceships'));
    }

    /**
     * @covers \calderawp\DB\Tables::createTable()
     * @covers \calderawp\DB\Tables::createIfNotExists()
     */
    public function testCreateTable()
    {
        $dataBase = \Mockery::mock(WpDbAdapter::class);
        $tableCreator = \Mockery::mock(TableCreator::class);
        $tableCreator
            ->shouldReceive('create_table')
            ->andReturn(true);
        $schemaFactory = \Mockery::mock(TableSchemaFactory::class);
        $tables = new Tables(
            $dataBase,
            $tableCreator,
            $schemaFactory
        );
        $schema = \Mockery::mock(TableSchema::class);

        $this->assertTrue($tables->createTable($schema));
    }


}
