<?php
// Это обработчик данных формы регистрации
require_once 'Validator.php'; // Подключение класса,осуществляющего валидацию
require_once 'DBShell.php'; // Подключение класса,работающего с базой данных
$errors = []; // Контейнер для ошибок

$login = $_POST['login_user'];
$name = $_POST['name_user'];
$email = $_POST['email_user'];
$password = $_POST['password_user'];
$password2 = $_POST['password2_user'];
$loginAuth = $_POST['login_user_auth'];
$passwordAuth = $_POST['password_user_auth'];

if ($loginAuth != '' && $passwordAuth != '') { // Если поля авторизации не пустые,
    $db = new DBShell($loginAuth, $passwordAuth); // Загрузка данных на авторизацию.
    $errors = $db->authorise(); // Выгрузка ошибок в контейнер.
    $flag = 'authorise'; // Если авторизация совершена, в переменную flag записывается соответствующая строка.
} else { //Если совершается регистрация,

    $validator = new Validator($name, $login, $email, $password, $password2); // Загрузка данных в валидатор
    $errors = $validator->getResult(); // Выгружает ошибки в контейнер для ошибок

    if (empty($errors)) { // Если ошибок нет,
        $db = new DBShell($login, $password); // подается сигнал для создания учетной записи
        $db->setName($name)->setEmail($email);
        $errors = $db->create(); // Выгружает ошибки,
    }
    $flag = 'register'; // В переменную записывается строка register.
}


if (empty($errors) && $flag == 'authorise') {
    echo json_encode(array('result' => 'authorise'));
} elseif (empty($errors) && $flag == 'register') {
    echo json_encode(array('result' => 'register'));
} else {
    // Если есть ошибки то отправляем
    echo json_encode(array('result' => 'error', 'text_error' => $errors));
}

