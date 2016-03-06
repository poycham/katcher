<?php $this->layout('layouts/master', ['title' => 'Katcher - Download .mp4']) ?>

<div class="row">
    <div class="col-md-5">
        <form id="download-form" action="" method="POST">
            <button type="submit" class="btn btn-primary">Download Again</button>
        </form>
    </div>
</div>

<?php $this->start('scripts') ?>
<script>
    $('#download-form').submit();
</script>
<?php $this->stop() ?>
