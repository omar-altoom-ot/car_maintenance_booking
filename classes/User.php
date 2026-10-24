<?php
require_once __DIR__ . "/../DB/config.php";

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $is_active;
    private $database;

    public function __construct(string $email = "", string $password = "")
    {
        $this->email = trim($email);
        $this->password = $password;
        $this->database = new Db();
    }

    public function verification()
    {
        if ($this->email === "" || $this->password === "") {
            return false;
        }

        $stmt = $this->database->run_query(
            "SELECT * FROM user WHERE email = ?",
            [$this->email]
        );
        $user = $stmt->fetch();

        if (
            !$user ||
            !password_verify($this->password, $user["password"]) ||
            !$user["is_active"]
        ) {
            return false;
        }

        $this->id = $user["id"];
        $this->name = $user["name"];
        $this->email = $user["email"];
        $this->role = $user["role"];
        $this->is_active = $user["is_active"];

        session_regenerate_id(true);
        $_SESSION["user_id"] = $this->id;
        $_SESSION["name"] = $this->name;
        $_SESSION["email"] = $this->email;
        $_SESSION["role"] = $this->role;

        return true;
    }

    public function register(string $name, string $email, string $password)
    {
        
    }

    public function getAll()
    {
        return $this->database
            ->run_query(
                "SELECT id,name,email,is_active,created_at FROM user WHERE role='user' ORDER BY created_at DESC"
            )
            ->fetchAll();
    }

    public function setActive(int $id, int $active)
    {
        return $this->database
            ->run_query(
                "UPDATE user SET is_active=? WHERE id=? AND role='user'",
                [$active ? 1 : 0, $id]
            )
            ->rowCount() === 1;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getIsActive()
    {
        return $this->is_active;
    }
}
?>
