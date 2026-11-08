<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="gestione.css">
    <title>Gestione</title>
</head>
<body>

    <?php
        function find(string $str, array $array){
            foreach($array as $value){
                if($value == $str)
                    return true;
            }
            return false;
        }
    ?>

    <?php 
        $file = fopen(__DIR__ . "/carrello.json","r+");

        if($file == null){
            echo "Errore";
            return;
        }

        if(filesize(__DIR__ . "/carrello.json") > 0)
            $_carrello = json_decode(fread($file, filesize(__DIR__ . "/carrello.json")), true);
        else
            $_carrello = array();
        fclose($file);

        if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])){
            unset($_carrello[$_GET["delete"]]);
            $_carrello = array_values($_carrello);
        }

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $array = array("gusti"=>$_POST["gusti"], "supporto"=> $_POST["supporto"], "toppings"=>$_POST["toppings"]);
            $_carrello[] = $array;
        }

        $file = fopen(__DIR__ . "/carrello.json", "w");

        fwrite($file, json_encode($_carrello));
        fclose($file);
    ?>

    <div>
        <a href="../index.php" class="btn">Aggiungi gelato</a>
        <a href="../pagamento/pagamento.php" class="btn">Paga</a>
    </div>
    <br>

    <?php 
    if($_carrello == null){
        echo "Carrello vuoto";
        return;
    }

    if(!is_array($_carrello)){
        echo "json non formattato in maniera corretta";
        return;
    }

    $i = 0;
    $totale = 0;
    foreach($_carrello as $item): ?>
        <form action="/gestione/gestione.php" method="get">
            <input type="hidden" name="delete" value="<?php echo $i ?>">
            <div class="card">
                <div class="card-header">
                    <h3>Gelato numero <?php echo $i + 1 ?></h3>
                    <div class="card-actions">
                        <h4>Costo: <?php 
                            $totale += 1.20 * count($item["gusti"]) + (($item["supporto"] == "Cono") ? 0.10 : 0.20) + 0.5 * count($item["toppings"]);
                            echo 1.20 * count($item["gusti"]) + (($item["supporto"] == "Cono") ? 0.10 : 0.20) + 0.5 * count($item["toppings"]);
                        ?></h4>
                        <button type="submit">Elimina</button>
                    </div>
                </div>
                <p>Gusti: <?php echo implode(",",$item["gusti"]); ?></p>
                <p>Supporto: <?php echo $item["supporto"]; ?></p>
                <p>Toppings: 
                    <?php 
                        $toppings = array();
                        if(find("panna", $item["toppings"])) $toppings[] = "Panna";
                        if(find("caramello", $item["toppings"])) $toppings[] = "Caramello";
                        if(find("cioccolato", $item["toppings"])) $toppings[] = "Cioccolato";
                        echo implode(", ", $toppings);
                    ?>
                </p>
            </div>
            <?php $i++; ?>
        </form>
    <?php endforeach; ?>
    <div class="totale">
        <h2>Totale: <?php echo $totale; ?></h2>
    </div>
</body>
</html>