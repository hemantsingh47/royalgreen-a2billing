<?php
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
    header("Location: billing_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
}

$DBHandle  = DbConnect();

$card_table = new Table('cc_card','*');
$card_clause = "id = ".$id;
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
$card = $card_result[0];

if (empty($card)) {
    header("Location: billing_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
}

// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_info_customer;

?>