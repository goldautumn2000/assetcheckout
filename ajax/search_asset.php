<?php
include ('../../../inc/includes.php');
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
if ($q === '') { echo json_encode([]); exit; }

global $DB;
$tables = [
    'glpi_computers' => '计算机',
    'glpi_monitors' => '显示器',
    'glpi_printers' => '打印机',
    'glpi_networkequipments' => '网络设备'
];

$results = [];
foreach ($tables as $table => $type) {
    $sql = "SELECT id, name, serial, otherserial FROM $table 
            WHERE name LIKE '%$q%' OR serial LIKE '%$q%' OR otherserial LIKE '%$q%'";
    $res = $DB->query($sql);
    while ($row = $DB->fetch_assoc($res)) {
        $results[] = [
            'id' => $row['id'],
            'type' => $type,
            'name' => $row['name'],
            'serial' => $row['serial'],
            'otherserial' => $row['otherserial']
        ];
    }
}
echo json_encode($results);
