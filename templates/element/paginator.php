    <div class="paginator">
    <?php
        echo $this->Paginator->prev('«');
        echo $this->Paginator->numbers(
            [
                'first' => 'Primera',
                'last' => 'Última',
                'modulus' => '4',
                'separator' => '',
                'currentTag' => 'a'
            ]
        );
        echo $this->Paginator->next('»');
    ?>
    </div>
