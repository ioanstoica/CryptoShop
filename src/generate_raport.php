<?php
require  $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=50&page=1&sparkline=false",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Accept: application/json",
        "Accept-Language: en",
        "Connection: keep-alive",
        "User-Agent: MyCurl/7.64.1"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $data = json_decode($response);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'Name');
    $sheet->setCellValue('B1', 'Price');

    $row = 2;
    foreach ($data as $coin) {
        $sheet->setCellValue('A' . $row, $coin->name);
        $sheet->setCellValue('B' . $row, $coin->current_price);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save('coincap.xlsx');
    echo 'Excel report generated successfully<br>';
    echo '<a href = "coincap.xlsx" download> Download </a>';
}
