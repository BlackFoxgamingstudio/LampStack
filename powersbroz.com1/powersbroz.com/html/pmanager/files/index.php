<?php
require_once '../app/boot.php';

header("HTTP/1.1 301 Moved Permanently");
header("Location: ".BASE_URL);

exit;