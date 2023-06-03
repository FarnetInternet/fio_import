<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Automatický import plateb</title>
    <!-- Pripojenie Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link href="app.css" rel="stylesheet" >
    <!-- Pripojenie Bootstrap a jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Automatický import plateb</h1>
        <hr>
        <div id="current-payment"></div>
        <div id="result"></div>
        <!-- Formulář pro vstupní údaje -->
        <div class="row">
            <div class="col-md-6">
                <form id="importForm" action="import.php">
                    <div class="py-2"></div>
                    <h4>Připojení k FIO API</h4>
                    <hr>
                    <div class="form-group">
                        <label for="tokenInput">FIO API token:</label>
                        <input type="text" class="form-control" id="tokenInput" name="token">
                    </div>
					<div class="mb-3"></div>
                    <div class="form-group">
                        <label for="dateFromInput">Datum od:</label>
                        <input type="date" class="form-control" id="dateFromInput" name="dateFrom">
                    </div>
					<div class="mb-3"></div>
                    <div class="form-group">
                        <label for="dateToInput">Datum do:</label>
                        <input type="date" class="form-control" id="dateToInput" name="dateTo">
                    </div>
					<div class="mb-3"></div>
                </form>
            </div>
            <div class="col-md-6">
                <!-- Formulář pro připojení k databázi -->
                <div class="py-2"></div>
                <h4>Připojení k databázi</h4>
                <hr>
                <form id="databaseForm" action="import.php">
                    <div class="form-group">
                        <label for="servernameInput">IP/Adresa serveru:</label>
                        <input type="text" class="form-control" id="servernameInput" name="servername">
                    </div>
					<div class="mb-3"></div>
                    <div class="form-group">
                        <label for="usernameInput">Uživatelské jméno:</label>
                        <input type="text" class="form-control" id="usernameInput" name="username">
                    </div>
					<div class="mb-3"></div>
                    <div class="form-group">
                        <label for="passwordInput">Heslo:</label>
                        <input type="password" class="form-control" id="passwordInput" name="password">
                    </div>
					<div class="mb-3"></div>
                    <div class="form-group">
                        <label for="dbnameInput">Název databáze:</label>
                        <input type="text" class="form-control" id="dbnameInput" name="dbname">
                    </div>
                </form>
            </div>
        </div>
		<div class="py-3"></div>
        <center>
            <button id="submitButton" class="btn btn-primary">Odeslat</button>
        </center>
    </div>

    <script>
        $(document).ready(function() {
            // Po kliknutí na tlačítko "Odeslat"
            $('#submitButton').click(function(e) {
                e.preventDefault();

                // Získání dat z obou formulářů
                var importFormData = $('#importForm').serialize();
                var databaseFormData = $('#databaseForm').serialize();

                // Spojení dat z obou formulářů
                var formData = importFormData + '&' + databaseFormData;

                // AJAX požadavek na import.php
                $.ajax({
                    url: 'import.php',
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        // Zobrazení odpovědi
                        $('#result').html(response);
                    },
                    error: function(xhr, status, error) {
                        // Zobrazení chyby
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>
</html>
