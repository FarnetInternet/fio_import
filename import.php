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

if ($conn->query($insertSql) === FALSE) {
    echo '<div class="alert alert-warning">Chyba při importování platby: ' . $conn->error . '</div>';
    exit;
}

        }

        echo '<div class="alert alert-primary" role="alert">Platby byly úspěšně importovány.</div>';
    } else {
        echo '<div class="alert alert-danger">Chyba při importování platby: ' . $conn->error . '</div>';
    }

    $conn->close();
} else {
    echo 'Chyba při získávání dat z Fio API.';
}
?>
