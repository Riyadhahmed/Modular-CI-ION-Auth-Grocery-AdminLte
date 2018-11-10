<?php
    ob_start();

    include(__DIR__."/list_tbody.php");

    $tbody_html = ob_get_clean();
?>

<?php
    echo json_encode(array(
        'current_total_results' => $this->get_total_results(),
        'tbody_html' => $tbody_html
    ));