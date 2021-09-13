<?php
class DBController
{
    private static $connection = NULL;

    # Constructor
    private function __construct()
    {
    }

    # Get connection
    public static function connect()
    {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        self::$connection = new PDO("mysql:host=localhost;dbname=chat", 'root', 'Arias0410!', $pdo_options);

        return self::$connection;
    }

    # User managment functions
    public static function insertUser(User $user)
    {
        $db = self::connect();
        $query = 'INSERT INTO users (username, email, password) VALUES(:username, :email, :password)';

        $insert = $db->prepare($query);
        $insert->bindValue('username', $user->getUsername());
        $insert->bindValue('email', $user->getEmail());
        $insert->bindValue('password', $user->getPassword());
        $insert->execute();
    }

    public static function deleteUser(string $id)
    {
        $db = self::connect();
        $query = 'DELETE FROM users WHERE id=:id';

        $delete = $db->prepare($query);
        $delete->bindValue('id', $id);
        $delete->execute();
    }

    public static function updateUser(User $user)
    {
        $db = self::connect();
        $query = 'UPDATE users SET username=:username, email=:email, password=:password, avatar=:avatar WHERE id=:id';

        $update = $db->prepare($query);
        $update->bindValue('username', $user->getUsername());
        $update->bindValue('email', $user->getEmail());
        $update->bindValue('password', $user->getPassword());
        $update->bindValue('avatar', $user->getAvatar());
        $update->bindValue('id', $user->getId());
        $update->execute();
    }

    public static function selectUser(string $column, string $value): NULL|User
    {
        $db = self::connect();
        $query = "SELECT * FROM users WHERE $column=:value";

        $select = $db->prepare($query);
        $select->bindValue('value', $value);
        $select->execute();

        $user = $select->fetch();

        if (!$user)
            return NULL;

        return User::factory(
            $user['id'],
            $user['username'],
            $user['email'],
            $user['password'],
            $user['avatar'],
            $user['register_date']
        );
    }
}
