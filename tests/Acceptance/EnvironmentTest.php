<?php

namespace calderawp\DB\Tests\Acceptance;

use calderawp\DB\Tests\Unit\TestCase;

/**
 * Class EnvironmentTest
 *
 * Make sure the test environment is trustworthy for integration testing.
 */
class EnvironmentTest extends TestCase
{

    /**
     * Tests work?
     */
    public function testSample()
    {
        $this->assertTrue(true);
    }

    /**
     * Can we use WordPress to get things in and out of database?
     */
    public function testDatabaseWorks()
    {
        $id = wp_insert_post(
            [
            'post_title' => 'Hi Roy',
            'post_content' => 'Hi Roy',
            ]
        );

        $this->assertNotEmpty(get_post($id));
    }
}
