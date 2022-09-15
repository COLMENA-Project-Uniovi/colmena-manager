<?php
$session = $this->request->getSession();
$projects = $session->read('Projects');
?>

<div class="home-selector visible">
    <div class="container">
        <h1>Bienvenido a Colmena</h1>
        <?php 
        
        if(isset($projects) && count($projects) > 0){
            foreach ($projects as $project) {
            ?>
                <div class="project" data-project=<?= $project['id'] ?>>
                    <p><?= $project['name'] ?></p>
                </div>
            <?php
            }
        } else {
            // TODO Botón para añadir proyectos
        }
        ?>
    </div>
</div>

<script>
    $(function() {
        $('.project').click(function() {
            $.ajax({
                method: 'POST',
                url: "<?= $this->Url->build(['plugin' => false, 'controller' => 'admin-users', 'action' => 'startSession']) ?>",
                data: {
                    projectID: $(this).data('project'),
                },
                success: function(response) {
                    $('.home-selector').removeClass('visible');
                    $('.nav nav').addClass('visible');
                }
            })
        })
    })
</script>