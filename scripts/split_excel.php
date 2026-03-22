<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$inputFile = __DIR__ . '/../pastry_shop_products.xlsx';
if (!file_exists($inputFile)) {
    echo "Input file not found: {$inputFile}\n";
    exit(1);
}

$spreadsheet = IOFactory::load($inputFile);
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();

// Read header (first row)
$headerRange = 'A1:' . $highestColumn . '1';
$header = $sheet->rangeToArray($headerRange, null, true, true, true);
$headerRow = array_values($header[1]);

// Read data rows
$dataRows = [];
for ($r = 2; $r <= $highestRow; $r++) {
    $row = $sheet->rangeToArray('A' . $r . ':' . $highestColumn . $r, null, true, true, true);
    $dataRows[] = array_values($row[$r]);
}

$total = count($dataRows);
if ($total === 0) {
    echo "No data rows found in spreadsheet.\n";
    exit(0);
}

$half = (int) ceil($total / 2);
$first = array_slice($dataRows, 0, $half);
$second = array_slice($dataRows, $half);

function writeExcel(array $headerRow, array $rows, string $outPath)
{
    $spread = new Spreadsheet();
    $ws = $spread->getActiveSheet();
    // Write header
    $ws->fromArray($headerRow, null, 'A1');
    // Write data
    if (!empty($rows)) {
        $ws->fromArray($rows, null, 'A2');
    }
    $writer = IOFactory::createWriter($spread, 'Xlsx');
    $writer->save($outPath);
}

$out1 = __DIR__ . '/../pastry_shop_products_seed.xlsx';
$out2 = __DIR__ . '/../pastry_shop_products_import.xlsx';

writeExcel($headerRow, $first, $out1);
writeExcel($headerRow, $second, $out2);

echo "Split complete.\n";
echo "Total rows: {$total}\n";
echo "First file ({$out1}) rows: " . count($first) . "\n";
echo "Second file ({$out2}) rows: " . count($second) . "\n";
