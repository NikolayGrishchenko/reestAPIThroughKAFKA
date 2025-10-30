<?php
namespace App\API;
class DB
{
    private string $hostname;
    private string $username;
    private string $password;
    private string $database;

    public \mysqli $db;

    public function __construct(string $hostname, string $username, string $password, string $database)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->db = new \mysqli($hostname, $username, $password, $database);
        $this->db->set_charset('utf8mb4');
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}