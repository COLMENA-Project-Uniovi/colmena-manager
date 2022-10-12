<?php
$session = $this->request->getSession();
$projects = $session->read('Projects');
?>
<div class="home-selector <?= $visible ?> ">
    <div class="card card-plain">
        <div class="card-header">
            <h1 class="card-title">Selecciona uno de tus proyectos</h1>
        </div>

        <div class="card-body">
            <div class="row">
                <?php
                foreach ($projects as $project) {
                ?>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="project-title">
                                    <h2>
                                        <?= $project['name'] ?>
                                    </h2>
                                </div>
                                <p class="project-description">
                                    <?= $project['description'] ?>
                                </p>

                                <button class="button project-button" data-project="<?= $project['id'] ?>">
                                    Ver projecto
                                </button>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="col">
                    <a class="card card-plain border add-project" href="<?= $this->Url->build(['plugin' => 'Colmena/AcademicalManager', 'controller' => 'projects', 'action' => 'add']) ?>">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <h2 class="p-2">Nuevo proyecto</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.project-button').click(function() {
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
            });
        });
    });

    $(function() {
        $('.add-project').click(function() {
            $('.home-selector').removeClass('visible');
            $('.home-selector').addClass('test');
            $('.nav').prop('disabled', true);
        });
    })

    if (window.location.href.indexOf("projects/add") > -1) {
        $('.home-selector').removeClass('visible');
    }
</script>