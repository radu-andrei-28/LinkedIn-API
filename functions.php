<?php
if (isset($_POST['categories'])) {
    $action = $_POST['categories'];
    export($action);
}

function export($action) {
    $json_obj = json_decode($action, true);
    $fp = fopen('export.csv', 'w+');
    fputcsv($fp, $json_obj);
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="export.csv";');
    fclose($fp);
}
?>



