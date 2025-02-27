// Исправление для загрузки файлов
// Функция проверки для активации кнопки "Продолжить"
function checkSubmitButton() {
    let allUploaded = true;
    $('.document-upload').each(function () {
        if (!this.files || !this.files[0]) {
            allUploaded = false;
            return false; // прерываем цикл
        }
    });

    $('#submitBtn').prop('disabled', !allUploaded);
    // Добавляем или удаляем класс main__btn-active в зависимости от статуса загрузки
    if (allUploaded) {
        $('#submitBtn').addClass('main__btn-active').removeClass('main__btn');
    } else {
        $('#submitBtn').removeClass('main__btn-active').addClass('main__btn');
    }
}

$(document).ready(function () {
    // В новой HTML-структуре мы используем <label for="inputId">, 
    // и поэтому не нужно программно вызывать .click() на input.
    // Браузер автоматически активирует input при клике на соответствующий label

    console.log('Загрузка файлов настроена. Используйте области "Нажмите для загрузки" для выбора документов.');

    // Отключаем ненужные обработчики на label.upload-box, чтобы избежать конфликтов
    $('.upload-box').off('click');

    // Проверка, что все элементы загрузки настроены правильно
    $('.document-upload').each(function () {
        const inputId = $(this).attr('id');
        const label = $('label[for="' + inputId + '"]');

        if (label.length) {
            console.log('Input #' + inputId + ' и label корректно связаны');
        } else {
            console.warn('Внимание: не найден label для input #' + inputId);
        }
    });

    // Обработка выбора файла для загрузки
    $('.document-upload').on('change', function () {
        const inputId = $(this).attr('id');
        const file = this.files[0];

        if (file) {
            // Показываем предпросмотр
            const reader = new FileReader();
            reader.onload = function (e) {
                $(`#${inputId}Preview`).show().find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);

            // Эмуляция загрузки с прогресс-баром
            const progressContainer = $(`#${inputId}Progress`);
            progressContainer.show();
            const progressFill = progressContainer.find('.progress-fill');
            const percentText = progressContainer.find('.status-percent');

            let percent = 0;
            const interval = setInterval(function () {
                percent += 5;
                progressFill.css('width', percent + '%');
                percentText.text(percent + '%');

                if (percent >= 100) {
                    clearInterval(interval);
                    progressContainer.find('.status-text').text('Загружено');
                    checkSubmitButton();
                }
            }, 100);
        }
    });

    // Обработка удаления изображения
    $('.remove-image').on('click', function () {
        const targetId = $(this).data('target');
        $(`#${targetId}`).val('');
        $(`#${targetId}Preview`).hide();
        $(`#${targetId}Progress`).hide().find('.progress-fill').css('width', '0%');
        $(`#${targetId}Progress .status-text`).text('Загрузка...');
        $(`#${targetId}Progress .status-percent`).text('0%');
        checkSubmitButton();
    });
}); 