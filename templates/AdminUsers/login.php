<?php

use Cake\Core\Configure;

$logo = $this->Html->image(
    'logo-black.png',
    ['alt' => 'logo', 'class' => 'card__image']
);
?>
<div class="scene flex">
    <div class="wrap-login100">
        <div class="logo">
            <?= $logo ?>
        </div>
        <?= $this->Form->create(
            null,
            [
                'class' => 'login100-form'
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

        <div class="buttons" style="display: flex;align-items: center;justify-content: space-between;">
            <div class="container-login100-form-btn">
                <div class="wrap-login100-form-btn">
                    <div class="login100-form-bgbtn"></div>
                    <input type="submit" class="login100-form-btn" value="Acceder">
                </div>
            </div>
            
            <div class="container-register100-form-btn">
                <div class="wrap-register100-form-btn">
                    <a class="register100-form-btn" href="./register">Registrarse</a>
                </div>
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