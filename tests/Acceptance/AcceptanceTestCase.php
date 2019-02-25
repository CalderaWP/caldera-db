<?php
namespace calderawp\DB\Tests\Acceptance;

use Brain\Monkey;

use calderawp\DB\Tests\Traits\TableFactory;
use calderawp\DB\Tests\Unit\TestCase;
use Mockery;


abstract class AcceptanceTestCase extends TestCase
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
