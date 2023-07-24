<?php

declare(strict_types=1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

/* YOUR CODE (Instructions in README.md) */
//? import required files
require_once APP_PATH . 'App.php';
require_once APP_PATH . 'format.php';

//~ getTransactionFiles() function returns an array
$files = getTransactionFiles(FILES_PATH);

//~ an empty array wich will be merged into array returned by getTransactions() function
$transactions = [];

//~ read the list of files array returned by getTransactionFiles() function
foreach ($files as $file) {
    $transactions = array_merge($transactions, getTransactions($file, 'extractTransactions'));
}

$totals = calculateTotals($transactions);

require_once(VIEWS_PATH . 'transactions.php');