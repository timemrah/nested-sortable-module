<?php
require '../class/Request.php';
require '../class/NestedItemModel.php';
require '../vendor/ajaxto/ajaxto.php';

$ajaxto = new ajaxto();

$groupId = Request::positiveGET('group_id');
if(!$groupId){
    $ajaxto->resFalse('Gurup Id belirtilmemiş');
    exit();
}

$NestedItemModel = new NestedItemModel();
$items = $NestedItemModel->getItems($groupId);

$ajaxto->resTrue('Item\'ler alındı', 'success', $items);