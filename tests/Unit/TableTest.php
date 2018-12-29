<?php

namespace calderawp\DB\Tests\Unit;

use calderawp\DB\Exceptions\InvalidColumnException;
use calderawp\DB\Table;
use WpDbTools\Type\GenericResult;
use WpDbTools\Type\TableSchema;
use WpDbTools\Db\Database;

class TableTest extends TestCase
{
	/**
	 * @covers \calderawp\DB\Table::implodeIn()
	 */
	public function testImplodeIn()
	{
		$database = \Mockery::mock('Database', Database::class);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		$table = new Table($database, $tableSchema);
		$this->assertEquals('1,2,3', $table->implodeIn([1, 2, '3']));
	}

	public function test__construct()
	{
		$database = \Mockery::mock('Database', Database::class);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		$table = new Table($database, $tableSchema);
		$this->assertAttributeEquals($database, 'database', $table);
		$this->assertAttributeEquals($tableSchema, 'tableSchema', $table);
	}


	/**
	 * @covers \calderawp\DB\Table::isAllowedKey()
	 * @covers \calderawp\DB\Table::getColumnAttributes()
	 */
	public function testGetColumnAttributes()
	{

		$database = \Mockery::mock('Database', Database::class);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		$tableSchema
			->shouldReceive('schema')
			->andReturn(['spaceships' => 1]);
		$table = new Table($database, $tableSchema);
		$this->assertTrue($table->isAllowedKey('spaceships'));
		$this->assertFalse($table->isAllowedKey('noms'));
	}


