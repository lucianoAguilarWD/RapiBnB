<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RapiBnB</title>

    <script>
        var URL_PATH = '<?= URL_PATH ?>';
    </script>

    <!--------------------Css Personalizado------------------------------>
    <link rel="stylesheet" href="<?=URL_PATH?>/Assets/css/styles.css">

    <!--------------------Script Personalizado------------------------------>
    <script src="<?=URL_PATH?>/Assets/js/scripts.js"></script>
    
</head>
<body>
    <header></header>

    <?php echo $content ?>

    <footer></footer>
</body>
</html>