<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riepilogo</title>
    <link rel="stylesheet" href="riepilogo.css">
    <?php include  __DIR__ . "/../utils/utils.php"; ?>
</head>
<body>
    Elenco scontrini: <br>
    <?php 
        $totaleIncassi = 0;
        $path = __DIR__ . "/../scontrini/scontrino_*.json";
        $files = glob($path);
        if(empty($files)){
            echo "Nessuno scontrino presente.";
        }else
            foreach($files as $file) : 
    ?>

        <?php 
        $f = fopen($file, "r");
        $content = json_decode(fread($f, filesize($file)), true); 
        $info = $content[count($content) - 1];
        $date = date_parse($info["data"]);
        $totaleIncassi += $info["totale"];

        echo "<div class=\"data-class\">Data: " . $date["day"] . "/" . $date["month"] . "/" . $date["year"]. "</div>";

        unset($content[count($content) - 1]);
        $content = array_values($content);

        $i = 0;
        foreach($content as $item): ?>
            <div class="card">
                <div class="card-header">
                    <h3>Gelato numero <?php echo $i + 1 ?></h3>
                    <div class="card-actions">
                    </div>
                </div>
                <p>Gusti: <?php echo implode(",",$item["gusti"]); ?></p>
                <p>Supporto: <?php echo $item["supporto"]; ?></p>
                <p>Toppings: 
                    <?php 
                        $toppings = array();
                        if($item["toppings"] != null){
                            if(find("panna", $item["toppings"])) $toppings[] = "Panna";
                            if(find("caramello", $item["toppings"])) $toppings[] = "Caramello";
                            if(find("cioccolato", $item["toppings"])) $toppings[] = "Cioccolato";
                            echo implode(", ", $toppings);
                        }else{
                            echo "Nessuno";
                        }
                    ?>
                </p>
            </div>
            <?php $i++; ?>
    <?php endforeach; ?>

    <?php endforeach; ?>    
    <br>

    <div class="card">
        <h3>Gelato rimanente:</h3><br>
        <?php 
            $piuVenduto = array("Errore");
            $minquantita = PHP_INT_MAX;
            $gusti = json_decode(getGusti(), true);
            foreach($gusti as $gusto => $quantita){
                if($quantita < $minquantita){
                    $minquantita = $quantita;
                    $piuVenduto = array($gusto);
                }else if($quantita == $minquantita){
                    $piuVenduto[] = $gusto;
                }
                echo $gusto . ": " . $quantita * 100 . " grammi<br>";
            }
        ?>
        <br>
        <h3>Gusti più venduti</h3>
        <?php 
            foreach($piuVenduto as $gusto){
                echo $gusto . "<br>";
            }
        ?>
        <br>
        <h3>Totale Incassi: <?php echo $totaleIncassi; ?></h3>
    </div>

    <form method="post" action="/index.php">
        <input type="hidden" name="reset" value="true">
        <button type="submit">Inizia nuova giornata</button>
    </form>
</body>
</html>