<?php
// File to store the visitor count
$file = 'count.txt';
// Get the current visitor's IP address
$ip = $_SERVER['REMOTE_ADDR'];
// Get the current visitor count
$count = file_get_contents($file);
// If the current visitor's IP address is not found in the count
if (strpos($count, $ip) === false) {
    // Append the IP address to the count
    file_put_contents($file, $ip . "\n", FILE_APPEND);
}
// Get the new visitor count
$count = count(file($file));
echo "Wellcome to news page<br>";
echo "News page visitor count: " . $count . "<br>";
echo '<iframe src="https://www.coindesk.com/" height="800" width="100%" title="description"></iframe>';
