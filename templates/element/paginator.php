    <div class="paginator m-4">
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
