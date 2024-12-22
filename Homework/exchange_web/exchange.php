<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fromCurrency = $_POST['from_currency'] ?? null;
    $toCurrency = $_POST['to_currency'] ?? null;
    $amount = floatval($_POST['amount'] ?? 0);

    $exchangeRates = [
        "LAK" => ["LAK" => 1, "USD" => 0.000047, "EUR" => 0.000045, "JPY" => 0.0072],
        "USD" => ["LAK" => 21925, "USD" => 1, "EUR" => 0.96, "JPY" => 156.46],
        "EUR" => ["LAK" => 22847, "USD" => 1.04, "EUR" => 1, "JPY" => 163.22],
        "JPY" => ["LAK" => 140.76, "USD" => 0.0064, "EUR" => 0.0061, "JPY" => 1],
    ];

    if (isset($exchangeRates[$fromCurrency][$toCurrency])) {
        $rate = $exchangeRates[$fromCurrency][$toCurrency];
        $convertedAmount = $amount * $rate;

        // Format the output as money
        $formattedAmount = number_format($convertedAmount, 2); // 2 decimal places for USD, EUR, JPY
        if ($toCurrency === "LAK") {
            $formattedAmount = number_format($convertedAmount, 0); // No decimal places for LAK
        }

        echo json_encode(['success' => true, 'result' => $formattedAmount]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid currency selection.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
