<?php
$session = $this->request->getSession();
$projects = $session->read('Projects');
?>
<div class="home-selector visible">
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
                                    <h5>
                                        <?= $project['name'] ?>
                                    </h5>
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
                    <div class="card card-plain border add-project">
                        <div class="card-body">
                            <a href="javascript:;">
                                <i class="fa fa-plus text-secondary mb-3" aria-hidden="true"></i>
                                <h5 class=" text-secondary"> New project </h5>
                            </a>
                        </div>
                    </div>
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
            })
        })
    })
</script>