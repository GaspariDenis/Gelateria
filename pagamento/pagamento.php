<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
    <link rel="stylesheet" href="pagamento.css">
    <?php 
        include __DIR__ . "/../utils/utils.php";
    ?>
</head>
<body>
    <?php 
        $file = getCarrello("r");

        if($file == null){
            echo "Errore";
            return;
        }

        if(getCarrelloLength() > 0)
            $_carrello = json_decode(fread($file, getCarrelloLength()), true);
        else
            $_carrello = array();
        fclose($file);
    ?>

    <?php 
        if(isset($_POST["pagamento"])): ?>

        <?php 
            generaScontrino($_POST["pagamento"]);
            $file = getCarrello("w");
            fwrite($file, json_encode(array()));
            fclose($file);
        ?>

        <div>
            <p>Pagamento avvenuto con successo!</p>
            <h4>Resto dovuto: <?php echo $_POST["importo"] - $_POST["pagamento"] ?></h4>
        </div>

        <form method="get" action="/index.php">
            <button type="submit">Torna alla home</button>
        </form>

        <form method="post" action="/riepilogo/riepilogo.php">
            <button type="submit">Riepilogo Finale</button>
        </form>

    <?php else: ?>

        <h1>Pagamento</h1>

    <div>
        <h2>Riepilogo Ordine</h2>
        <ul>
            <?php 
                $totale = 0;
                $i = 0;
                foreach($_carrello as $prodotto): ?>
                    <li>Gelato numero <?php echo $i + 1 ?></li>
                    Prezzo: <?php echo getPrezzo($prodotto); ?>
                    
                    <?php 
                        $totale += getPrezzo($prodotto);
                        $i++; 
                    ?>
            <?php endforeach; ?>
            
        </ul>
        <p><strong>Totale: <?php echo $totale; ?>€</strong></p>
    </div>

    <div>
        <form method="post" action="/pagamento/pagamento.php">
            <input type="hidden" name="pagamento" value="<?php echo $totale ?>">
            Soldi ricevuti: <input type="number" name="importo" min=<?php echo $totale ?> step="any"><br>
            <button type="submit">Procedi al pagamento</button>
        </form>
    </div>

    <form method="get" action="/gestione/gestione.php">
        <button type="submit">Torna all'ordine</button>
    </form>

    <?php endif; ?>
</body>
</html>