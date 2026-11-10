<?php
function find(string $str, array $array){
    foreach($array as $value){
        if($value == $str)
            return true;
    }
    return false;
}

function getPrezzo($item){
    $tot = 1.20 * count($item["gusti"]) + (($item["supporto"] == "Cono") ? 0.10 : 0.20);
    if(array_key_exists("toppings", $item) && $item["toppings"] != null)
        $tot += 0.50 * count($item["toppings"]);
    return $tot;
}

function getCarrello($mode){
    return fopen(__DIR__ . "/carrello.json", $mode);
}

function getCarrelloLength(){
    return filesize(__DIR__ . "/carrello.json");
}

function getGusti(){
    $file = fopen(__DIR__ . "/gusti.json", "r");
    if($file == null || filesize(__DIR__ . "/gusti.json") == 0){
        resetGusti();
        fclose($file);
        $file = fopen(__DIR__ . "/gusti.json", "r");
    }
    return fread($file, filesize(__DIR__ . "/gusti.json"));
}

function removeGelato($gusto){
    $file = fopen(__DIR__ . "/gusti.json", "r+");
    $gusti = json_decode(fread($file, filesize(__DIR__ . "/gusti.json")), true);
    if($gusti == null || !array_key_exists($gusto, $gusti)){
        error_log("Gusto non trovato: " . $gusto );
        return false;
    }
    fclose($file);
    if($gusti[$gusto] >= 0.11){
        $gusti[$gusto] -= 0.11;
        $file = fopen(__DIR__ . "/gusti.json", "w");
        fwrite($file, json_encode($gusti));
        fclose($file);
        return true;
    }
    return false;
}

function addGelato($gusto){
    $file = fopen(__DIR__ . "/gusti.json", "r+");
    $gusti = json_decode(fread($file, filesize(__DIR__ . "/gusti.json")), true);
    if($gusti == null || !array_key_exists($gusto, $gusti)){
        error_log("Gusto non trovato: " . $gusto . " oppure il file gusti.json e` vuoto.");
        resetGusti();
        $gusti = json_decode(fread($file, filesize(__DIR__ . "/gusti.json")), true);
    }
    fclose($file);
    if($gusti[$gusto] <= 4.89){
        $gusti[$gusto] += 0.11;
        $file = fopen(__DIR__ . "/gusti.json", "w");
        fwrite($file, json_encode($gusti));
        fclose($file);
        return true;
    }
    return false;
}

function resetGusti(){
    $file = fopen(__DIR__ . "/gusti.json", "w");
    fwrite($file, json_encode(array(
        "Vaniglia" => 5.0,
        "Cioccolato" => 5.0,
        "Menta" => 5.0,
        "Limone" => 5.0
    )));
    fclose($file);
}
?>