	/**
	 * @covers \calderawp\DB\Table::isPrimaryKey()
	 */
	public function testIsPrimaryKey()
	{
		$database = \Mockery::mock('Database', Database::class);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['spaceships']);
		$tableSchema
			->shouldReceive('schema')
			->andReturn(['spaceships' => 1]);
		$table = new Table($database, $tableSchema);
		$this->assertTrue($table->isPrimaryKey('spaceships'));
		$this->assertFalse($table->isPrimaryKey('noms'));
	}


	/**
	 * @covers \calderawp\DB\Table::getPrimaryKey()
	 */
	public function testGetPrimaryKey()
	{
		$database = \Mockery::mock('Database', Database::class);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['spaceships']);
		$table = new Table($database, $tableSchema);
		$this->assertEquals('spaceships', $table->getPrimaryKey());

	}


	/**
	 * @covers \calderawp\DB\Table::getTableName()
	 */
	public function testGetTableName()
	{
		$database = \Mockery::mock('Database', Database::class);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');
		$table = new Table($database, $tableSchema);
		$this->assertEquals('spaceships', $table->getTableName());

	}

	/**
	 * @covers \calderawp\DB\Table::getSchema()
	 */
	public function testGetSchema()
	{
		$database = \Mockery::mock('Database', Database::class);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');
		$table = new Table($database, $tableSchema);
		$this->assertEquals($tableSchema, $table->getSchema());
	}

	/**
	 * @covers \calderawp\DB\Table::getTableName()
	 */
	public function testCreate()
	{
		$database = \Mockery::mock('Database', Database::class);
		$expectedResult = 7;
		$database
			->shouldReceive(
				'query_statement'
			);
		$database
			->shouldReceive('last_insert_id')
			->andReturn($expectedResult);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);

		//Table name
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');

		//Schema
		$tableSchema
			->shouldReceive('schema')
			->andReturn(
				[
					'id' => [
						'name' => 'id',
						'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
						'format' => '%d',
					],
					'ship_name' => [
						'name' => 'ship_name',
						'description' => 'varchar(18) NOT NULL DEFAULT',
						'format' => '%s',
					],

				]
			);
		//Primary key
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['id']);
		$table = new Table($database, $tableSchema);
		$this->assertEquals(
			$expectedResult,
			$table->create(['ship_name' => 'Enterprise'])
		);
	}

	/**
	 * @covers \calderawp\DB\Table::findById()
	 * @covers \calderawp\DB\Table::read()
	 */
	public function testFindById()
	{
		$database = \Mockery::mock('Database', Database::class);
		$expectedResult = new GenericResult(new \stdClass());
		$database
			->shouldReceive(
				'query_statement'
			)
			->andReturn($expectedResult);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);

		//Table name
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');

		//Schema
		$tableSchema
			->shouldReceive('schema')
			->andReturn(
				[
					'id' => [
						'name' => 'id',
						'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
						'format' => '%d',
					],
					'ship_name' => [
						'name' => 'ship_name',
						'description' => 'varchar(18) NOT NULL DEFAULT',
						'format' => '%s',
					],

				]
			);
		//Primary key
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['id']);
		$table = new Table($database, $tableSchema);
		$this->assertEquals(
			$expectedResult,
			$table->findById(7)
		);
		$this->assertEquals(
			$expectedResult,
			$table->read(7)
		);
	}

	/**
	 * @covers \calderawp\DB\Table::update()
	 */
	public function testUpdate()
	{
		$database = \Mockery::mock('Database', Database::class);
		$expectedResult = new GenericResult(new \stdClass());
		$database
			->shouldReceive(
				'query_statement'
			)
			->andReturn($expectedResult);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);

		//Table name
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');

		//Schema
		$tableSchema
			->shouldReceive('schema')
			->andReturn(
				[
					'id' => [
						'name' => 'id',
						'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
						'format' => '%d',
					],
					'ship_name' => [
						'name' => 'ship_name',
						'description' => 'varchar(18) NOT NULL DEFAULT',
						'format' => '%s',
					],

				]
			);
		//Primary key
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['id']);
		$table = new Table($database, $tableSchema);
		$this->assertEquals(
			$expectedResult,
			$table->update(7, ['ship_name' => 'Enterprise'])
		);

	}


	public function testFindWhere()
	{
		$database = \Mockery::mock('Database', Database::class);
		$expectedResult = new GenericResult(new \stdClass());
		$database
			->shouldReceive(
				'query_statement'
			)
			->andReturn($expectedResult);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);

		//Table name
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');

		//Schema
		$tableSchema
			->shouldReceive('schema')
			->andReturn(
				[
					'id' => [
						'name' => 'id',
						'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
						'format' => '%d',
					],
					'ship_name' => [
						'name' => 'ship_name',
						'description' => 'varchar(18) NOT NULL DEFAULT',
						'format' => '%s',
					],

				]
			);
		//Primary key
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['id']);
		$table = new Table($database, $tableSchema);
		$this->assertEquals(
			$expectedResult,
			$table->findWhere('ship_name', 'Enterprise')
		);
	}

	/**
	 * @covers \calderawp\DB\Table::findWhere()
	 * @covers \calderawp\DB\Table::whereColumns()
	 */
	public function testFindWhereInvalidColumn()
	{
		$database = \Mockery::mock('Database', Database::class);

		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);

		//Table name
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');

		//Schema
		$tableSchema
			->shouldReceive('schema')
			->andReturn(
				[
					'id' => [
						'name' => 'id',
						'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
						'format' => '%d',
					],
					'ship_name' => [
						'name' => 'ship_name',
						'description' => 'varchar(18) NOT NULL DEFAULT',
						'format' => '%s',
					],
				]
			);
		//Primary key
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['id']);
		$table = new Table($database, $tableSchema);


		$this->expectException(InvalidColumnException::class);
		$table->findWhere('invalid_column', 'bats');
	}

	/**
	 * @covers \calderawp\DB\Table::delete()
	 */
	public function testDelete()
	{
		$database = \Mockery::mock('Database', Database::class);
		$expectedResult = new GenericResult(new \stdClass());
		$database
			->shouldReceive(
				'query_statement'
			)
			->andReturn($expectedResult);

		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);
		//Primary key
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['spaceships']);
		//Table name
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');
		$table = new Table($database, $tableSchema);
		$this->assertEquals('spaceships', $table->getPrimaryKey());
		$this->assertSame(true, $table->delete(5));
	}

	/**
	 * @covers \calderawp\DB\Table::anonymize()
	 */
	public function testAnonymize()
	{
		$database = \Mockery::mock('Database', Database::class);
		$expectedResult = new GenericResult(new \stdClass());
		$database
			->shouldReceive(
				'query_statement'
			)
			->andReturn($expectedResult);
		$tableSchema = \Mockery::mock('TableSchema', TableSchema::class);

		//Table name
		$tableSchema
			->shouldReceive('name')
			->andReturn('spaceships');

		//Schema
		$tableSchema
			->shouldReceive('schema')
			->andReturn(
				[
					'id' => [
						'name' => 'id',
						'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
						'format' => '%d',
					],
					'ship_name' => [
						'name' => 'ship_name',
						'description' => 'varchar(18) NOT NULL DEFAULT',
						'format' => '%s',
					],

				]
			);
		//Primary key
		$tableSchema
			->shouldReceive('primary_key')
			->andReturn(['id']);
		$table = new Table($database, $tableSchema);
		$this->assertEquals(
			$expectedResult,
			$table->anonymize(7, 'ship_name')
		);
	}

}
