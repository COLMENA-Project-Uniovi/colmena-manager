<?php

use Cake\Core\Configure;

$logo = $this->Html->image(
    'logo-black.png',
    ['alt' => 'logo', 'class' => 'card__image']
);
?>
<div class="scene flex">
    <div class="wrap-register100">
        <div class="logo">
            <?= $logo ?>
        </div>
        <?= $this->Form->create(
            null,
            [
                'class' => 'register100-form'
            ]
        ); ?>

        <div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c">
            <input type="text" name="username" autofocus="autofocus" class="input100" id="username" placeholder="Usuario">
            <span class="focus-input100" data-placeholder="Usuario"></span>
        </div>

        <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input type="password" name="password" class="input100" id="password" placeholder="Contraseña">
            <span class="focus-input100" data-placeholder="Contraseña"></span>
        </div>

        <div class="wrap-input100 validate-input" data-validate="Repeat password">
            <input type="password" name="password_repeat" class="input100" id="password_repeat" placeholder="Repetir contraseña">
            <span class="focus-input100" data-placeholder="Repetir contraseña"></span>
        </div>
        <div class="container-register100-form-btn">
            <div class="wrap-register100-form-btn">
                <div class="register100-form-bgbtn"></div>
                <input type="submit" class="register100-form-btn" value="Registrarse">
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<script>
    $('.input100').each(function() {
        $(this).on('blur', function() {
            if ($(this).val().trim() != "") {
                $(this).addClass('has-val');
            } else {
                $(this).removeClass('has-val');
            }
        })
    })
</script>