<?php
require 'Connect.php';

class Sql
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connect::getConnection();
    }

    public function dbErrorInfo($query): void
    {
        $errInfo = $query->errorInfo();
        if ($errInfo[0] != PDO::ERR_NONE) {
            echo $errInfo[2];
            exit();
        }
    }

    public function selectOne($tableName, $col, $id)
    {
        $allowedTables = ['Users'];
        if (!in_array($tableName, $allowedTables)) {
            die("Неверное имя таблицы");
        } else {
            $sql = "SELECT * FROM $tableName WHERE $col = '$id'";
            $query = $this->pdo->prepare($sql);
            $query->execute();
            $this->dbErrorInfo($query);
            return $query->fetch();
        }
    }

    public function selectWhere($tableName, $data)
    {
        $isFirst = true;
        $columns = '';
        $keys = '';
        foreach ($data as $key => $value) {
            if (!$isFirst) {
                $columns .= ", $key";
                $keys .= ", '$value'";
            }
            if ($isFirst) {
                $columns .= "$key";
                $keys .= "'$value'";
                $isFirst = false;
            }
        }
        $sql = "SELECT * FROM $tableName WHERE $columns = $keys";
        $query = $this->pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response = json_encode($result);
        // Отправка данных в виде JSON-ответа
        header('Content-Type: application/json');
        echo $response;
    }

    public function insertIntoTable($tableName, $data): int
    {
        $i = 0;
        $coll = '';
        $masks = '';
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                if ($i === 0) {
                    $coll = $coll . "$key";
                    $masks = $masks . "'" . "$value" . "'";
                } else {
                    $coll = $coll . ", $key";
                    $masks = $masks . ", '$value" . "'";
                }
                $i++;
            } else {
                if ($i === 0) {
                    $coll = $coll . "$key";
                    $masks = $masks . $value;
                } else {
                    $coll = $coll . ", $key";
                    $masks = $masks . ", " . $value;
                }
                $i++;
            }
        }
        $sql = " INSERT INTO $tableName ($coll) VALUES ($masks)";
        $query = $this->pdo->prepare($sql);
        $query->execute();
        return $this->pdo->lastInsertId();
    }

    public function updateIntoTable($tableName, $data, $userId)
    {
        $userId = intval($userId);
        $allowedTables = ['Users'];
        if (!in_array($tableName, $allowedTables)) {
            die("Неверное имя таблицы");
        } else {
            $update = [];
            foreach ($data as $key => $value) {
                if ($key === 'password') {
                    $value = password_hash($value, PASSWORD_DEFAULT);
                    $update[] = "$key = '$value'";
                } else {
                    $update[] = "$key = '$value'";
                }

            }
            $sql = "UPDATE $tableName SET " . implode(", ", $update) . " WHERE id = ${userId}";
            try {
                $query = $this->pdo->prepare($sql);
                $query->execute();
            } catch (PDOException $e) {
                $errorMessage = "Ошибка при выполнении запроса: " . $e->getMessage();
                error_log($errorMessage, 3, $_SERVER['DOCUMENT_ROOT'] . "/Logs/Error.log");
            }
        }
    }
    public function SetOnlineStatus($tableName,$userId,$status)
    {
        $sql ="UPDATE $tableName SET is_active = '$status' WHERE id = $userId";
        $query = $this->pdo->prepare($sql);
        $query->execute();
    }
    public function  selectOnline()
    {
        $sql = "SELECT * FROM Users WHERE is_active = 'true'";
        $query = $this->pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response = json_encode($result);
        // Отправка данных в виде JSON-ответа
        header('Content-Type: application/json');
        echo $response;
    }
    public function PutInCsv()
    {
        $sql = "SELECT * FROM Users WHERE is_active = 'true'";
        $query = $this->pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        // Открываем файл CSV для записи
        $file = fopen('online.csv', 'w');

        // Записываем заголовки столбцов (если нужно)
        fputcsv($file, array_keys($result[0]));

        // Записываем данные из результата запроса в файл CSV
        foreach ($result as $row) {
            fputcsv($file, $row);
        }
        // Закрываем файл CSV
        fclose($file);
    }

}