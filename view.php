<!--
    Autore: Giuseppe Mariotti
    Data:   28/01/2022
-->

<!DOCTYPE html>

<?php
    require_once("./conf.php");

    // --- ARRAY ASSOCIATIVI PER LE EMOJI DELLO STATO DI LETTURA ---
    $status_strings = array(
        0 => "Da leggere",
        1 => "In lettura",
        2 => "Letto"
    );

    $status_emoji_codes = array(
        "Da leggere" => "&#x1f534;",
        "In lettura" => "&#x1f7e1;",
        "Letto" => "&#x1f7e2;"
    );

    // --- CONNESSIONE ---
    $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, "Alexandria");
    if ($connection->connect_error) { die("<h1>Errore di connessione al database</h1>"); }

    // --- CONTROLLO SU ID ---
    $id = substr($_SERVER['PATH_INFO'], 1);

    if (!is_numeric($id) || $id < 0)
    {
        die("<h1>L'ID non è formattato correttamente</h1>");
    }

    // --- QUERY INFO LIBRO ---
    $info_anagrafiche = "url_copertina, titolo, autore, editore, pagine, isbn, stato_lettura";
    $info_temporali = "anno_pubblicazione, YEAR(CURDATE()) - anno_pubblicazione, anno_stampa, YEAR(CURDATE()) - anno_stampa, anno_stampa - anno_pubblicazione";
    $info_traduzione_e_prezzo = "lingua_originale, titolo_originale, traduttore, prezzo, ROUND(prezzo / pagine, 2)";

    $statement = $connection->prepare(
        "SELECT $info_anagrafiche, $info_temporali, $info_traduzione_e_prezzo
        FROM libri
        WHERE id = ?;"
    );

    $statement->bind_param("i", $id);
    $statement->execute();

    $result = $statement->get_result();
    $statement->close();

    // In caso di errore sulla query
    if ($connection->errno)
    {
        $connection->close();
        die("<h1>Errore nell'esecuzione della query</h1>");
    }

    if ($result->num_rows == 0) { die("<h1>Nessun libro trovato</h1>"); }

    // --- TRASFERIMENTO DATI IN VARIABILI PHP ---
    $row = $result->fetch_row();

    $url_copertina = $row[0];
    $titolo = $row[1];
    $autore = $row[2];
    $editore = $row[3];
    $pagine = $row[4];
    $isbn = $row[5];
    $stato_lettura = $row[6];
    $anno_pubblicazione = $row[7];
    $distanza_pubblicazione = $row[8];
    $anno_stampa = $row[9];
    $distanza_stampa = $row[10];
    $modernita = $row[11];
    $lingua_originale = $row[12];
    $titolo_originale = $row[13];
    $traduttore = $row[14];
    $prezzo = $row[15];
    $prezzo_pagina = $row[16];

    // --- QUERY LETTURE LIBRO ---
    $data_inizio = "DATE_FORMAT(letture.data_inizio, '%d/%c/%Y')";
    $data_fine = "DATE_FORMAT(letture.data_fine, '%d/%c/%Y')";
    $tempo_lettura = "DATEDIFF(letture.data_fine, letture.data_inizio) + 1";
    $pagine_giorno = "ROUND(libri.pagine / (DATEDIFF(letture.data_fine, letture.data_inizio) + 1), 2)";

    $statement = $connection->prepare(
        "SELECT $data_inizio, $data_fine, $tempo_lettura, $pagine_giorno
        FROM letture INNER JOIN libri ON letture.id_libro = libri.id
        WHERE id_libro = ?;"
    );

    $statement->bind_param("i", $id);
    $statement->execute();

    $result = $statement->get_result();
    $statement->close();

    // In caso di errore sulla query
    if ($connection->errno)
    {
        $connection->close();
        die("Errore nell'esecuzione della query");
    }

    // --- TRASFERIMENTO DATI IN VARIABILI PHP ---
    $letture = array();

    while ($lett = $result->fetch_array()) { array_push($letture, $lett); }

    // Libera la memoria e chiude la connessione
    $result->free();
    $connection->close();
