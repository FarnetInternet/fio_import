 Automatic Payment Import
This is a web application for automatically importing payments. It allows you to connect to the FIO API and retrieve payment data within a specified date range. The retrieved data can then be imported into a database for further analysis and processing.

Prerequisites
Before using this application, make sure you have the following:

FIO API token: You need a valid FIO API token to connect to the FIO API and retrieve payment data.
Database connection details: You should have the IP address or server name, username, password, and the name of the database where you want to import the payment data.
Getting Started
To use the application, follow these steps:

Open the application in a web browser.
Fill in the following details in the FIO API Connection section:
FIO API token: Enter your FIO API token in the provided input field.
From: Select the starting date from which you want to import payments.
To: Select the end date until which you want to import payments.
Fill in the following details in the Database Connection section:
Server: Enter the IP address or server name where your database is hosted.
Username: Provide the username for accessing the database.
Password: Enter the password associated with the provided username.
Database: Specify the name of the database where you want to import the payment data.
Click the Submit button to initiate the payment import process.
Wait for the import to complete. The status and any error messages will be displayed in the Result section.
Dependencies
This application relies on the following dependencies:

Bootstrap CSS: Used for styling the user interface.
Bootstrap and jQuery: Required for Bootstrap components and AJAX functionality.
Please make sure you have an active internet connection to load these dependencies.

Notes
The import process is handled by the import.php file on the server. Make sure it is properly configured and accessible.
Any errors encountered during the import process will be logged in the browser console for troubleshooting purposes.
For more information or assistance, please refer to the application documentation or contact the support team.
