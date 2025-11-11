<?php
include ('../../../inc/includes.php');
include_once __DIR__ . '/setup.php';
Session::checkRight("config", READ);
global $DB;

// 多语言加载
$user_lang = $_SESSION['glpi_language'] ?? 'zh_CN';
$lang_file = __DIR__."/../lang/{$user_lang}.php";
$trans = file_exists($lang_file) ? include($lang_file) : include(__DIR__."/../lang/zh_CN.php");

// 当前登录用户
$currentUserID = Session::getLoginUserID();
$currentUser = getUserName($currentUserID);
function getUserName($userID) {
    global $DB;
    $query = "SELECT firstname, realname FROM glpi_users WHERE id=" . intval($userID);
    $res = $DB->query($query);
    if ($row = $DB->fetch_assoc($res)) return trim($row['firstname'].' '.$row['realname']);
    return 'Unknown';
}

// 生成唯一签收编号
function generateCheckoutNumber($DB) {
    $date = date('Ymd');
    $sql = "SELECT checkout_number FROM glpi_assetcheckout 
            WHERE checkout_number LIKE 'OUT-$date-%' 
            ORDER BY checkout_number DESC LIMIT 1";
    $res = $DB->query($sql);
    $seq = 1;
    if ($row = $DB->fetch_assoc($res)) {
        $last = $row['checkout_number'];
        $parts = explode('-', $last);
        $seq = intval(end($parts)) + 1;
    }
    return sprintf("OUT-%s-%03d", $date, $seq);
}

// 保存出库单
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['generate'])){
    $receiver = $_POST['receiver'] ?? '';
    $department = $_POST['department'] ?? '';
    $remark = $_POST['remark'] ?? '';
    $assets_json = $_POST['assets_json'] ?? '[]';

    $checkout_number = generateCheckoutNumber($DB);
    $checkout_date = date('Y-m-d H:i:s');
    $checkout_user_id = $currentUserID;

    $sql = "INSERT INTO glpi_assetcheckout 
            (checkout_number, checkout_date, checkout_user_id, receiver, department, remark, assets_json)
            VALUES ('".addslashes($checkout_number)."','$checkout_date',$checkout_user_id,'".addslashes($receiver)."','".addslashes($department)."','".addslashes($remark)."','".addslashes($assets_json)."')";
    $DB->query($sql);

    echo "<p style='color:green;'>{$trans['checkout_saved']} $checkout_number</p>";
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title><?php echo $trans['assetcheckout']; ?></title>
<link rel="stylesheet" href="../css/style.css">
<script>
// JavaScript 前端逻辑，搜索资产、添加、打印（与之前代码相同）
</script>
</head>
<body>
<div class="container">
<img src="../pics/logo.png" alt="公司LOGO" style="height:60px; display:block;margin:auto;">
<h2><?php echo $trans['assetcheckout']; ?></h2>

<form id="checkoutForm" method="post">
<label><?php echo $trans['receiver']; ?>：</label><input type="text" name="receiver" placeholder="<?php echo $trans['receiver']; ?>"><br>
<label><?php echo $trans['department']; ?>：</label><input type="text" name="department" placeholder="<?php echo $trans['department']; ?>"><br>
<label><?php echo $trans['checkout_user']; ?>：</label><input type="text" value="<?php echo $currentUser;?>" readonly><br>
<label><?php echo $trans['remark']; ?>：</label><input type="text" name="remark" placeholder="<?php echo $trans['remark']; ?>"><br>

<h3><?php echo $trans['search_asset']; ?></h3>
<div id="searchContainer">
    <div>
        <input type="text" placeholder="资产编号/序列号/姓名查询" onkeyup="searchAsset(this, this.nextElementSibling)">
        <div class="result"></div>
    </div>
</div>
<button type="button" onclick="addSearchRow()"><?php echo $trans['add_search']; ?></button>
<button type="button" onclick="addSelectedAssets()"><?php echo $trans['add_selected_assets']; ?></button>

<h3><?php echo $trans['checkout_assets']; ?></h3>
<table>
<thead><tr>
<th>序号</th>
<th><?php echo $trans['asset_number']; ?></th>
<th><?php echo $trans['asset_type']; ?></th>
<th><?php echo $trans['asset_name']; ?></th>
<th><?php echo $trans['serial_number']; ?></th>
<th><?php echo $trans['delete']; ?></th>
</tr></thead>
<tbody id="assetTableBody"></tbody>
</table>

<input type="hidden" name="assets_json" id="assets_json">
<button type="button" onclick="printCheckout()"><?php echo $trans['print_checkout']; ?></button>
<input type="hidden" name="generate" value="1">
</form>
</div>
</body>
</html>
