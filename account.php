<?php
// Это аккаунт пользователя, на который он перенаправляется после удачной авторизации.
session_start();
echo "<div style='color: #0a53be; font-size: 30px; text-align: center'>";
echo 'Hello ' . $_SESSION['user']['login']; // Приветствие пользователя
echo "</div>";
// Кнопка выхода
echo '<form action="LogOut.php" id="exit" method="post" style="text-align: center" > 

    <input type="submit" value="Exit" name="exit" style="font-size: 20px" >

</form>';
echo "</div>";