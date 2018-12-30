<?php
namespace calderawp\DB\Tests\Integration;

use Brain\Monkey;

use calderawp\DB\Tests\Traits\TableFactory;
use Mockery;


abstract class AcceptenceTestCase extends \WP_UnitTestCase
{
    use TableFactory;
    /**
     * Prepares the test environment before each test.
     */
    public function setUp()
    {
        parent::setUp();
        Monkey\setUp();
    }

    /**
     * Cleans up the test environment after each test.
     */
    public function tearDown()
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
