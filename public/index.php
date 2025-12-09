<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
require_once "../app/controllers/UsuarioController.php";

$controller = new UsuarioController();
$controller->index();
?>

<body>
    <h1>Hello</h1>
    <p>Probando</p>
</body>
</html>