<?php

const FORM_DEFAULT_CONTENT = '
<label class="small mb-1 ml-1" for="name">Прізвище Ім’я  *</label>
<input class="form-control mb-1" type="text" name="name" placeholder="Имя" required>
<label class="small mb-1 ml-1" for="phone">Телефон *</label>
<input class="form-control mb-1" type="tel" name="phone" placeholder="012 34 56 789" pattern="^\+?[\d\-\(\) ]{10,20}" title="Будь ласка, введіть дійсний номер телефону з цифрами та пробілами" required>
<label class="small mb-1 ml-1" for="email">Електрона пошта  *</label>
<input class="form-control mb-1" type="email" name="email" placeholder="email@mail.com" required>
<label class="small mb-1 ml-1" for="msg">Ваше запитання (необовязково)</label>
<textarea class="form-control mb-4" name="msg" placeholder="Повідомлення"></textarea>
<input class="btn btn-primary" type="submit" value="ЛИШИТИ ЗАЯВКУ">
';

const THANK_YOU_DEFAULT_CONTENT = '
<h2>Вашу заявку прийнято</h2>
<p>Ми звяжемося з вами найближчим часом</p>
[k_redirect_timer]
';