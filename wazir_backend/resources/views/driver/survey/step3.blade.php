<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение анкеты Wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
</head>

<body>
    <div class="survey-3">
        <div class="container">
            <div class="back-button">
                <a href="{{ route('driver.survey.step2') }}">←</a>
            </div>

            <h3 class="title-left">Заполните данные ВУ</h3>
            <form action="{{ route('driver.survey.processStep3') }}" method="POST" id="driverDataForm">
                @csrf
                
                @if(request()->has('redirect_to_complete'))
                <input type="hidden" name="redirect_to_complete" value="1">
                @endif
                
                <div class="form-group">
                    <label class="form-label">Введите страну</label>
                    <div class="form-input-with-arrow" id="countrySelector">
                        <input type="text" name="country"
                            value="{{ old('country', session('country', 'Выберите страну')) }}" readonly>
                        <span class="arrow">›</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Введите Ф.И.О.</label>
                    <div class="form-input-with-arrow">
                        <input type="text" name="fullname" placeholder="Ф.И.О."
                            value="{{ old('fullname', session('fullname', '')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Введите номер в/у</label>
                    <div class="form-input-with-arrow">
                        <span class="prefix">в/у</span>
                        <input type="text" name="license_number" placeholder="00000000" maxlength="8"
                            value="{{ old('license_number', session('license_number', '')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Введите дату выдачи
                        в/у</label>
                    <input type="text" name="issue_date" class="form-input date-mask" placeholder="дд.мм.гггг"
                        value="{{ old('issue_date', session('issue_date', '')) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Введите срок действия в/у
                        (если есть)</label>
                    <input type="text" name="expiry_date" class="form-input date-mask" placeholder="дд.мм.гггг"
                        value="{{ old('expiry_date', session('expiry_date', '')) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Введите позывной</label>
                    <input type="text" name="callsign" class="form-input" placeholder="Позывной"
                        value="{{ old('callsign', session('callsign', '')) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Введите код приглашения (если
                        есть)</label>
                    <input type="text" name="invitation_code" class="form-input" placeholder="0000000000" maxlength="10"
                        value="{{ old('invitation_code', session('invitation_code', '')) }}">
                </div>

                <button type="submit"
                    class="main__btn {{ (old('country', session('country', '')) != 'Выберите страну' && 
                                         !empty(old('fullname', session('fullname', ''))) && 
                                         !empty(old('license_number', session('license_number', ''))) && 
                                         !empty(old('issue_date', session('issue_date', '')))) ? 'main__btn-active' : '' }}"
                    style="margin: 20px 0 30px 0;">Подтвердить</button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/imask"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.date-mask').forEach(element => {
            IMask(element, {
                mask: 'dd.mm.yyyy',
                blocks: {
                    dd: {
                        mask: IMask.MaskedRange,
                        from: 1,
                        to: 31
                    },
                    mm: {
                        mask: IMask.MaskedRange,
                        from: 1,
                        to: 12
                    },
                    yyyy: {
                        mask: IMask.MaskedRange,
                        from: 1900,
                        to: 2099
                    }
                }
            });
        });

        function checkFormCompletion() {
            const country = document.querySelector('#countrySelector input').value;
            const name = document.querySelector('input[name="fullname"]').value;
            const licenseNumber = document.querySelector('input[name="license_number"]').value;
            const issueDate = document.querySelector('input[name="issue_date"]').value;
            const submitButton = document.querySelector('.main__btn');

            if (
                country !== 'Выберите страну' &&
                name.trim() !== '' &&
                licenseNumber.trim() !== '' &&
                issueDate.length === 10
            ) {
                submitButton.classList.add('main__btn-active');
            } else {
                submitButton.classList.remove('main__btn-active');
            }
        }

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', checkFormCompletion);
        });

        const countrySelector = document.getElementById('countrySelector');
        countrySelector.addEventListener('click', function() {
            const input = this.querySelector('input');
            if (input.value === 'Выберите страну') {
                input.value = 'Киргизия';
                checkFormCompletion();
            }
        });

        // Если страна уже выбрана, проверим форму при загрузке
        checkFormCompletion();
    });
    </script>
</body>

</html>