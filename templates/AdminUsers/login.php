<?php

use Cake\Core\Configure;

$logo = $this->Html->image(
    'logo.png',
    ['alt' => 'logo', 'class' => 'card__image']
);
?>
<style>
    :root {
        --bc-1: #BBA169;
        --bc-2: #BBA169;
    }

    * {
        font: 100 62.5% "Heebo", sans-serif;
    }

    body {
        color: #fefefe;
        overflow-x: hidden;
        width: 100%;
        margin: 0;
    }

    .visually-hidden {
        clip: rect(0 0 0 0);
        clip-path: inset(50%);
        height: 1px;
        overflow: hidden;
        position: absolute;
        white-space: nowrap;
        width: 1px;
    }

    .flex {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .scene {
        background: #362100 url('https://beta.colmenaproject.es/admin/img/wallpaper.jpg') center/cover;
        height: 100vh;
        position: relative;
    }

    input {
        outline: none;
        border: none;
    }

    textarea {
        outline: none;
        border: none;
    }

    textarea:focus,
    input:focus {
        border-color: transparent !important;
    }

    input:focus::-webkit-input-placeholder {
        color: transparent;
    }

    input:focus:-moz-placeholder {
        color: transparent;
    }

    input:focus::-moz-placeholder {
        color: transparent;
    }

    input:focus:-ms-input-placeholder {
        color: transparent;
    }

    textarea:focus::-webkit-input-placeholder {
        color: transparent;
    }

    textarea:focus:-moz-placeholder {
        color: transparent;
    }

    textarea:focus::-moz-placeholder {
        color: transparent;
    }

    textarea:focus:-ms-input-placeholder {
        color: transparent;
    }

    input::-webkit-input-placeholder {
        color: #adadad;
    }

    input:-moz-placeholder {
        color: #adadad;
    }

    input::-moz-placeholder {
        color: #adadad;
    }

    input:-ms-input-placeholder {
        color: #adadad;
    }

    textarea::-webkit-input-placeholder {
        color: #adadad;
    }

    textarea:-moz-placeholder {
        color: #adadad;
    }

    textarea::-moz-placeholder {
        color: #adadad;
    }

    textarea:-ms-input-placeholder {
        color: #adadad;
    }

    /*---------------------------------------------*/
    button {
        outline: none !important;
        border: none;
        background: transparent;
    }

    button:hover {
        cursor: pointer;
    }

    iframe {
        border: none !important;
    }


    /*//////////////////////////////////////////////////////////////////
[ Utility ]*/
    .txt1 {
        font-size: 13px;
        color: #666666;
        line-height: 1.5;
    }

    .txt2 {

        font-size: 13px;
        color: #333333;
        line-height: 1.5;
    }

    /*//////////////////////////////////////////////////////////////////
[ login ]*/

    .limiter {
        width: 100%;
        margin: 0 auto;
    }

    .container-login100 {
        width: 100%;
        min-height: 100vh;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        padding: 15px;
        background: #f2f2f2;
    }

    .wrap-login100 {
        width: 390px;
        backdrop-filter: blur(10px);
        background: rgba(254, 254, 254, 0.075);
        border: 1px solid rgba(254, 254, 254, 0.18);
        border-radius: 15px;
        box-shadow: 0 10px 35px 0 rgb(54 33 0 / 65%);
        border-radius: 10px;
        overflow: hidden;
        padding: 80px 60px;
        box-shadow: 0 5px 10px 0px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0 5px 10px 0px rgba(0, 0, 0, 0.1);
        -webkit-box-shadow: 0 5px 10px 0px rgba(0, 0, 0, 0.1);
        -o-box-shadow: 0 5px 10px 0px rgba(0, 0, 0, 0.1);
        -ms-box-shadow: 0 5px 10px 0px rgba(0, 0, 0, 0.1);
    }

    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 50px;
    }

    /*------------------------------------------------------------------
[ Form ]*/

    .login100-form {
        width: 100%;
    }

    .login100-form-title {
        display: block;
        font-size: 30px;
        color: #333333;
        line-height: 1.2;
        text-align: center;
    }

    .login100-form-title i {
        font-size: 60px;
    }

    .wrap-input100 {
        width: 100%;
        position: relative;
        border-bottom: 2px solid #F8B333;
        margin-bottom: 37px;
    }

    .input100 {
        font-size: 15px;
        color: white;
        line-height: 1.2;
        display: block;
        width: 100%;
        height: 45px;
        background: transparent;
        padding: 0 5px;
    }

    /*---------------------------------------------*/
    .focus-input100 {
        position: absolute;
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
    }

    .focus-input100::before {
        content: "";
        display: block;
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;

        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;

        background: #F8B333;
        /* background: -webkit-linear-gradient(left, var(--bc-1), var(--bc-2));
        background: -o-linear-gradient(left, #21d4fd, #b721ff);
        background: -moz-linear-gradient(left, #21d4fd, #b721ff);
        background: linear-gradient(left, #21d4fd, #b721ff); */
    }

    .focus-input100::after {

        font-size: 15px;
        color: white;
        line-height: 1.2;
        font-weight: 700;
        content: attr(data-placeholder);
        display: block;
        width: 100%;
        position: absolute;
        top: 16px;
        left: 0px;
        padding-left: 5px;

        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
    }

    .input100:focus+.focus-input100::after {
        top: -15px;
    }

    .input100:focus+.focus-input100::before {
        width: 100%;
    }

    .has-val.input100+.focus-input100::after {
        top: -15px;
    }

    .has-val.input100+.focus-input100::before {
        width: 100%;
    }

    /*---------------------------------------------*/
    .btn-show-pass {
        font-size: 15px;
        color: #999999;

        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        align-items: center;
        position: absolute;
        height: 100%;
        top: 0;
        right: 0;
        padding-right: 5px;
        cursor: pointer;
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
    }

    .btn-show-pass:hover {
        color: #6a7dfe;
        color: -webkit-linear-gradient(left, #21d4fd, #b721ff);
        color: -o-linear-gradient(left, #21d4fd, #b721ff);
        color: -moz-linear-gradient(left, #21d4fd, #b721ff);
        color: linear-gradient(left, #21d4fd, #b721ff);
    }

    .btn-show-pass.active {
        color: #6a7dfe;
        color: -webkit-linear-gradient(left, #21d4fd, #b721ff);
        color: -o-linear-gradient(left, #21d4fd, #b721ff);
        color: -moz-linear-gradient(left, #21d4fd, #b721ff);
        color: linear-gradient(left, #21d4fd, #b721ff);
    }



    /*------------------------------------------------------------------
[ Button ]*/
    .container-login100-form-btn {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding-top: 13px;
    }

    .wrap-login100-form-btn {
        width: 100%;
        display: block;
        position: relative;
        z-index: 1;
        border-radius: 25px;
        overflow: hidden;
        margin: 0 auto;
        background: none;
    }

    .login100-form-bgbtn {
        position: absolute;
        z-index: -1;
        width: 300%;
        height: 100%;
        background: #F8B333;
        background: -webkit-linear-gradient(right, var(--bc-2), var(--bc-1), var(--bc-2), var(--bc-1));
        background: -o-linear-gradient(right, #21d4fd, #b721ff, #21d4fd, #b721ff);
        background: -moz-linear-gradient(right, #21d4fd, #b721ff, #21d4fd, #b721ff);
        background: linear-gradient(right, #21d4fd, #F8B333, #21d4fd, #F8B333);
        top: 0;
        left: -100%;

        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
    }



    .login100-form-btn {
        font-size: 18px;
        color: #fff;
        line-height: 1.2;

        letter-spacing: 1.1px;
        background: none;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 12px 20px;
        width: 100%;
        cursor: pointer;
        background-color: #F8B333;

        transition: all 0.2s cubic-bezier(0, 0, 0, 0.54);
    }

    .login100-form-btn:hover {
        background-color: white;
        color: var(--bc-1);
    }

    .wrap-login100-form-btn:hover .login100-form-bgbtn {
        left: 0;
    }

    .has-val.input100+.focus-input100::after {
        top: -15px;
    }

    /*------------------------------------------------------------------
[ Responsive ]*/

    @media (max-width: 576px) {
        .wrap-login100 {
            padding: 77px 15px 33px 15px;
        }
    }
</style>
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
            <input type="text" name="username" autofocus="autofocus" class="input100" id="username">
            <span class="focus-input100" data-placeholder="Usuario"></span>
        </div>
        <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input type="password" name="password" class="input100" id="password">
            <span class="focus-input100" data-placeholder="Contraseña"></span>
        </div>
        <div class="container-login100-form-btn">
            <div class="wrap-login100-form-btn">
                <div class="login100-form-bgbtn"></div>
                <input type="submit" class="login100-form-btn" value="Acceder">
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