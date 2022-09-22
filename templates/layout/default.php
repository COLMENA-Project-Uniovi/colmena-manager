<!DOCTYPE html>
<html>

<?= $this->element('head'); ?>

<body>
    <div id="container">
        <?php

        $session = $this->request->getSession();
        $projectID = $session->read('Projectid');
        $visible = '';

        if (is_null($projectID)) {
            $visible = 'visible';
            include ROOT . '/templates/element/intermediate.php';
        }
        ?>

        <?= $this->element('main-menu', $menuItems); ?>

        <div id="main-content" <?= isset($_SESSION['menu_hide']) && $_SESSION['menu_hide'] ? 'class="wide"' : '' ?>>
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content'); ?>
        </div><!-- #flow-content -->
    </div><!-- #container -->
    <div class="notification hidden" onclick="this.classList.add('hidden')">
        <span class="close"><i class="fas fa-times"></i></span>
        <div class="nt-content"><?= isset($message) ? $message : ''; ?></div>
    </div><!-- .notification -->
</body>

</html>