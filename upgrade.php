<?php
 
function xmldb_mymodule_upgrade($oldversion) {
    global $CFG;
 
    $result = TRUE;
 
    if ($oldversion < 2019062801) {

        // Define table tool_password to be created.
        $table = new xmldb_table('tool_password');

        // Adding fields to table tool_password.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('changetime', XMLDB_TYPE_INTEGER, '12', null, null, null, '0');

        // Adding keys to table tool_password.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for tool_password.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Password savepoint reached.
        upgrade_plugin_savepoint(true, 2019062801, 'tool', 'password');
    }

 
    return $result;
}