?>

<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo $titolo?> - View - Alexandria</title>

        <!-- CSS -->
        <link rel="stylesheet" href="/static/style/main.css">

        <!-- JS -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="p-4 flex flex-col items-center bg-slate-300">
        <div class="hover:cursor-pointer underline self-start text-lg" onclick="window.location.replace('/home.php')">Torna alla home</div>


        <div class="w-max flex flex-col items-center divide-y divide-gray-400">

            <!-- Anagrafica -->
            <div class="w-full flex justify-center p-6 space-x-10">

                <!-- Cover image -->
                <img class='rounded-md h-80' src='/static/imgs/covers/<?php echo $url_copertina?>'>

                <!-- Info -->
                <div class="flex flex-col text-2xl space-y-2">
                    
                    <span class='font-bold text-4xl'><?php echo $titolo?></span>
                    <span>di <?php echo $autore?></span>

                    <span class='pt-6'><?php echo $editore?></span>
                    <span><?php echo $pagine?> pagine</span>

                    <span>ISBN: <?php echo ($isbn == NULL) ? "N/A" : $isbn?></span>
                    <span><?php echo $lingua_originale?></span>

                    <?php
                        $status = $status_strings[$stato_lettura];
                        $emoji = $status_emoji_codes[$status_strings[$stato_lettura]];

                        echo "<span class='pt-6'>$emoji $status</span>\n";
                    ?>

                </div>

            </div>

            <!-- Temporale -->
            <div class="w-full flex flex-col items-center p-6 text-2xl space-y-2">

                <div>
                    <span>Pubblicato nel <?php echo $anno_pubblicazione?></span>
                    <span>-</span>
                    <span><?php echo $distanza_pubblicazione?> anni fa</span>
                </div>

                <div>
                    <span>Stampato nel <?php echo $anno_stampa?></span>
                    <span>-</span>
                    <span><?php echo $distanza_stampa?> anni fa</span>
                </div>

                <span>La modernità della copia è di <?php echo $modernita?> anni</span>

            </div>

            <!-- Linguistica -->
            <?php
                if ($titolo_originale != NULL)
                {
                    echo "<div class='w-full flex flex-col items-center p-6 text-2xl space-y-2'>\n";
                        echo "<span>\"$titolo_originale\"</span>\n";
                        echo "<span>Traduzione di $traduttore</span>\n";
                    echo "</div>\n";
                }
            ?>

            <!-- Prezzo -->
            <div class="w-full flex flex-col items-center p-6 text-2xl space-y-2">
                <span><?php echo $prezzo?> &euro;</span>
                <span><?php echo $prezzo_pagina?> &euro;/pagina</span>
            </div>

            <?php
                if (count($letture) > 0)
                {
                    // Letture
                    echo "<div class='w-full p-6 text-2xl space-y-2'>";

                        $num_lettura = 1;

                        foreach ($letture as $lett)
                        {
                            $data_inizio = $lett[0];
                            $data_fine = $lett[1];
                            $tempo_lettura = $lett[2];
                            $pagine_giorno = $lett[3];

                            echo "<div class='ml-2'>\n";

                                echo "<span class='font-bold'>$num_lettura&deg; lettura:</span>\n";
                                echo "<span>$data_inizio</span>\n";
                                echo "<span>&RightArrow;</span>\n";
                                
                                if ($data_fine == NULL)
                                {
                                    echo "<span>In lettura</span>\n";
                                }
                                else
                                {
                                    echo "<span>$data_fine</span>\n";
                                    echo "<span>&bullet;</span>\n";
                                    echo "<span>$tempo_lettura giorni</span>\n";
                                    echo "<span>&bullet;</span>\n";
                                    echo "<span>$pagine_giorno pagine/giorno</span>\n";
                                }

                            echo "</div>\n";

                            $num_lettura++;
                        }

                    echo "</div>";
                }
            ?>

        </div>

    </body>
</html>
