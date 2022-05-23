<?php
$projects = [
    [
        'id' => '1',
        'title' => 'Curso 2022-2023',
        'subjects' => [
            'algorithm' => 'test'
        ]
    ],
    [
        'id' => '2',
        'title' => 'Curso 2023-2024',
        'subjects' => [
            'algorithm' => 'test2'
        ]
    ]
]; ?>
<div class="home-selector visible">
    <h1>Bienvenido a Colmena</h1>

    <?php foreach ($projects as $project) {
    ?>
        <div class="project" data-project=<?= $project['id'] ?>>
            <p><?= $project['title'] ?></p>
        </div>
    <?php
    } ?>
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