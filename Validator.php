<?php
// Класс, осуществляющий проверку введенных данных на соответствие требованиям.
class Validator
{
    private $login;
    private $name;
    private $email;
    private $password;
    private $password2;
    public $errors = [];

    public function __construct($name, $login, $email, $password, $password2) // Записывает принятые параметры в свойства
    {
        $this->name = $name;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
        $this->password2 = $password2;
    }


    private function PasswordComparison() // Сравнивает пароли
    {
        if ($this->password != $this->password2) {
            $this->errors['password2_user'] = 'Пароли не совпадают.';
        }
        return $this;
    }

    private function ValidateLogin() // Проверка логина
    {
        if (strlen($this->login) < 6) {
            $this->errors['login_user'] = 'Слишком короткий login.';
        }
        if (preg_match("/^[a-zа-яё\d]{1}[a-zа-яё\d\s]*[a-zа-яё\d]{1}$/i", $this->login) == 0) {
            $this->errors['login_user'] .= ' Неверные символы.';
        }
        return $this;
    }

    private function ValidateMail() // Проверка почтового адреса
    {
        if (preg_match('/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/', $this->email) == 0) {
            $this->errors['email_user'] .= 'Неверный email.';
        }
        return $this;
    }

    private function ValidateName() // Проверка имени
    {
        if (strlen($this->name) < 2) {
            $this->errors['name_user'] = 'Слишком короткое имя.';
        }
        if (preg_match("/^[a-zа-яё\d]{1}[a-zа-яё\d\s]*[a-zа-яё\d]{1}$/i", $this->name) == 0) {
            $this->errors['name_user'] .= ' Неверные символы.';
        }
        return $this;
    }

    private function ValidatePassword() // Проверка пароля
    {
        if (strlen($this->password) < 6) {
            $this->errors['password_user'] = 'Слишком короткий пароль.';
        }
        if (preg_match('#\d+#', $this->password) == 0) {
            $this->errors['password_user'] .= ' Пароль должен содержать цифры.';
        }
        if (preg_match('#(?=\S*[a-z])(?=\S*[A-Z])#', $this->password) == 0) {
            $this->errors['password_user'] .= ' Пароль должен содержать буквы в разных регистрах.';
        }
        if (preg_match('#\W+#', $this->password) == 0) {
            $this->errors['password_user'] .= ' Пароль должен содержать спецсимвол.';
        }
        if (preg_match('#\s+#', $this->password) == 1) {
            $this->errors['password_user'] .= ' Пароль не должен содержать пробел.';
        }
        return $this;
    }

    public function getResult() // Вызывает цепочкой все проверки и собирает ошибки
    {
        $this->ValidateLogin()->ValidateName()->ValidatePassword()->ValidateMail()->PasswordComparison();
        $result = $this->errors;
        return $result;
    }
}