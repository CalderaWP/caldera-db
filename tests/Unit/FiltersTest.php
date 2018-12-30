<?php

namespace calderawp\DB\Tests\Unit;

use calderawp\DB\Exceptions\InvalidColumnException;
use calderawp\DB\Table;
use WpDbTools\Type\GenericResult;
use WpDbTools\Type\TableSchema;
use WpDbTools\Db\Database;
use calderawp\caldera\Forms\DataSources\FormsDataSources;
use calderawp\caldera\Forms\Tests\TestCase;
use calderawp\caldera\WordPressPlugin\Filters\EntryDataFilters;

class FiltersTest extends TestCase
{
    public function testCreateEntry()
    {
        $this->markTestSkipped('Does not work yet');

        $dataSource = \Mockery::mock(FormsDataSources::class, \calderawp\caldera\Forms\Contracts\DataSourcesContract::class);
        $entryDataSource = \Mockery::mock(Table::class, \calderawp\caldera\DataSource\Contracts\SourceContract::class)
            ->shouldReceive('create')
            ->andReturn(7);


        $expectedEntry = \Mockery::mock('Entry', \calderawp\caldera\Forms\Contracts\EntryContract::class);
        $entryDataSource
            ->shouldReceive('create')
            ->andReturn(7);
        $entryDataSource
            ->shouldReceive('read')
            ->andReturn($expectedEntry);
        $dataSource
            ->shouldReceive('getEntryDataSource')
            ->andReturn($entryDataSource);


        $entryDataFilters = new EntryDataFilters(
            $this->core()->getEvents()->getHooks(),
            $dataSource
        );
        $request = \Mockery::mock('Request', \calderawp\caldera\restApi\Request::class);
        $request
            ->shouldReceive('getParam')
            ->andReturn('contact-form');
        $entry = $entryDataFilters->createEntry(null, $request);
        $this->assertSame($expectedEntry, $entry);
    }

    public function testGetEntries()
    {
        $this->markTestSkipped('Does not work yet');

    }
}
