<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic Payment Import App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            color: #333;
        }

        p {
            margin-bottom: 10px;
        }

        ul {
            margin: 0;
            padding: 0;
        }

        li {
            list-style: none;
            margin-bottom: 5px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Automatic Payment Import App</h1>
        <p>This application is designed to automate the process of importing payments from Fio bank using their API into a local database.</p>

        <h2>Requirements</h2>
        <ul>
            <li>PHP (>= 7.0)</li>
            <li>cURL PHP extension</li>
            <li>MySQL or MariaDB</li>
            <li>Bootstrap (v5.2.3)</li>
            <li>Font Awesome (v5.15.4)</li>
        </ul>

        <h2>Installation</h2>
        <ol>
            <li>Clone the repository: <code>git clone https://github.com/yourusername/your-repo.git</code></li>
            <li>Configure your web server (Apache, Nginx, etc.) to serve the application from the cloned directory.</li>
            <li>Import the SQL file <code>database.sql</code> into your MySQL or MariaDB database.</li>
            <li>Edit <code>import.php</code> and set up your Fio API token and database connection details.</li>
        </ol>

        <h2>Usage</h2>
        <p>Once the application is set up, you can access it through your web browser. Fill in the required fields in the form and click the "Odeslat" (Submit) button to initiate the import process.</p>

        <h2>License</h2>
        <p>This project is licensed under the MIT License - see the <a href="LICENSE">LICENSE</a> file for details.</p>

        <h2>Contributing</h2>
        <p>Contributions are welcome! Feel free to submit pull requests or open issues.</p>

        <div class="alert alert-info">
            <strong>Note:</strong> Make sure to keep your Fio API token and database credentials secure and do not expose them publicly.
        </div>
    </div>
</body>
</html>
