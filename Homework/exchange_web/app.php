<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    
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
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Noto Sans Lao Looped", sans-serif;
        }
        body {
            background-color: #f4f6f9;
        }
        .converter-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="converter-form" class="converter-container shadow border border-success">
            <h2 class="text-center mb-4">ເຄື່ອງຄຳນວນແລກປ່ຽນເງິນຕາ</h2>
            <div class="mb-3">
                <label for="amount" class="form-label">ຈຳນວນເງິນ:</label>
                <input type="number" class="form-control border border-success" id="amount" name="amount" placeholder="Enter Amount" value="1" required>
            </div>
            <div class="mb-3">
                <label for="fromCurrency" class="form-label">ຈາກສະກຸນເງິນ:</label>
                <select class="form-select border border-success" name="from_currency" id="fromCurrency" required>
                    <option value="LAK">LAK - Lao Kip</option>
                    <option value="USD">USD - US Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="JPY">JPY - Japanese Yen</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="toCurrency" class="form-label">ເປັນສະກຸນເງິນ:</label>
                <select class="form-select border border-success" name="to_currency" id="toCurrency" required>
                    <option value="LAK">LAK - Lao Kip</option>
                    <option value="USD">USD - US Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="JPY">JPY - Japanese Yen</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="convertedAmount" class="form-label">ມູນຄ່າແລກປ່ຽນ:</label>
                <input type="text" class="form-control border border-success" id="convertedAmount" name="convertedAmount" readonly>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">ດຳເນີນການແລກປ່ຽນ</button>
            </div>
            <div id="error-message" class="alert alert-danger mt-3 d-none"></div>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#converter-form").submit(function (e) {
                e.preventDefault(); // Prevent form submission
                const formData = $(this).serialize(); // Serialize form data

                $.post("app.php", formData, function (response) {
                    if (response.success) {
                        $("#convertedAmount").val(response.result);
                        $("#error-message").addClass("d-none");
                    } else {
                        $("#error-message").removeClass("d-none").text(response.message);
                    }
                }, "json").fail(function () {
                    $("#error-message").removeClass("d-none").text("An error occurred. Please try again.");
                });
            });
        });
    </script>
</body>
</html>