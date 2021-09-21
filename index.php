<?php
$itemGroup['ID'] = 1;
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nested Sortable Module</title>
    <link type="text/css" rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
</head>
<body class="p-5">

    <div id="wrapper"
        data-group-id    = "<?=$itemGroup['ID']?>",
        data-get-action  = "ajax/get-items.php"
        data-sort-action = "ajax/sort-item.php"
    >

        <div class="list-group nested-sortable" data-upper-id="0">

        </div>

    </div>


    <div id="clone-wrapper">
        <div class="list-group-item" data-id="">
            <div>
                <div class="content"><i class="fas fa-bars menu-icon me-2"></i><span></span></div>
                <div class="btn-wrapper">
                    <button class="btn btn-sm btn-primary add-up-btn"><i class="fas fa-arrow-up"></i> Ekle</button>
                    <button class="btn btn-sm btn-primary add-down-btn"><i class="fas fa-arrow-down"></i> Ekle</button>
                    <button class="btn btn-sm btn-primary add-in-btn"><i class="fas fa-level-down-alt"></i> Ekle</button>
                    <button class="btn btn-sm btn-warning edit-btn"><i class="fas fa-pencil-alt"></i> Düzelt</button>
                    <button class="btn btn-sm btn-danger del-btn"><i class="fas fa-trash-alt"></i> Sil</button>
                </div>
            </div>
            <div class="list-group nested-sortable" data-upper-id=""></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
    <script src="vendor/ajaxto/ajaxto.js"></script>
    <script src="vendor/Sortable.min.js"></script>
    <script src="js/NestedItem.js"></script>
    <script src="js/script.js"></script>

</body>
</html>