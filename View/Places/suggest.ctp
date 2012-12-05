<?php

$names = array();
foreach ($result['data'] as $d) {
    array_push($names, $d['name']);
}
echo json_encode(array($result['metadata']['searchTerm'], $names));
?>