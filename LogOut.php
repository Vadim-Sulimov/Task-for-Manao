<?php
// Файл завершения сессии и выхода из аккаунта
session_start();
if(isset($_POST['exit'])){
    require_once 'DBShell.php';
    $login = $_SESSION['user']['login']; // Изъятие логина пользователя из переменной сессии
    DBShell::deleteCookie($login); // Передача логина пользователя в метод удаления куки из БД
    session_destroy(); // Завершение сессии
    setcookie('login','',time()); // Удаление куки
    header("Location: index.html");
}
