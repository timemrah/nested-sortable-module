<?php

class NestedItemModel{

    private $pdo;

    public function __construct($pdo = null)
    {
        if($pdo !== null){
            $this->pdo = $pdo;
        } else{
            $pdoDsn   = "mysql:host=localhost;dbname=nested_sortable_module;charset=utf8";
            $this->pdo = new \PDO($pdoDsn, 'root', '');
        }

    }


    public function getItems($groupId){

        $stmt = $this->pdo->prepare('SELECT * FROM nested_items WHERE GROUP_ID=:groupId ORDER BY ROW');
        $stmt->execute(compact('groupId'));
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }


    public function upSortCategory($categoryId, $oldIndex, $newIndex, $oldUpperId){
        /* Taşınan kategorinin yeni konumu ile eski konumu arasında kalan kategorilerin sırasını bir arttır ki
         * bu nesneler taşıdığımız kategorinin altında kalsın. */

        $stmt = $this->pdo->prepare('UPDATE nested_items SET ROW = ROW +1 WHERE ROW >= :newIndex AND ROW < :oldIndex AND UPPER_ID=:oldUpperId');
        $stmt->execute(compact(['newIndex', 'oldIndex', 'oldUpperId']));

        //Taşıdığımız kategorinin yeni sırasını set et
        $stmt = $this->pdo->prepare('UPDATE nested_items SET ROW = :newIndex WHERE ID = :categoryId');
        $stmt->execute(compact(['newIndex', 'categoryId']));
    }
    //KATEGORİYİ YUKARI TAŞI
    public function downSortCategory($categoryId, $oldIndex, $newIndex, $oldUpperId){

        /* Taşınan kategorinin yeni konumu ile eski konumu arasında kalan kategorilerin sırasını bir azalt ki
         * bu nesneler taşıdığımız kategorinin üstünde kalsın. */
        $stmt = $this->pdo->prepare('UPDATE nested_items SET ROW = ROW - 1 WHERE ROW > :oldIndex AND ROW <= :newIndex AND UPPER_ID=:oldUpperId');
        $stmt->execute(compact(['newIndex', 'oldIndex', 'oldUpperId']));

        //Taşıdığımız kategorinin yeni sırasını set et
        $stmt = $this->pdo->prepare('UPDATE nested_items SET ROW = :newIndex WHERE ID = :categoryId');
        $stmt->execute(compact('newIndex', 'categoryId'));

    }
    //KATEGORİYİ BAŞKA BOYUTA TAŞI
    public function nestedSortCategory($categoryId, $oldUpperId, $newUpperId, $oldIndex, $newIndex){

        /* Koparılan boyuttaki kategorileriden sırası kopardığımız kategoriden büyük olanların sıra değerini
         * bir azalt ki kategori altında kalan kategoriler bir yukarı sıralansın. */
        $stmt = $this->pdo->prepare('UPDATE nested_items SET ROW = ROW - 1 WHERE UPPER_ID = :oldUpperId AND ROW > :oldIndex');
        $stmt->execute(compact('oldIndex', 'oldUpperId'));

        /* Yeni girdiğimiz boyuttaki kategorileriden sırası eklediğimiz kategoriden büyük ve eşit olanların
         * sıra değerini bir arttır ki kategoriler bir aşağıya sıralansın. */
        $stmt = $this->pdo->prepare('UPDATE nested_items SET ROW = ROW +1 WHERE UPPER_ID = :newUpperId AND ROW >= :newIndex');
        $stmt->execute(compact('newUpperId', 'newIndex'));

        //Taşıdığımız kategorinin yeni sırasını set et
        $stmt = $this->pdo->prepare('UPDATE nested_items SET UPPER_ID = :newUpperId , ROW = :newIndex WHERE ID=:categoryId');
        $stmt->execute(compact(['newUpperId', 'newIndex', 'categoryId']));

    }







}