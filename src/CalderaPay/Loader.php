<?php


namespace calderawp\DB\CalderaPay;


class Loader
{

    /**
     * Get account meta table schema as string
     *
     * @return string
     */
    public static function accountMetaSchemaYamlString()
    {
        return file_get_contents(__DIR__ . '/account_meta.yml', true);
    }
}
