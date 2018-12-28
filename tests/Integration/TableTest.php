<?php

namespace calderawp\DB\Tests\Integration;

use calderawp\DB\Table;

class TableTest extends IntegrationTestCase
{
    /**
     * @covers \calderawp\DB\Table::getColumnAttributes()
     */
    public function testGetColumnAttributes()
    {

        $table = $this->formsTableFactory();
        $this->assertInternalType('array', $table->getColumnAttributes());
    }

    /**
     * @covers \calderawp\DB\Table::getColumnAttributes()
     */
    public function testGetColumnAttribute()
    {

        $table = $this->formsTableFactory();
        $this->assertInternalType('array', $table->getColumnAttribute('form_id'));
    }

    /**
     * @covers \calderawp\DB\Table::isPrimaryKey()
     */
    public function testIsPrimaryKey()
    {

        $table = $this->formsTableFactory();
        $this->assertTrue($table->isPrimaryKey('id'));
        $this->assertFalse($table->isPrimaryKey('form_id'));
        $this->assertFalse($table->isPrimaryKey('sandwich'));
    }


    /**
     * @covers \calderawp\DB\Table::implodeIn()
     */
    public function testImplodeIn()
    {
        $ids = [
        1, 2
        ];
        $this->assertSame(
            $this->formsTableFactory()->implodeIn($ids),
            '1,2'
        );
    }

    /**
     * @covers \calderawp\DB\Table::getTableName()
     */
    public function testGetTableName()
    {
        $this->assertInternalType(
            'string',
            $this->formsTableFactory()->getTableName()
        );
    }


    /**
     * @covers \calderawp\DB\Table::isAllowedKey()
     */
    public function testIsValidColumn()
    {
        $this->assertTrue(
            $this->formsTableFactory()
                ->isAllowedKey('form_id')
        );
    }

    /**
     * @covers \calderawp\DB\Table::valuesString()
     * @covers \calderawp\DB\Table::allowedDataOnly()
     */
    public function testValuesString()
    {
        $data = [
        'id' => 211,
        'config' => json_encode(['fields' => [], 'ID' => 'CF1']),
        'form_id' => 'CF1',
        ];
        $string = $this->formsTableFactory()->valuesString($data);

        $this->assertSame('%d, %s, %s', $string);
    }

    /**
     * @covers \calderawp\DB\Table::valuesString()
     * @covers \calderawp\DB\Table::allowedDataOnly()
     */
    public function testValuesStringInvalid()
    {
        $data = [
        'form_id' => 'CF1',
        'fake' => 'ATTACK'
        ];
        $string = $this->formsTableFactory()->valuesString($data);
        $this->assertFalse(strpos($string, 'ATTACK'));
    }

    /**
     * @covers \calderawp\DB\Table::values()
     * @covers \calderawp\DB\Table::allowedDataOnly()
     */
    public function testValues()
    {
        $data = [
        'form_id' => 'CF1',
        ];
        $prepared = $this->formsTableFactory()->values($data);
        $this->assertSame($data['form_id'], $prepared[0]);
    }

    /**
     * @covers \calderawp\DB\Table::values()
     * @covers \calderawp\DB\Table::allowedDataOnly()
     * @covers \calderawp\DB\Table::isPrimaryKey()
     * @covers \calderawp\DB\Table::getPrimaryKey()
     * @covers \calderawp\DB\Table::allowedKeysFromData()
     */
    public function testValuesWithoutPrimary()
    {
        $data = [
        'form_id' => 'CF1',
        'id' => '2',
        ];
        $prepared = $this->formsTableFactory()->values($data, false);
        $this->assertCount(1, $prepared);
    }

    /**
     * @covers \calderawp\DB\Table::values()
     * @covers \calderawp\DB\Table::allowedDataOnly()
     */
    public function testValuesInvalid()
    {
        $data = [
        'form_id' => 'CF1',
        'fake' => 'SURPRISE'
        ];
        $prepared = $this->formsTableFactory()->values($data);
        $this->assertFalse(in_array('SURPRISE', $prepared));
    }


