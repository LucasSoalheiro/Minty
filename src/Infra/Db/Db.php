<?php
namespace Src\Infra\Db;

use PDO;

class Db
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $this->pdo = new PDO(
            "pgsql:host={$config['host']};dbname={$config['dbname']}",
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}

//   private $pdo = new Pdo("pgsql:host=localhost;dbname=meu_banco", "admin", "admin123", [
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
//     ]);FF