<?php
class User
{
    # Attributes
    private ?int $id;
    private string $username;
    private string $email;
    private string $password;
    private ?string $avatar;
    private ?DateTime $registrationDate;

    # Constructor
    private function __construct()
    {
    }

    # User Factory
    public static function factory(
        ?int $id,
        string $username,
        string $email,
        string $password,
        ?string $avatar,
        ?string $registrationDate
    ): User {
        $user  = new User();

        $user->setId($id);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setAvatar($avatar);
        $user->setRegistrationDate($registrationDate);

        return $user;
    }

    # Setters & Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar)
    {
        $this->avatar = $avatar;
    }

    public function getRegistrationDate(): ?DateTime
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?string $registrationDate)
    {
        $this->registrationDate = $registrationDate ? new DateTime($registrationDate) : NULL;
    }
}
