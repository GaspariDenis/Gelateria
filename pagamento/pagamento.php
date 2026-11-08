<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
</head>
<body>
    <?php 
        $file = fopen(__DIR__ . "../gestione/carrello.json","r");

        if($file == null){
            echo "Errore";
            return;
        }

        if(filesize(__DIR__ . "../gestione/carrello.json") > 0)
            $_carrello = json_decode(fread($file, filesize(__DIR__ . "/carrello.json")), true);
        else
            $_carrello = array();
        fclose($file);
    ?>
</body>
</html>