<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private \PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionCreator::createConnection();
    }

    public function allStudents():array
    {
        $sqlQuery = 'SELECT * FROM students';
        $statement = $this->connection->query($sqlQuery);

        return $this->hydrateStudentList($statement);        
    }    

    public function studentsBirthAt(\DateTimeInterface $birthDate):array
    {
        $sqlQuery = "SELECT * FROM students WHERE birth_date = :birth_date";
        $statement = $this->connection->prepare($sqlQuery);
        $statement->bindValue(':birth_date', $birthDate->format('Y-m-d'));
        $statement->execute();

        return $this->hydrateStudentList($statement);
    }

    public function hydrateStudentList(\PDOStatement $statement):array
    {
        $studentDataList = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $studentList = [];
        
        foreach ($studentDataList as $studentData) {
            $studentList[] = new Student(
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date'])
            );
        }

        return $studentList;
    }

    public function update(Student $student):bool
    {
        $updateQuery = 'UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id';
        $statement = $this->connection->prepare($updateQuery);        
        $statement->bindValue(':name', $student->name());
        $statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $statement->bindValue(':id', $student->id());
        return $statement->execute();
    }

    public function save(Student $student):bool
    {
        if ($student->id() === null) {
            return $this->insert($student);
        }
        return $this->update($student);        
    }

    public function insert(Student $student):bool
    {
        $insertQuery = 'INSERT INTO students (name, birth_date) VALUES (:name, :birth_date)';
        $statement = $this->connection->prepare($insertQuery);        
        $statement->bindValue(':name', $student->name());
        $statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $success = $statement->execute();

        if ($success) {
            $student->defineId($this->connection->lastInsertId());
        }

        return $success;

    }

    public function remove(Student $student):bool
    {
        $deleteQuery = 'DELETE FROM students WHERE id = :id';
        $statement = $this->connection->prepare($deleteQuery);
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);
        return $statement->execute();
    }
}