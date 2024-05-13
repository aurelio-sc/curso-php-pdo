<?php

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:' . $databasePath);

$student = new Alura\Pdo\Domain\Model\Student(
    null,
    "SQLInjectionAttack', ''); DROP TABLE students; --",
    new \DateTimeImmutable('1980-01-01')
    );

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date)";

$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(':name', $student->name());
$statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

if ($statement->execute()) {
    echo "Aluno '{$student->name()}' inseridocom sucesso!";
}

//var_dump($statement);
//var_dump($pdo->exec($sqlInsert));