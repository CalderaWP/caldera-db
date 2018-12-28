<?php

namespace calderawp\DB\CalderaForms;

class Loader
{
    /**
     * Get forms table schema as string
     *
     * @return string
     */
    public static function formsSchemaYamlString()
    {
        return file_get_contents(__DIR__ . '/forms.yml', true);
    }

    /**
     * Get forms entry table schema as string
     *
     * @return string
     */
    public static function formEntrySchemaYamlString()
    {
        return file_get_contents(__DIR__ . '/form_entries.yml', true);
    }

    /**
     * Get forms entry values table schema as string
     *
     * @return string
     */
    public static function formEntryValuesSchemaYamlString()
    {
        return file_get_contents(__DIR__ . '/form_entry_values.yml', true);
    }


    /**
     * Get forms entry meta table schema as string
     *
     * @return string
     */
    public static function formEntryMetaSchemaYamlString()
    {
        return file_get_contents(__DIR__ . '/form_entry_meta.yml', true);
    }
}
