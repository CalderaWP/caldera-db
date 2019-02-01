<?php

namespace calderawp\DB\Tests\Unit\CalderaPay;

use calderawp\DB\CalderaPay\Loader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class LoaderTest extends TestCase
{

	/** @covers \calderawp\DB\CalderaPay\Loader::accountMetaSchemaYamlString */
	public function testAccountMetaSchemaYamlString()
	{
		$yaml =  Loader::accountMetaSchemaYamlString();
		$parsed = Yaml::parse($yaml);
		$this->assertArrayHasKey('table', $parsed);
		$this->assertArrayHasKey('version', $parsed);
	}
}
