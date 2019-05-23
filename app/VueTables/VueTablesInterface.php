<?php
/**
 *  VueTables server-side component interface
 */
namespace App\VueTables;

interface VueTablesInterface
{
    public function get($table, array $fields);
}
