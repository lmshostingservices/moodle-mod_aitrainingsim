<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_aitrainingsim_upgrade($oldversion) {
    if ($oldversion < 2026073100) {
        upgrade_mod_savepoint(true, 2026073100, 'aitrainingsim');
    }
    return true;
}
