<!--
    Autore: Giuseppe Mariotti
    Data:   28/01/2022
-->

<!DOCTYPE html>

<?php
    require_once("./conf.php");

    // --- CONNESSIONE ---
    $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, "Alexandria");
    if ($connection->connect_error) { die("<h1>Errore di connessione al database</h1>"); }

    // --- QUERY ---
    $query =
    "SELECT id, titolo, autore, editore, pagine, stato_lettura, url_copertina
    FROM libri;";

    $result = $connection->query($query);

    // In caso di errore sulla query
    if ($connection->errno)
    {
        $connection->close();
        die("<h1>Errore nell'esecuzione della query</h1>");
    }

    // --- TRASFERIMENTO DATI IN VARIABILI PHP ---
    $da_leggere = array();
    $in_lettura = array();
    $letti = array();

    while ($libro = $result->fetch_array())
    {
        switch ($libro["stato_lettura"])
        {
            case 0: array_push($da_leggere, $libro); break;
            case 1: array_push($in_lettura, $libro); break;
            case 2: array_push($letti, $libro); break;
        }
    }

    // Libera la memoria e chiude la connessione
    $result->free();
    $connection->close();

    // --- FUNZIONI DI VISUALIZZAZIONE ---
    function stampa_libro_grande($libro)
    {
        $id = $libro["id"];
        $copertina = $libro["url_copertina"];
        $titolo = $libro["titolo"];
        $autore = $libro["autore"];
        $editore = $libro["editore"];
        $pagine = $libro["pagine"];

        // Book
        echo "<div class='flex items-center p-6 bg-slate-400 rounded-xl shadow-xl space-x-6 hover:cursor-pointer' onclick='window.location.replace(\"/view.php/$id\");'>\n";

            // Cover image
            echo "<img class='rounded-md h-48' src='/static/imgs/covers/$copertina'>\n";

            // Info
            echo "<div class='flex flex-col text-xl'>\n";
                echo "<span class='font-bold text-2xl'>$titolo</span>\n";
                echo "<span>$autore</span>\n";
                echo "<span>&mdash;</span>\n";
                echo "<span>$editore</span>\n";
                echo "<span>$pagine pagine</span>\n";
            echo "</div>\n";

        echo "</div>\n";
    }

    function stampa_libro_piccolo($libro)
    {
        $id = $libro["id"];
        $copertina = $libro["url_copertina"];
        $titolo = $libro["titolo"];
        $autore = $libro["autore"];
        $editore = $libro["editore"];
        $pagine = $libro["pagine"];

        // Book
        echo "<div class='flex items-center space-x-4 hover:cursor-pointer' onclick='window.location.replace(\"/view.php/$id\");'>\n";

            // Cover image
            echo "<img class='rounded-md h-12' src='/static/imgs/covers/$copertina'>\n";

            // Info
            echo "<div class='text-xl space-x-2'>\n";
                echo "<span class='font-bold text-2xl'>$titolo</span>\n";
                echo "<span>di $autore</span>\n";
                echo "<span>-</span>\n";
                echo "<span>$editore</span>\n";
                echo "<span>-</span>\n";
                echo "<span>$pagine pagine</span>\n";
            echo "</div>\n";

        echo "</div>\n";
    }
?>

<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Home - Alexandria</title>

        <!-- CSS -->
        <link rel="stylesheet" href="/static/style/main.css">

        <!-- JS -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="py-6 flex flex-col items-center space-y-10 bg-slate-300">

        <!-- In lettura row -->
        <div class="flex flex-col items-center space-y-6">

            <!-- Header -->
            <div class="text-6xl font-bold">In lettura</div>

            <!-- List -->
            <div class="flex justify-center space-x-6">

                <?php
                    if (count($in_lettura) == 0)
                    {
                        echo "<span class='text-2xl'>Nessun libro in lettura</span>\n";
                    }
                    else
                    {
                        foreach ($in_lettura as $libro) { stampa_libro_grande($libro); }
                    }
                ?>

            </div>

        </div>

        <!-- Da leggere row -->
        <div class="flex flex-col items-center space-y-6">

            <!-- Header -->
            <div class="text-6xl font-bold">Da leggere</div>

            <!-- List -->
            <div class="p-6 bg-slate-400 rounded-xl shadow-xl space-y-6">

                <?php
                    if (count($da_leggere) == 0)
                    {
                        echo "<span class='text-2xl'>Nessun libro da leggere</span>\n";
                    }
                    else
                    {
                        foreach ($da_leggere as $libro) { stampa_libro_piccolo($libro); }
                    }
                ?>

            </div>

        </div>

        <!-- Gia letti row -->
        <div class="flex flex-col items-center space-y-6">

            <!-- Header -->
            <div class="text-6xl font-bold">Gi&agrave; letti</div>

            <!-- List -->
            <div class="p-6 bg-slate-400 rounded-xl shadow-xl space-y-6">

                <?php
                    if (count($letti) == 0)
                    {
                        echo "<span class='text-2xl'>Nessun libro da leggere</span>\n";
                    }
                    else
                    {
                        foreach ($letti as $libro) { stampa_libro_piccolo($libro); }
                    }
                ?>

            </div>

        </div>

    </body>
</html>
