<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Automatický import plateb</title>
        <!-- Připojení Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="app.css" rel="stylesheet" />
        <style>
            /* Vlastní styly */
            body {
                background-color: #f8f9fa;
            }
            .container {
                max-width: 600px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-4">
            <h1 class="text-center">Automatický import plateb</h1>
            <hr />
            <div id="result"></div>
            <!-- Formulář pro vstupní údaje -->
            <div class="row">
                <div class="col-md-6">
                    <form id="importForm">
                        <div class="mb-3">
                            <label for="tokenInput" class="form-label">FIO API token:</label>
                            <input type="text" class="form-control" id="tokenInput" name="token" />
                        </div>
                        <div class="mb-3">
                            <label for="dateFromInput" class="form-label">Datum od:</label>
                            <input type="date" class="form-control" id="dateFromInput" name="dateFrom" />
                        </div>
                        <div class="mb-3">
                            <label for="dateToInput" class="form-label">Datum do:</label>
                            <input type="date" class="form-control" id="dateToInput" name="dateTo" value="<?php echo date('Y-m-d'); ?>" />
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <!-- Formulář pro připojení k databázi -->
                    <form id="databaseForm">
                        <div class="mb-3">
                            <label for="servernameInput" class="form-label">IP/Adresa serveru:</label>
                            <input type="text" class="form-control" id="servernameInput" name="servername" />
                        </div>
                        <div class="mb-3">
                            <label for="usernameInput" class="form-label">Uživatelské jméno:</label>
                            <input type="text" class="form-control" id="usernameInput" name="username" />
                        </div>
                        <div class="mb-3">
                            <label for="passwordInput" class="form-label">Heslo:</label>
                            <input type="password" class="form-control" id="passwordInput" name="password" />
                        </div>
                        <div class="mb-3">
                            <label for="dbnameInput" class="form-label">Název databáze:</label>
                            <input type="text" class="form-control" id="dbnameInput" name="dbname" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center">
                <button id="submitButton" class="btn btn-primary">Odeslat</button>
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
                            // Zobrazení odpovědi
                            $("#result").html(response);
                        },
                        error: function (xhr, status, error) {
                            // Zobrazení chyby
                            console.log(error);
                        },
                    });
                });
            });
        </script>
    </body>
</html>
