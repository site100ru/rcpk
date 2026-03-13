<?php
session_start();
$win = "true";

if($_POST){
    function getCaptchaCourse($SecretKey){
        $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdV1IcUAAAAABnQ0mXIp5Yh7tLEcAXzdqG6rx9Y&response={$SecretKey}");
        $Return = json_decode($Response);
        return $Return;
    }
    $Return = getCaptchaCourse($_POST['g-recaptcha-response']);

    if($Return->success == true && $Return->score > .5){
        $fio = isset($_POST['fio']) ? $_POST['fio'] : '';
        $tel = isset($_POST['tel']) ? $_POST['tel'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $kolvo_user = isset($_POST['kolvo_user']) ? $_POST['kolvo_user'] : '';
        $course_direction = isset($_POST['course_direction']) ? $_POST['course_direction'] : '';
        $learning_format = isset($_POST['learning_format']) ? $_POST['learning_format'] : '';
        $agreement = isset($_POST['agreement']) ? 'Да' : 'Нет';

        $message = "ФИО: ".$fio."\n";
        $message .= "Телефон: ".$tel."\n";
        $message .= "E-mail: ".$email."\n";
        $message .= "Количество людей: ".$kolvo_user."\n";
        $message .= "Направление курса: ".$course_direction."\n";
        $message .= "Формат обучения: ".$learning_format."\n";
        $message .= "Согласие с пользовательским соглашением: ".$agreement."\n";

        mail("info@rcpk62.ru, vasilyev-r@mail.ru", "Запись на курс с сайта", $message);
		// mail("sidorov-vv3@mail.ru, vasilyev-r@mail.ru", "Сообщение с сайта", $message);

        $_SESSION['win'] = 1;
        $_SESSION['recaptcha'] = '<p class="text-light">Спасибо за обращение. <br />Ваша заявка принята в работу. <br />В ближайшее время, с вами свяжется сотрудник учебного центра, <br />для уточнения деталей обучения.</p>';
        header("Location: ".$_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['win'] = 1;
        $_SESSION['recaptcha'] = '<p><strong>Извините!</strong><br>Ваши действия похожи на робота. Пожалуйста повторите попытку!</p>';
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
}
?>