<?php
require_once 'FioApi.php';

// Získání proměnných z formuláře v index.php
$token = $_GET['token'];
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];
//$variableSymbol = $_POST['variableSymbol'];

// Připojovací údaje k databázi
$servername = $_GET['servername'];
$username = $_GET['username'];
$password = $_GET['password'];
$dbname = $_GET['dbname'];

// Dnešní datum
$today = date("Y-m-d");

// Vytvoření URL pro získání transakcí
$url = 'https://www.fio.cz/ib_api/rest/periods/' . $token . '/' . $dateFrom . '/' . $dateTo . '/transactions.json';

// Získání transakcí z Fio API
$response = file_get_contents($url);

// Spracování odpovědi
if ($response !== false) {
    $data = json_decode($response, true);

    // Vytvoření SQL tabulky
    $sql = "CREATE TABLE IF NOT EXISTS platbyTest (
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

    // Připojení k databázi
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Připojení k databázi zlyhalo: " . $conn->connect_error);
    }

    // Vytvoření tabulky
    if ($conn->query($sql) === TRUE) {
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
            $idPokynu = intval($row['column17']['value']);

            $varSymbol = isset($transaction['column4']) ? $transaction['column4']['value'] : ''; // Získanie hodnoty konštantného symbolu (KS)

            $insertSql = "INSERT INTO platbyTest (transactionId, datum, objem, mena, protiucet, kodBanky, nazevBanky, typ, provedl, idPokynu, varSymbol)
                          VALUES ('$transactionId', '$datum', '$objem', '$mena', '$protiucet', '$kodBanky', '$nazevBanky', '$typ', '$provedl', '$idPokynu', '$varSymbol')";

            if ($conn->query($insertSql) === TRUE) {
                $importedCount++;
            } else {
                echo '<div class="alert alert-warning">Chyba při importování platby: ' . $conn->error . '</div>';
                exit;
            }
        }

        $endTime = microtime(true); // Konec importu
        $importDuration = $endTime - $startTime;

        echo '<div class="alert alert-primary" role="alert">Platby byly úspěšně importovány. Počet importovaných plateb: ' . $importedCount . '</div>';
        echo '<div class="alert alert-info" role="alert">Čas trvání importu: ' . round($importDuration, 2) . ' sekund</div>';

        // Save debug info to a file
        $debugInfo = "Import start time: " . date("Y-m-d H:i:s", $startTime) . "\n";
        $debugInfo .= "Import end time: " . date("Y-m-d H:i:s", $endTime) . "\n";
        $debugInfo .= "Import duration: " . round($importDuration, 2) . " seconds\n";
        $debugInfo .= "Number of imported payments: " . $importedCount . "\n";

        file_put_contents('debug.log', $debugInfo, FILE_APPEND | LOCK_EX); // Create or append to the debug.log file
    } else {
        echo '<div class="alert alert-danger">Chyba při importování platby: ' . $conn->error . '</div>';
    }

    $conn->close();
} else {
    echo 'Chyba při získávání dat z Fio API.';
}
?>
