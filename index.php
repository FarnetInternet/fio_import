<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatický import plateb</title>
    <!-- Připojení Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81b9711967.js" crossorigin="anonymous"></script>
    <style>
    /* Loader styles */
    #loader {
        position: fixed;
        top: 50%;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0); /* Fully transparent background */
        display: none;
        z-index: 9999; /* Ensure it appears above other content */
        text-align: center; /* Center horizontally */
    }

    .loader-content {
        display: inline-block; /* Ensure content is centered */
        transform: translateY(-50%); /* Center vertically */
    }

    </style>
</head>
<body>
<div id="loader">
    <div class="loader-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
<div class="container mt-4">
    <h1 class="text-center">Automatický import plateb</h1>
    <hr>
    <div id="result"></div>
    <!-- Formulář pro vstupní údaje -->
    <div class="row">
        <div class="col-md-6">
            <form id="importForm">
                <div class="mb-3">
                    <label for="tokenInput" class="form-label">FIO API token:</label>
                    <input type="text" class="form-control" id="tokenInput" name="token">
                </div>
                <div class="mb-3">
                    <label for="dateFromInput" class="form-label">Datum od:</label>
                    <input type="date" class="form-control" id="dateFromInput" name="dateFrom">
                </div>
                <div class="mb-3">
                    <label for="dateToInput" class="form-label">Datum do:</label>
                    <input type="date" class="form-control" id="dateToInput" name="dateTo" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <!-- Formulář pro připojení k databázi -->
            <form id="databaseForm">
                <div class="mb-3">
                    <label for="servernameInput" class="form-label">IP/Adresa serveru:</label>
                    <input type="text" class="form-control" id="servernameInput" name="servername">
                </div>
                <div class="mb-3">
                    <label for="usernameInput" class="form-label">Uživatelské jméno:</label>
                    <input type="text" class="form-control" id="usernameInput" name="username">
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Heslo:</label>
                    <input type="password" class="form-control" id="passwordInput" name="password">
                </div>
                <div class="mb-3">
                    <label for="dbnameInput" class="form-label">Název databáze:</label>
                    <input type="text" class="form-control" id="dbnameInput" name="dbname">
                </div>
            </form>
        </div>
    </div>
    <div class="text-center">
        <button id="submitButton" class="btn btn-primary"><i class="fas fa-file-import"></i> Zahájit import</button>
    </div>
</div>

<!-- Připojení Bootstrap a jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // Po kliknutí na tlačítko "Odeslat"
        $("#submitButton").click(function (e) {
            e.preventDefault();

            // Show loader
            $('#loader').show();

            // Získání dat z obou formulářů
            var importFormData = $("#importForm").serialize();
            var databaseFormData = $("#databaseForm").serialize();

            // Spojení dat z obou formulářů
            var formData = importFormData + "&" + databaseFormData;

            // AJAX požadavek na import.php
            $.ajax({
                url: "import.php",
                type: "GET",
                data: formData,
                success: function (response) {
                    // Hide loader on success
                    $('#loader').hide();
                    
                    // Zobrazení odpovědi
                    $("#result").html(response);
                },
                error: function (xhr, status, error) {
                    // Hide loader on error
                    $('#loader').hide();
                    
                    // Zobrazení chyby
                    console.error(error);
                },
            });
        });
    });
</script>
</body>
</html>
