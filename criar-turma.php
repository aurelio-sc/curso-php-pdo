<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();

$sutendtRepository = new PdoStudentRepository($connection);

$connection->beginTransaction();

try {

$aStudent = new Student(null, 'João Pedro', new \DateTimeImmutable('1992-08-24'));

$sutendtRepository->save($aStudent);

$anotherStudent = new Student(null, 'Maria Catarina', new \DateTimeImmutable('1983-11-19'));

$sutendtRepository->save($anotherStudent);

$connection->commit();
} catch (\PDOException $e) {
    echo $e->getMessage();
    $connection->rollBack();
}

