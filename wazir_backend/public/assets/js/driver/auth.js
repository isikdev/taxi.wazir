$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const phoneNumber = urlParams.get('phone');

    if (phoneNumber) {
        $('.active-phone-number').text(decodeURIComponent(phoneNumber));
    }

    const $smsInputs = $('.sms-code');

    $smsInputs.on('input', function () {
        // Оставляем ввод только цифр
        this.value = this.value.replace(/[^\d]/g, '');
        if (this.value.length === 1) {
            $(this).next('.sms-code').focus();
        }
    });

    // Обработчик отправки формы
    $('#sms-form').on('submit', function (e) {
        // Объединяем значения всех полей в одну строку
        let combinedCode = '';
        $smsInputs.each(function () {
            combinedCode += $(this).val();
        });
        // Записываем объединённое значение в скрытое поле
        $('#sms-code-hidden').val(combinedCode);
        // Форма отправится стандартным способом, вызывая POST-запрос на processStep2
    });

    // Остальные части вашего кода (таймер, resend и т.д.) можно оставить, если нужны:
    let timeLeft = 59;
    const timerElement = $('.timer-sms');
    const resendLink = $('.resend-sms');
    const invalidText = $('.invalid-text');

    resendLink.hide();
    invalidText.hide();

    function startTimer() {
        const timer = setInterval(function () {
            if (timeLeft <= 0) {
                clearInterval(timer);
                timerElement.hide();
                resendLink.show();
            } else {
                timerElement.text(`Код не пришел (0:${timeLeft.toString().padStart(2, '0')})`);
                timeLeft--;
            }
        }, 1000);
    }
    startTimer();
});
