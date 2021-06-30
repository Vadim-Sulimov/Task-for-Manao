$(document).ready(function() {
    $('.forms').submit(function(){
        // Убираем класс ошибок с инпутов
            $('input').each(function(){
            $(this).removeClass('error_input');
        });
        // Прячем текст ошибок
        $('.error').hide();

        // Получение данных из полей
        var login_user = $('#login_user').val();
        var name_user = $('#name_user').val();
        var email_user = $('#email_user').val();
        var password_user = $('#password_user').val();
        var password2_user = $('#password2_user').val();
        var login_user_auth = $('#login_user_auth').val();
        var password_user_auth = $('#password_user_auth').val();

        $.ajax({
            // Мтод отправки
            type: "POST",
            // Путь до скрипта-обработчика
            url: "FormProcessor.php",
            // Какие данные будут переданы
            data: {
                'login_user': login_user,
                'name_user': name_user,
                'email_user': email_user,
                'password_user': password_user,
                'password2_user': password2_user,
                'login_user_auth': login_user_auth,
                'password_user_auth': password_user_auth,
            },
            // Тип передачи данных
            dataType: "json",
            // Действие, при ответе с сервера
            success: function(data){
                // В случае, когда пришло register, выводится сообщение об успешной регистрации.
                if(data.result == 'register'){
                    alert('форма корректно заполнена');
                    // В случае ошибок в форме
                }if(data.result == 'authorise') {
                    window.location.replace('account.php'); // Переход на страницу аккаунта в случае, если пришло authorise.
                    // В случае ошибок в форме
                }else {
                    // Перебираем массив с ошибками
                    for(var errorField in data.text_error){
                        // Выводим текст ошибок
                        $('#'+errorField+'_error').html(data.text_error[errorField]);
                        // Показываем текст ошибок
                        $('#'+errorField+'_error').show();
                        // Обводим инпуты красным цветом
                        $('#'+errorField).addClass('error_input');
                    }
                }
            }
        });
        // Останавливаем сабмит
         return false;
    });
});
