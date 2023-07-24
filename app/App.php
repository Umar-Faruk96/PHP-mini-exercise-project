<?php

declare(strict_types=1);

// Your Code
function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) {
            continue;
        }

        $files[] = $dirPath . $file;
    }

    return $files;
}

function getTransactions(string $filePath, ?callable $transactionHandler = null): array
{
    //& check if the file actually exists
    if (!file_exists($filePath)) {
        trigger_error('File "' . $filePath . '" does not exist' . E_USER_ERROR);
    }

    $file = fopen($filePath, 'r');

    //& read the first line that's way it can't be printed, a trick
    fgetcsv($file);

    $transactions = [];

    while (($transaction = fgetcsv($file)) !== false) {

        //* check if there is callback function available
        if ($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }

        $transactions[] = $transaction;
    }

    return $transactions;
}

function extractTransactions(array $transactionRow): array
{
    //& transactionRow array destructure
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    //& transaction amount format
    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount
    ];
}

function calculateTotals(array $transactions): array
{
    $totals = ['totalIncome' => 0, 'totalExpense' => 0, 'netTotal' => 0];


    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];

        if ($transaction['amount'] >= 0) {
            $totals['totalIncome'] += $transaction['amount'];
        } else {
            $totals['totalExpense'] += $transaction['amount'];
        }
    }
    return $totals;
}