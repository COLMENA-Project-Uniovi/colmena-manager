<div class="input select">
    <label for="<?= $id; ?>"><?= $title; ?></label>
    <select name="<?= $name; ?>" class="<?= $class; ?>" id="<?= $id; ?>" multiple>
<?php
    foreach ($options as $key => $value) {
?>
        <option selected><?= $value; ?></option>
<?php
    }
?>
    </select>
    <span class="help">Introduce las palabras clave para este elemento separadas por comas. No debería tener más de 5-6 palabras clave.</span>
</div><!-- .input.select -->
