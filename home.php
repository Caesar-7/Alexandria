<!--
    Autore: Giuseppe Mariotti
    Data:   28/01/2022
-->

<!DOCTYPE html>

<?php
    require("./conf.php");

    // CONNESSIONE
    $connection = @ new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, "Alexandria");
    if ($connection->connect_error) { die("Errore di connessione al database"); }

    // QUERY
    $query =
    "SELECT id, titolo, autore, editore, pagine, stato_lettura, url_copertina
    FROM libri;";

    $result = @ $connection->query($query);

    // In caso di errore sulla query
    if ($connection->errno)
    {
        @ $connection->close();
        die("Errore nell'esecuzione della query");
    }

    // TRASFERIMENTO DATI IN VARIABILI PHP
    $da_leggere = array();
    $in_lettura = array();
    $letti = array();

    while ($libro = $result->fetch_array())
    {
        if ($libro["stato_lettura"] == 0)
        {
            array_push($da_leggere, $libro);
        }
        elseif ($libro["stato_lettura"] == 1)
        {
            array_push($in_lettura, $libro);
        }
        elseif ($libro["stato_lettura"] == 2)
        {
            array_push($letti, $libro);
        }
    }

    // Libera la memoria e chiude la connessione
    $result->free();
    $connection->close();
?>

<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Home - Alexandria</title>

        <!-- CSS -->
        <link rel="stylesheet" href="static/style/main.css">

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
                        foreach ($in_lettura as $libro)
                        {
                            // Book
                            echo "<div class='flex items-center p-6 bg-slate-400 rounded-xl shadow-xl space-x-6 hover:cursor-pointer' onclick='window.location.href=\"./view.php?id=" . $libro["id"] . "\";'>\n";

                                // Cover image
                                echo "<img class='rounded-md h-48' src='static/imgs/covers/" . $libro["url_copertina"] . "'>\n";

                                // Info
                                echo "<div class='flex flex-col text-xl'>\n";
                                    echo "<span class='font-bold text-2xl'>" . $libro["titolo"] . "</span>\n";
                                    echo "<span>" . $libro["autore"] . "</span>\n";
                                    echo "<span>&mdash;</span>\n";
                                    echo "<span>" . $libro["editore"] . "</span>\n";
                                    echo "<span>" . $libro["pagine"] . " pagine</span>\n";
                                echo "</div>\n";

                            echo "</div>\n";
                        }
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
                        foreach ($da_leggere as $libro)
                        {
                            // Book
                            echo "<div class='flex items-center space-x-4 hover:cursor-pointer' onclick='window.location.href = \"./view.php?id=" . $libro["id"] . "\";'>\n";

                                // Cover image
                                echo "<img class='rounded-md h-12' src='static/imgs/covers/" . $libro["url_copertina"] . "'>\n";

                                // Info
                                echo "<div class='text-xl space-x-2'>\n";
                                    echo "<span class='font-bold text-2xl'>" . $libro["titolo"] . "</span>\n";
                                    echo "<span>di " . $libro["autore"] . "</span>\n";
                                    echo "<span>-</span>\n";
                                    echo "<span>" . $libro["editore"] . "</span>\n";
                                    echo "<span>-</span>\n";
                                    echo "<span>" . $libro["pagine"] . " pagine</span>\n";
                                echo "</div>\n";

                            echo "</div>\n";
                        }
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
                        foreach ($letti as $libro)
                        {
                            // Book
                            echo "<div class='flex items-center space-x-4 hover:cursor-pointer' onclick='window.location.href = \"./view.php?id=" . $libro["id"] . "\";'>\n";

                                // Cover image
                                echo "<img class='rounded-md h-12' src='static/imgs/covers/" . $libro["url_copertina"] . "'>\n";

                                // Info
                                echo "<div class='text-xl space-x-2'>\n";
                                    echo "<span class='font-bold text-2xl'>" . $libro["titolo"] . "</span>\n";
                                    echo "<span>di " . $libro["autore"] . "</span>\n";
                                    echo "<span>-</span>\n";
                                    echo "<span>" . $libro["editore"] . "</span>\n";
                                    echo "<span>-</span>\n";
                                    echo "<span>" . $libro["pagine"] . " pagine</span>\n";
                                echo "</div>\n";

                            echo "</div>\n";
                        }
                    }
                ?>

            </div>

        </div>

    </body>
</html>