    /**
     * @covers \calderawp\DB\Table::create()
     * @covers \calderawp\DB\Table::whereStatement()
     * @covers \calderawp\DB\Table::getPrimaryKey()
     */
    public function testCreate()
    {
        $table = $this->formsTableFactory();
        $data = [
        'id' => 2,
        'config' => json_encode(['fields' => [], 'ID' => 'CF1']),
        'form_id' => 'CF1',
        'type' => 'primary'
        ];
        $newId = $table->create($data);
        $this->assertTrue(is_numeric($newId));
    }

    /**
     * @covers \calderawp\DB\Table::read()
     * @covers \calderawp\DB\Table::whereStatement()
     * @covers \calderawp\DB\Table::getPrimaryKey()
     */
    public function testRead()
    {
        $table = $this->formsTableFactory();
        $data = [
        'id' => 2,
        'config' => json_encode(['fields' => [], 'ID' => 'CF2']),
        'form_id' => 'CF2',
        'type' => 'revision'
        ];
        $newId = $table->create($data);
        $result = $table->read($newId);
        $first = $result[0];
        $this->assertSame($data['form_id'], $first->form_id);
        $this->assertSame($data['type'], $first->type);
    }

    /**
     * @covers \calderawp\DB\Table::update()
     * @covers \calderawp\DB\Table::whereStatement()
     * @covers \calderawp\DB\Table::getPrimaryKey()
     */
    public function testUpdate()
    {
        $table = $this->formsTableFactory();
        $data = [
        'id' => 22,
        'config' => json_encode(['fields' => [], 'ID' => 'CF2']),
        'form_id' => 'CF2',
        'type' => 'revision'
        ];
        $newId = $table->create($data);
        $data = (array)$table->read($newId);
        $data['type'] = 'primary';
        $table->update($newId, $data);
        $data = $table->read($newId);
        $data = $data[0];
        $this->assertSame('primary', $data->type);
    }

    /**
     * @group  now
     * @covers \calderawp\DB\Table::update()
     * @covers \calderawp\DB\Table::whereStatement()
     * @covers \calderawp\DB\Table::getPrimaryKey()
     */
    public function testAnnonymize()
    {
        $table = $this->formsTableFactory();
        $data = [
        'id' => 23,
        'config' => json_encode(['fields' => [], 'ID' => 'CF2']),
        'form_id' => 'CF2',
        'type' => 'revision'
        ];
        $newId = $table->create($data);

        $table->anonymize($newId, 'type');
        $results = $table->read($newId);
        $data = $results[0];
        $this->assertSame(Table::ANNONYMIZER, $data->type);
    }


    /**
     * @covers \calderawp\DB\Table::findById()
     * @covers \calderawp\DB\Table::getPrimaryKey()
     */
    public function testFindById()
    {
        $table = $this->formsTableFactory();
        $data = [
        'id' => 24,
        'config' => json_encode(['fields' => [], 'ID' => 'CF2']),
        'form_id' => 'CF2',
        'type' => 'revision'
        ];
        $newId = $table->create($data);
        $result = $table->findById($newId);
        $first = $result[0];
        $this->assertSame($data['form_id'], $first->form_id);
        $this->assertSame($data['type'], $first->type);
    }

    /**
     * @covers \calderawp\DB\Table::whereColumns()
     * @covers \calderawp\DB\Table::findWhere()
     * @covers \calderawp\DB\Table::whereStatement()
     */
    public function testFindByWhere()
    {
        $table = $this->formsTableFactory();
        $data = [
        'form_id' => 'CF3',
        'type' => 'revision'
        ];
        $table->create($data);
        $data['type'] = 'primary';
        $table->create($data);
        $data['form_id'] = 'cfX';
        $table->create($data);
        $this->assertSame(
            2, count(
                $table->findWhere('form_id', 'CF3')
            )
        );
    }

    /**
     * @covers \calderawp\DB\Table::delete()
     */
    public function testDelete()
    {
        $table = $this->formsTableFactory();
        $data = [
        'form_id' => 'CF4',
        'type' => 'revision'
        ];
        $newId = $table->create($data);
        $table->delete($newId);

        $this->assertEmpty($table->findById($newId));
    }
}
