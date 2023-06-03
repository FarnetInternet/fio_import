# fio_import

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Automatic Payment Import</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1>Automatic Payment Import</h1>
        <p>This is a web application for automatically importing payments. It allows you to connect to the FIO API and retrieve payment data within a specified date range. The retrieved data can then be imported into a database for further analysis and processing.</p>
		    <h2>Prerequisites</h2>
    <p>Before using this application, make sure you have the following:</p>
    <ul>
        <li>FIO API token: You need a valid FIO API token to connect to the FIO API and retrieve payment data.</li>
        <li>Database connection details: You should have the IP address or server name, username, password, and the name of the database where you want to import the payment data.</li>
    </ul>

    <h2>Getting Started</h2>
    <p>To use the application, follow these steps:</p>
    <ol>
        <li>Open the application in a web browser.</li>
        <li>Fill in the following details in the FIO API Connection section:
            <ul>
                <li>FIO API token: Enter your FIO API token in the provided input field.</li>
                <li>From: Select the starting date from which you want to import payments.</li>
                <li>To: Select the end date until which you want to import payments.</li>
            </ul>
        </li>
        <li>Fill in the following details in the Database Connection section:
            <ul>
                <li>Server: Enter the IP address or server name where your database is hosted.</li>
                <li>Username: Provide the username for accessing the database.</li>
                <li>Password: Enter the password associated with the provided username.</li>
                <li>Database: Specify the name of the database where you want to import the payment data.</li>
            </ul>
        </li>
        <li>Click the Submit button to initiate the payment import process.</li>
        <li>Wait for the import to complete. The status and any error messages will be displayed in the Result section.</li>
    </ol>

    <h2>Dependencies</h2>
    <p>This application relies on the following dependencies:</p>
    <ul>
        <li>Bootstrap CSS: Used for styling the user interface.</li>
        <li>Bootstrap and jQuery: Required for Bootstrap components and AJAX functionality.</li>
    </ul>
    <p>Please make sure you have an active internet connection to load these dependencies.</p>

    <h2>Notes</h2>
    <ul>
        <li>The import process is handled by the import.php file on the server. Make sure it is properly configured and accessible.</li>
        <li>Any errors encountered during the import process will be logged in the browser console for troubleshooting purposes.</li>
        <li>For more information or assistance, please refer to the application documentation or contact the support team.</li>
    </ul>
</div>

<!-- Bootstrap and jQuery scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
