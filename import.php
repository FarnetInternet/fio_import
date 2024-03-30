<?php
require_once 'FioApi.php';

// Function to create database and table
function createDatabaseAndTable($servername, $username, $password, $dbname) {
    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        echo '<div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i> Databáze byla úspěšně vytvořena nebo již existuje</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Chyba při vytváření databáze: ' . $conn->error . '</div>';
        return false;
    }

    // Select database
    $conn->select_db($dbname);

    // Create table
    $sql = "CREATE TABLE IF NOT EXISTS transaction (
      `transactionId` bigint NOT NULL,
      `datum` date DEFAULT NULL,
      `objem` decimal(10,2) DEFAULT NULL,
      `mena` varchar(3) DEFAULT NULL,
      `protiucet` varchar(50) DEFAULT NULL,
      `kodBanky` varchar(4) DEFAULT NULL,
      `nazevBanky` varchar(100) DEFAULT NULL,
      `typ` varchar(100) DEFAULT NULL,
      `provedl` varchar(100) DEFAULT NULL,
      `idPokynu` bigint DEFAULT NULL,
      `konstantnySymbol` varchar(10) DEFAULT NULL,
      `variabilnySymbol` varchar(10) DEFAULT NULL,
      `specificSymbol` varchar(10) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i> Tabulka byla úspěšně vytvořena nebo již existuje</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Chyba při vytváření tabulky: ' . $conn->error . '</div>';
        return false;
    }

    $conn->close();
    return true;
}

// Získání proměnných z formuláře v index.php
$token = $_GET['token'];
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];

// Připojovací údaje k databázi
$servername = $_GET['servername'];
$username = $_GET['username'];
$password = $_GET['password'];
$dbname = $_GET['dbname'];

// Dnešní datum
$today = date("Y-m-d");

// Vytvoření URL pro získání transakcí
$url = 'https://www.fio.cz/ib_api/rest/periods/' . $token . '/' . $dateFrom . '/' . $dateTo . '/transactions.json';

// Získání transakcí z Fio API pomocí cURL
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

// Spracování odpovědi
if (!$err) {
    $data = json_decode($response, true);

    // Check if Fio API response contains an error
    if (isset($data['error'])) {
        // Display Fio API error message
        echo '<div class="alert alert-danger" role="alert">';
        echo '<i class="fas fa-exclamation-circle"></i> Data není možné poskytnout bez silné autorizace. Pokyn k zobrazení dat si autorizujte ve Vašem Internetovém bankovnictví a data si vyžádejte znovu. Platnost ověření je 10 minut od autorizace. Nebo požádejte o data, která nejsou starší jak 90 dní (od 01.01.2024), v takovém případě není autorizace třeba.';
        echo '</div>';
    } else {
        // Check if data is not empty
        if (!empty($data['accountStatement']['transactionList']['transaction'])) {
            // Create database and table
            if (createDatabaseAndTable($servername, $username, $password, $dbname)) {
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Vložení/importování platby do tabulky
                $startTime = microtime(true); // Začátek importu
                $importedCount = 0;

                foreach ($data['accountStatement']['transactionList']['transaction'] as $transaction) {
                    $transactionId = $transaction['column22']['value'];
                    $datum = date("Y-m-d", strtotime($transaction['column0']['value']));
                    $objem = $transaction['column1']['value'];
                    $mena = $transaction['column14']['value'];
                    $protiucet = isset($transaction['column2']) ? $transaction['column2']['value'] : '';
                    $kodBanky = isset($transaction['column3']) ? $transaction['column3']['value'] : '';
                    $nazevBanky = isset($transaction['column12']) ? $transaction['column12']['value'] : '';
                    $typ = $transaction['column10']['value'];
                    $provedl = isset($transaction['column8']) ? $transaction['column8']['value'] : '';
                    $idPokynu = intval($transaction['column17']['value']);

                    $variabilnySymbol = isset($transaction['column4']) ? $transaction['column4']['value'] : '';

                    $insertSql = "INSERT INTO transaction (transactionId, datum, objem, mena, protiucet, kodBanky, nazevBanky, typ, provedl, idPokynu, variabilnySymbol)
                                  VALUES ('$transactionId', '$datum', '$objem', '$mena', '$protiucet', '$kodBanky', '$nazevBanky', '$typ', '$provedl', '$idPokynu', '$variabilnySymbol')";

                    if ($conn->query($insertSql) === true) {
                        $importedCount++;
                    } else {
                        echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Chyba při importování platby: ' . $conn->error . '</div>';
                        exit();
                    }
                }

                $endTime = microtime(true); // Konec importu
                $importDuration = $endTime - $startTime;

                echo '<div class="alert alert-primary" role="alert"><i class="fas fa-check-circle"></i> Platby byly úspěšně importovány. Počet importovaných plateb: ' . $importedCount . '</div>';
                echo '<div class="alert alert-info" role="alert"><i class="fa-solid fa-circle-info"></i> Čas trvání importu: ' . round($importDuration, 2) . ' sekund</div>';

                // Save debug info to a file
                $debugInfo = "Čas zahájení importu: " . date("Y-m-d H:i:s", $startTime) . "\n";
                $debugInfo .= "Čas ukončení importu: " . date("Y-m-d H:i:s", $endTime) . "\n";
                $debugInfo .= "Doba trvání importu: " . round($importDuration, 2) . " seconds\n";
                $debugInfo .= "Počet importovaných plateb: " . $importedCount . "\n";

                file_put_contents('debug.log', $debugInfo, FILE_APPEND | LOCK_EX); // Create or append to the debug.log file

                $conn->close();
            } else {
                echo '<div class="alert alert-danger" role="alert"><i class="fas fa-times-circle"></i> Nepodařilo se vytvořit databázi a tabulku.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i> Data není možné poskytnout bez silné autorizace. Pokyn k zobrazení dat si autorizujte ve Vašem Internetovém bankovnictví <a href="https://ib.fio.cz/">Internetovém bankovnictví</a> a data si vyžádejte znovu. Platnost ověření je 10 minut od autorizace. Nebo požádejte o data, která nejsou starší jak 90 dní (od 01.01.2024), v takovém případě není autorizace třeba.</div>';
        }
    }
} else {
    echo '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Chyba při získávání dat z Fio API: ' . $err . '</div>';
}
?>
