<!--
    Autore: Giuseppe Mariotti
    Data:   11/02/2023
-->
<!DOCTYPE html>

<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Add book - Alexandria</title>

        <link rel="stylesheet" href="/static/style/main.css">

        <!-- JS -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="p-4 flex flex-col items-center bg-slate-300">
        <form method="POST" action="/add_action.php">
            <div>ANAGRAFICA</div>
            <div class="flex">  
                <div class="pr-3">
                    <label for="titolo">Titolo</label><br>
                    <input type="text" name="titolo" id="titolo" maxlength="100" required>
                </div>

                <div class="pr-3">
                    <label for="autore" class="block">Autore</label>
                    <input type="text" name="autore" id="autore" maxlength="50" required>
                </div>

                <div class="pr-3">
                    <label for="editore" class="block">Editore</label>
                    <input type="text" name="editore" id="editore" maxlength="50" required>
                </div>

                <div class="pr-3">
                    <label for="pagine" class="block">Pagine</label>
                    <input type="number" name="pagine" id="pagine" min="1" required>
                </div>
            </div>

            <div class="flex">
                <div class="pr-3">
                    <label for="isbn" class="block">ISBN</label>
                    <input type="text" name="isbn" id="isbn" minlength="10" maxlength="13">
                </div>

                <div class="pr-3">
                    <label for="lingua_originale" class="block">Lingua Originale</label>
                    <input type="text" name="lingua_originale" id="lingua_originale" maxlength="50" required>
                </div>

                <div class="pr-3">
                    <label for="stato_lettura" class="block">Stato lettura</label>
                    <select name="stato_lettura" id="stato_lettura">
                        <option value="0" selected>&#x1f534; Da leggere</option>
                        <option value="1">&#x1f7e1; In lettura</option>
                        <option value="2">&#x1f7e2; Letto</option>
                    </select>
                </div>

                <div class="pr-3">
                    <label for="url_copertina" class="block">Copertina</label>
                    <input type="file" id="url_copertina" name="url_copertina" accept="image/png, image/jpeg">
                </div>
            </div>

            <br><br><div>TEMPORALE</div>
            <label for="anno_pubblicazione">Anno pubblicazione</label>
            <input type="number" name="anno_pubblicazione" id="anno_pubblicazione" min="0" required>

            <label for="anno_stampa">Anno stampa</label>
            <input type="number" name="anno_stampa" id="anno_stampa" min="0" required>

            <br><br><div>TRADUZIONE</div>
            <label for="traduttore">Traduttore</label>
            <input type="text" name="traduttore" id="traduttore" maxlength="50">

            <label for="titolo_originale">Titolo Originale</label>
            <input type="text" name="titolo_originale" id="titolo_originale" maxlength="100">

            <br><br><div>PREZZO</div>
            <label for="prezzo">Prezzo</label>
            <input type="number" name="prezzo" id="prezzo" min="0" step="0.01" required>

            <br><br><input type="submit" class="border-2 border-black p-2 hover:cursor-pointer" value="Crea">
        </form>
    </body>
</html>
