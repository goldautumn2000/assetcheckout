<?php
if (!defined('GLPI_ROOT')) { die("Sorry. You can't access directly to this file"); }

function plugin_version_assetcheckout() {
    return [
        'name'           => '资产出库单',
        'version'        => '1.0.0',
        'author'         => 'You',
        'license'        => 'GPLv2+',
        'homepage'       => 'https://example.com',
        'minGlpiVersion' => '10.0',
        'csrf_compliant' => true
    ];
}

function plugin_assetcheckout_check_prerequisites() {
    if (version_compare(GLPI_VERSION, '10.0', '<')) {
        echo "此插件需要 GLPI 10.0 或更高版本";
        return false;
    }
    return true;
}

function plugin_assetcheckout_check_config() { return true; }

function plugin_assetcheckout_install() {
    global $DB;
    $table = "glpi_assetcheckout";
    if (!$DB->tableExists($table)) {
        $sql = file_get_contents(__DIR__."/sql/create_table.sql");
        $DB->query($sql);
    }
    return true;
}

function plugin_assetcheckout_uninstall() {
    global $DB;
    $table = "glpi_assetcheckout";
    if ($DB->tableExists($table)) { $DB->query("DROP TABLE `$table`"); }
    return true;
}
