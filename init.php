<?php
file_put_contents(GLPI_LOG_DIR . '/debug_plugin.log', date('Y-m-d H:i:s') . " assetcheckout init.php loaded\n", FILE_APPEND);
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

global $PLUGIN_HOOKS;

// 插件目录名
$plugin = 'assetcheckout';

// 标记插件为 CSRF 兼容 ✅
$PLUGIN_HOOKS['csrf_compliant'][$plugin] = true;

// 注册一个示例菜单入口（可选）
$PLUGIN_HOOKS['menu_toadd'][$plugin] = [
    'plugins' => 'PluginAssetcheckoutMenu'
];
