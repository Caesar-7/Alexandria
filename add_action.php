<!--
    Autore: Giuseppe Mariotti
    Data:   30/03/2023
-->

<?php
    require_once("./conf.php");

    // --- CONNESSIONE ---
    $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, "Alexandria");
    if ($connection->connect_error) { die("Errore di connessione al database"); }

    // --- INPUT ---
    $array_tipi = array(
        "titolo" => "s",
        "autore" => "s",
        "editore" => "s",
        "pagine" => "i",
        "lingua_originale" => "s",
        "stato_lettura" => "i",
        "anno_pubblicazione" => "i",
        "anno_stampa" => "i",
        "prezzo" => "d",
        "isbn" => "s",
        "traduttore" => "s",
        "titolo_originale" => "s"
    );

    $array_campi = array(
        "titolo" => null,
        "autore" => null,
        "editore" => null,
        "pagine" => null,
        "lingua_originale" => null,
        "stato_lettura" => null,
        "anno_pubblicazione" => null,
        "anno_stampa" => null,
        "prezzo" => null
    );

    foreach ($array_campi as $nome_campo => $valore)
    {
        if (!isset($_POST[$nome_campo]))
        {
            die("<h1>Uno o più parametri essenziali sono assenti</h1>");
        }
        else
        {
            $array_campi[$nome_campo] = $_POST[$nome_campo];
        }
    }

    if (isset($_POST["isbn"])) { $array_campi["isbn"] = $_POST["isbn"]; }

    if (isset($_POST["traduttore"])) { $array_campi["traduttore"] = $_POST["traduttore"]; }

    if (isset($_POST["titolo_originale"])) { $array_campi["titolo_originale"] = $_POST["titolo_originale"]; }

    // INSERIRE GESTIONE FILE IMMAGINE COPERTINA LIBRO

    // --- QUERY ---
    $stringa_campi = "";
    $stringa_placeholder = "";
    $stringa_tipi = "";

    // Genera la lista di campi e placeholder in base all'array di campi inseriti
    foreach ($array_campi as $nome_campo => $valore_campo)
    {
        $stringa_campi .= ", $nome_campo";
        $stringa_placeholder .= ", ?";
        $stringa_tipi .= $array_tipi[$nome_campo];
    }

    // Rimuove la virgola e lo spazio precedenti il primo campo
    $stringa_campi = substr($stringa_campi, 2);
    $stringa_placeholder = substr($stringa_placeholder, 2);

    $statement = $connection->prepare(
        "INSERT INTO libri ($stringa_campi) VALUES ($stringa_placeholder);"
    );

    $valori_campi = array_values($array_campi);

    $statement->bind_param($stringa_tipi, ...$valori_campi);
    $statement->execute();

    $result = $statement->get_result();
    $statement->close();

    if ($result) { die("<h1>Inserimento fallito! <a href='home.php'>Ritorna alla home</a></h1>"); }

    // Libera la memoria e chiude la connessione
    $result->free();
    $connection->close();

    header("location:/home.php");
    die;
?>
