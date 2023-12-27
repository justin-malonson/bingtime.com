<?php

try {
    require_once __DIR__ ."/../includes/ajax_protect.php";
    require_once __DIR__. "/../includes/login.php";
    require_once __DIR__ . "/../includes/get-status.php";

    echo json_encode([
        "result" => [
            "messages" => [
                "scheduled" => $scheduled,
                "pending" => $pending,
                "queued" => $queued,
                "sent" => $sent,
                "delivered" => $delivered,
                "failed" => $failed,
                "canceled" => $canceled,
                "received" => $received
            ],
            "ussd" => [
                "pending" => $pendingUssd,
                "sent" => $sentUssd
            ]
        ]
    ]);
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}