<?php

namespace calderawp\DB\Tests\Unit;

use calderawp\DB\Factory;
use calderawp\interop\Attribute;

class FactoryTest extends TestCase
{

	public function testTableSchema()
	{
		$attributeData = [
			'name' => 'id',
			'sqlDescriptor' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
			'format' => '%d',
		];
		$attribute2Data = [
			'name' => 'name',
			'sqlDescriptor' => 'varchar(256) NOT NULL',
			'format' => '%s',
		];
		$tableName = 'cf_whatever';
		$primaryKey = 'id';
		$indexes = ['name'];

		$factory = new Factory();
		$attributes = [$attributeData, $attribute2Data];
		$tableSchema = $factory->tableSchema($attributes, $tableName, $primaryKey, $indexes);
		$this->assertEquals($tableName, $tableSchema->name());
		$this->assertEquals(['id'], $tableSchema->primary_key());
		$expectAttribute2 = [
			'name' => 'name',
			'format' => '%s',
			'description' => 'varchar(256) NOT NULL',
			'type' => 'string',
		];
		$this->assertEquals(['name' => $expectAttribute2], $tableSchema->indices());
		$this->assertEquals($expectAttribute2, $tableSchema->schema()[ 'name' ]);
		$this->assertEquals([
			'name' => 'id',
			'format' => '%d',
			'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
			'type' => 'string',
		], $tableSchema->schema()[ 'id' ]);

	}

	public function testColumnSchema()
	{
		$attributeData = [
			'name' => 'id',
			'sqlDescriptor' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
			'format' => '%d',
		];
		$attribute = Attribute::fromArray($attributeData);
		$factory = new Factory();
		$columnSchema = $factory->columnSchema($attribute);
		$this->assertEquals([
			'name' => 'id',
			'description' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
			'format' => '%d',
		], $columnSchema);
	}

	public function testDatabaseTable()
	{
		$attributeData = [
			'name' => 'id',
			'sqlDescriptor' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
			'format' => '%d',
		];
		$attribute2Data = [
			'name' => 'name',
			'sqlDescriptor' => 'varchar(256) NOT NULL',
			'format' => '%s',
		];
		$tableName = 'cf_whatever';
		$primaryKey = 'id';
		$indexes = ['name'];

		$factory = new Factory();
		$attributes = [$attributeData, $attribute2Data];
		$tableSchema = $factory->tableSchema($attributes, $tableName, $primaryKey, $indexes);
		$databaseAdapter = \Mockery::mock('wpdb', \WpDbTools\Db\Database::class );
		$table = $factory->databaseTable($tableSchema,$databaseAdapter);
		$this->assertEquals($tableName,$table->name());

	}

	public function testWordPressDatabaseTable()
	{



		$attributeData = [
			'name' => 'id',
			'sqlDescriptor' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
			'format' => '%d',
		];
		$attribute2Data = [
			'name' => 'name',
			'sqlDescriptor' => 'varchar(256) NOT NULL',
			'format' => '%s',
		];
		$tableName = 'cf_whatever';
		$primaryKey = 'id';
		$indexes = ['name'];

		$factory = new Factory();
		$attributes = [$attributeData, $attribute2Data];
		$tableSchema = $factory->tableSchema($attributes, $tableName, $primaryKey, $indexes);
		$databaseAdapter = \Mockery::mock('wpdb', \WpDbTools\Db\Database::class );
		$table = $factory->wordPressDatabaseTable($tableSchema,$databaseAdapter);
		$this->assertEquals($tableName,$table->name());
	}
}
