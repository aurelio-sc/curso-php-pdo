<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$sqlDelete = 'DELETE FROM students WHERE id = :id';

$preparedStatement = $pdo->prepare($sqlDelete);
$preparedStatement->bindValue(':id', 5, PDO::PARAM_INT);
var_dump($preparedStatement->execute());

$preparedStatement->bindValue(':id', 4, PDO::PARAM_INT);
var_dump($preparedStatement->execute());