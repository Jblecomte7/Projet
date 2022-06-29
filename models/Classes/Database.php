<?php
class Database
{

    private string $db_name;
    private string $db_user;
    private string $db_pass;
    private string $db_host;
    private int $db_port;
    private $pdo;


    public function __construct(string $db_name = 'jeanbaptistelecomte_projetfinal', int $db_port = 3306, string $db_user = 'jeanbaptistelecomte', string $db_pass = '9bc09e5aaf06484c4b2ba99290f6c300', string $db_host = 'localhost')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
        $this->db_port = $db_port;
    }

    private function getPDO()
    {
        if ($this->pdo === null) {
            $pdo = new \PDO('mysql:dbname=' . $this->db_name . ';port=' . $this->db_port . ';host=' . $this->db_host, $this->db_user, $this->db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    public function query(string $statement)
    {
        $req = $this->getPDO()->query($statement);
        $data = $req->fetchAll();
        return $data;
    }

    public function prepare(string $statement, array $params, bool $one = false)
    {
        $req = $this->getPDO()->prepare($statement);
        $req->execute($params);
        if ($one) {
            $data = $req->fetch();
        } else {
            $data = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }
}
