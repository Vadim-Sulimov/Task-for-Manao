<?php
// Класс, работающий с базой данных
class DBShell
{
    private $link;
    private $login;
    private $email;
    private $name;
    private $password;
    public $errors = [];

    public function __construct($login,$password) // Записывает параметры в свойства
    {
        $link = simplexml_load_file('Users.xml');
        $this->link = $link;
        $this->login = $login;
        $this->password = $password;

    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function setEmail($email){
        $this->email = $email;
        return $this;
    }

    public function create()
    {
        // сохраняет запись в базу
        $this->read(); // Проверяет введенные логин и пароль
       if (empty($this->errors)) // Если такого логина и почтового ящика нет в базе, происходит запись
       {
           $node = $this->link->addChild('user');
           $node->addChild('login', $this->login);
           $node->addChild('name', $this->name);
           $node->addChild('email', $this->email);
           $node->addChild('password', password_hash($this->password, PASSWORD_BCRYPT)); // Хэширование пароля

           $text = $this->link->asXML();
           $text=preg_replace('/(<\/.+?\>)/', "\$1\r\n", $text); // Регулярки для записи в базу столбцом
           $text=preg_replace('/\>\s*\</', ">\r\n<", $text);
           file_put_contents("Users.xml", $text);
       };
       return $this->errors;
    }

   public function authorise() // Метод для авторизации.
   {
        $users = []; // Контейнер для логинов
       foreach ($this->link->user as $user){
               if ($user->login == $this->login && password_verify($this->password,$user->password) == false) { // Если логин существует в базе, а введенный пароль не соответствует
                   $this->errors['password_user_auth'] = 'Вы ввели неверный пароль.';
               }
           $users[] = $user->login;
       }
    if (in_array( $this->login,$users) == false){                      // Проверка введенного логина на существование
        $this->errors['login_user_auth'] = 'Нет такого пользователя.';
    }

        if(empty($this->errors)){ // Запись куки в узел к авторизующемуся пользователю.
            session_start();
            $key = mt_rand().'rand';
            setcookie('key', $key, time() + 3000);
            setcookie('login',$this->login, time() + 3000);
            $_SESSION['user'] = ['login' =>$this->login];
            foreach ($this->link->user as $user){
                if ($user->login == $this->login){
                    $user->addChild("cookie", $key );
                    $text = $this->link->asXML();
                    $text=preg_replace('/(<\/.+?\>)/', "\$1\r\n", $text);
                    $text=preg_replace('/\>\s*\</', ">\r\n<", $text);
                    file_put_contents("Users.xml", $text);
                }
                }
        }

       return $this->errors;
   }



   public static function deleteCookie($login) // Удаление куки пользователя после выхода из сессии
   {
       $link = simplexml_load_file('Users.xml');
       foreach ($link->user as $user){
           if ($user->login == $login){
               unset($user->cookie);
               $text = $link->asXML();
               $text=preg_replace('/(<\/.+?\>)/', "\$1\r\n", $text);
               $text=preg_replace('/\>\s*\</', ">\r\n<", $text);
               file_put_contents("Users.xml", $text);
           }
       }
   }

    private function read() // Проверяет введенные логин и почту
    {
        foreach ($this->link->user as $user){
            if ($user->login == $this->login){
                $this->errors['login_user'] = 'Такой login уже есть.';
            }
            if ($user->email == $this->email){
                $this->errors['email_user'] = 'Такой email уже есть.';
            }
        }
    }

}