<?php


namespace calderawp\DB\Tests\Traits;

use calderawp\DB\CalderaForms\Loader;
use calderawp\DB\Table;
use calderawp\DB\Tables;

trait TableFactory
{

    /**
     * @return Table
     */
    protected function formsTableFactory()
    {
        $tables = new Tables();
        $tables->addTableSchema('forms', Loader::formsSchemaYamlString());
        $table = new Table(
            $tables->getDatabaseAdapter(),
            $tables->getTableSchema('forms')
        );
        return $table;
    }
}
