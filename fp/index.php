<html>
<body>

<?php
$files = glob("{*.jpg,*.png}",GLOB_BRACE);
foreach ($files as $fileno => $filename) {
    echo("<img src=\"$filename\">\n");
}
?>
</html>
</body>