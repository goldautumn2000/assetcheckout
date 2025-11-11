<?php
if (!defined('GLPI_ROOT')) die("Sorry. You can't access directly to this file");

class plugin_assetcheckout extends CommonDBTM {
    static function getTypeName($nb=0) { return "资产出库单"; }
    static function getMenuContent() {
        return [
            'title' => "资产出库单",
            'page'  => "/plugins/assetcheckout/front/assetcheckout.php",
            'icon'  => "fas fa-box-open"
        ];
    }
}
