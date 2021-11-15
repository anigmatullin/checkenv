<?php

$resource = "google1.com";

$res = dns_get_record($resource, DNS_A);

if (!$res) {
    echo "dns request failed\n";
}

foreach($res as $row) {
    $ip = $row["ip"];
    echo $ip, "\n";
}
