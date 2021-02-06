<?php

require_once 'object.php';

try {
    $api = new objectApi();
    echo $api->run();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
