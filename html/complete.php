<?php

include 'config.php';
include 'http.php';

$result = search(ELASTICSEARCH,'.kibana','search',"*");
$available_searches=[];
foreach($result as $search) {
    $available_searches[] = $search;
}

sort($available_searches);
echo json_encode($available_searches);
