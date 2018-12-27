<?php


namespace calderawp\DB\Tests\Unit;

use calderawp\DB\CalderaForms\Loader;
use Symfony\Component\Yaml\Yaml;

class FormLoaderTest extends TestCase
{

	/**
	 * @covers \calderawp\DB\CalderaForms\Loader::formsSchemaYamlString()
	 */
	public function testFormsSchemaIsString()
	{
		$this->assertInternalType('string', Loader::formsSchemaYamlString());
	}

	/**
	 * @covers  \calderawp\DB\CalderaForms\Loader::formEntrySchemaYamlString()
	 */
	public function testFormEntrySchemaYamlStringIsString()
	{
		$this->assertInternalType('string', Loader::formEntrySchemaYamlString());
	}

	/**
	 * @covers  \calderawp\DB\CalderaForms\Loader::formsSchemaYamlString()
	 */
	public function testFormEntryValuesSchemaYamlStringIsString()
	{
		$this->assertInternalType('string', Loader::formsSchemaYamlString());
	}

	/**
	 * @covers  \calderawp\DB\CalderaForms\Loader::formEntryMetaSchemaYamlString()
	 */
	public function testFormEntryMetaSchemaYamlStringIsString()
	{
		$this->assertInternalType('string', Loader::formEntryMetaSchemaYamlString());
	}

	/**
	 * @covers  \calderawp\DB\CalderaForms\Loader::formsSchemaYamlString()
	 */
	public function testFormsSchemaHasTablesAndVersion()
	{
		$yaml =  Loader::formsSchemaYamlString();
		$parsed = Yaml::parse($yaml);
		$this->assertArrayHasKey('table', $parsed);
		$this->assertArrayHasKey('version', $parsed);
	}

	/**
	 * @covers \calderawp\DB\CalderaForms\Loader::formEntrySchemaYamlString()
	 */
	public function testFormEntrySchemaYamlStringTablesAndVersion()
	{
		$yaml =  Loader::formsSchemaYamlString();
		$parsed = Yaml::parse($yaml);
		$this->assertArrayHasKey('table', $parsed);
		$this->assertArrayHasKey('version', $parsed);
	}

	/**
	 * @covers  \calderawp\DB\CalderaForms\Loader::formsSchemaYamlString()
	 */
	public function testFormEntryValuesSchemaYamlStringTablesAndVersion()
	{
		$yaml = Loader::formsSchemaYamlString();
		$parsed = Yaml::parse($yaml);
		$this->assertArrayHasKey('table', $parsed);
		$this->assertArrayHasKey('version', $parsed);
	}

	/**
	 * @covers \calderawp\DB\CalderaForms\Loader::formEntryMetaSchemaYamlString()
	 */
	public function testFormEntryMetaSchemaYamlStringTablesAndVersion()
	{
		$yaml =  Loader::formEntryMetaSchemaYamlString();
		$parsed = Yaml::parse($yaml);
		$this->assertArrayHasKey('table', $parsed);
		$this->assertArrayHasKey('version', $parsed);
	}
}
