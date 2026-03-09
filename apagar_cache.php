<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Expira os cookies para limpar nome salvo e contador de jogadas.
setcookie('jogador', '', time() - 3600);
setcookie('jogadas', '', time() - 3600);

header('Location: index.php?cache=apagado');
exit;
