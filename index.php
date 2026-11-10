<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Gelato</title>

     <link rel="stylesheet" href="index.css">
     <?php include __DIR__ . "/utils/utils.php"; ?>
</head>
<body>

    <?php 
        if(isset($_POST["reset"]) && $_POST["reset"]){
            resetGusti();
            resetCarrello();
            resetScontrini();
        }
    ?>

    <form method="post">
        <input type="number" name="NPalline" min=1 max=3 value=<?php 
        if(!isset($_POST["NPalline"]))
            echo 0;
        else
            echo $_POST["NPalline"];
        ?>>
        <button type="submit">Conferma</button>
    </form>

    <br>

    <?php
        $numeroPalline = $_POST["NPalline"];
        if($numeroPalline > 0 && $numeroPalline <= 3):
    ?>
        <br>
        Scegli i gusti <br>

        <form method="post" action="gestione/gestione.php">

        <?php for($i = 1; $i <= $numeroPalline; $i++): ?>

            <label>Pallina <?php echo $i; ?></label>
            <select name="gusti[]">
            
            <?php
            $gustiInFile = getGusti();
            $gusti = json_decode($gustiInFile, true);
            foreach($gusti as $gusto => $quantita):
                if($quantita >= 0.11):
            ?>
            
                <option value="<?php echo $gusto; ?>"><?php echo $gusto; ?> - <?php echo $quantita * 1000; ?></option>
            <?php endif; endforeach; ?>
            </select>
        <br>

        <?php endfor ?>

            Seleziona il supporto: <br>
            <input type="radio" name="supporto" value="Cono"> Cono <br>
            <input type="radio" name="supporto" value="Coppetta"> Coppetta
            <br><br>
 
            Seleziona Toppings: <br>
            <input type="checkbox" name="toppings[]" value="panna">
            Panna
            <br>
            <input type="checkbox" name="toppings[]" value="caramello">
            Caramello
            <br>
            <input type="checkbox" name="toppings[]" value="cioccolato">
            Cioccolato
            <br>

        <button type="submit">Inserisci</button>

        </form>
    <?php endif ?>

    <form method="get" action="/riepilogo/riepilogo.php">
        <button type="submit">Riepilogo Finale</button>
    </form>

</body>
</html>