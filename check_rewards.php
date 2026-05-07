<?php
/* Script de prueba/debug para verificar que la API de recompensas
 * funciona correctamente. Simula una petición GET action=list.
 *
 * ⚠️ ATENCIÓN: La ruta absoluta está hardcodeada a un directorio
 * específico de desarrollo local. Debería usar __DIR__ en su lugar.
 */
$_GET['action'] = 'list';
$_SERVER['REQUEST_METHOD'] = 'GET';
require __DIR__ . '/public/api/recompensas.php';