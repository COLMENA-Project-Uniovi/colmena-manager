<?php
$session = $this->request->getSession();
$projects = $session->read('Projects');
?>
<div class="home-selector visible">
    <div class="container">
        <h1>Bienvenido a Colmena</h1>
        <div class="slider-container" style="width: 100%;display: flex;align-items: center;">
            <div id="slide-left-container">
                <div class="slide-left">
                </div>
            </div>
            <?php
            if (isset($projects) && count($projects) > 0) {
            ?>
                <div id="cards-container">
                    <div class="cards">
                        <?php
                        foreach ($projects as $project) {
                        ?>
                            <div class="card project" data-project=<?= $project['id'] ?>>
                                <div class="container">
                                    <h2>
                                        <?= $project['name'] ?>
                                    </h2>
                                    <p><?= $project['description'] ?></p>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>

            <div id="slide-right-container">
                <div class="slide-right">
                </div>
            </div>
        </div>
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

<script>
    /**
     * @description Change Home page slider's arrows active status
     */
    function updateSliderArrowsStatus(
        cardsContainer,
        containerWidth,
        cardCount,
        cardWidth
    ) {
        if (
            $(cardsContainer).scrollLeft() + containerWidth <
            cardCount * cardWidth + 15
        ) {
            $("#slide-right-container").addClass("active");
        } else {
            $("#slide-right-container").removeClass("active");
        }
        if ($(cardsContainer).scrollLeft() > 0) {
            $("#slide-left-container").addClass("active");
        } else {
            $("#slide-left-container").removeClass("active");
        }
    }
    $(function() {
        // Scroll products' slider left/right
        let div = $("#cards-container");
        let cardCount = $(div)
            .find(".cards")
            .children(".card").length;
        let speed = 1000;
        let containerWidth = $(".container").width();
        let cardWidth = 250;

        updateSliderArrowsStatus(div, containerWidth, cardCount, cardWidth);

        //Remove scrollbars
        $("#slide-right-container").click(function(e) {
            if ($(div).scrollLeft() + containerWidth < cardCount * cardWidth) {
                $(div).animate({
                    scrollLeft: $(div).scrollLeft() + cardWidth
                }, {
                    duration: speed,
                    complete: function() {
                        setTimeout(
                            updateSliderArrowsStatus(
                                div,
                                containerWidth,
                                cardCount,
                                cardWidth
                            ),
                            1005
                        );
                    }
                });
            }
            updateSliderArrowsStatus(div, containerWidth, cardCount, cardWidth);
        });
        $("#slide-left-container").click(function(e) {
            if ($(div).scrollLeft() + containerWidth > containerWidth) {
                $(div).animate({
                    scrollLeft: "-=" + cardWidth
                }, {
                    duration: speed,
                    complete: function() {
                        setTimeout(
                            updateSliderArrowsStatus(
                                div,
                                containerWidth,
                                cardCount,
                                cardWidth
                            ),
                            1005
                        );
                    }
                });
            }
            updateSliderArrowsStatus(div, containerWidth, cardCount, cardWidth);
        });

        // If resize action ocurred then update the container width value
        $(window).resize(function() {
            try {
                containerWidth = $("#cards-container").width();
                updateSliderArrowsStatus(div, containerWidth, cardCount, cardWidth);
            } catch (error) {
                console.log(
                    `Error occured while trying to get updated slider container width: 
            ${error}`
                );
            }
        });
    });
</script>