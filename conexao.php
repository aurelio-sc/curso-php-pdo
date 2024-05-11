<?php

$databasePath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:' . $databasePath);

echo 'Conectado com sucesso!';

$pdo->exec('CREATE TABLE students (
    id INTEGER PRIMARY KEY,
    name TEXT,
    birth_date TEXT
    )');