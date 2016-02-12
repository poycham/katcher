<?php $this->layout('layouts/master') ?>

<div class="row">
    <div class="col-md-5">
        <form action="" method="POST">
            <?php if (count($nonexistentFiles) > 0) : ?>
                <div class="alert alert-warning">
                    These file(s) were not found: <?= implode(', ', array_map($getDownloadLink, $nonexistentFiles)) ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Combine Files</button>
        </form>
    </div>
</div>
