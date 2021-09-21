<?php
class NestedItemBusiness{




    public function __construct(){

    }




    public function sort($data): bool
    {

        $NestedItemModel = new NestedItemModel();

        $categoryId = $data['categoryId'];
        $oldIndex   = $data['oldIndex'];
        $newIndex   = $data['newIndex'];
        $oldUpperId = $data['oldUpperId'];
        $newUpperId = $data['newUpperId'];

        $sortType = $this->getSortType($data);
        switch ($sortType) {
            case "up"    : $NestedItemModel->upSortCategory($categoryId, $oldIndex, $newIndex, $oldUpperId);   break;
            case "down"  : $NestedItemModel->downSortCategory($categoryId, $oldIndex, $newIndex, $oldUpperId); break;
            case "nested": $NestedItemModel->nestedSortCategory($categoryId, $oldUpperId, $newUpperId, $oldIndex, $newIndex); break;
            default: return false;
        }

        return true;

    }


    public function getSortType($data) :string
    {
        if ($data['oldUpperId'] === $data['newUpperId'] && $data['oldIndex'] !== $data['newIndex']) {
            if ($data['newIndex'] < $data['oldIndex']) { return 'up';   }
            else                                       { return 'down'; }
        } else if ($data['oldUpperId'] !== $data['newUpperId']) {
            return 'nested';
        }

        return 'undefined';
    }



}