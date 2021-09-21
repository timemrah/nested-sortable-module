<?php
require '../class/Request.php';
require '../class/NestedItemModel.php';
require '../class/NestedItemBusiness.php';
require '../vendor/ajaxto/ajaxto.php';

$ajaxto = new ajaxto();
$data   = [
    'categoryId' => Request::positivePUT('categoryId'),
    'groupId'    => Request::positivePUT('groupId'),
    'oldUpperId' => Request::positivePUT('oldUpperId', 0),
    'newUpperId' => Request::positivePUT('newUpperId', 0),
    'oldIndex'   => Request::positivePUT('oldIndex', 0),
    'newIndex'   => Request::positivePUT('newIndex', 0)
];


$categoryBusiness = new NestedItemBusiness();
$categoryBusiness->sort($data);


$ajaxto->resTrue('Sıralama uygulandı', 'success', $data);