<?php
    $this->layout('layouts/master', ['title' => 'Katcher - Convert to .mp4'])
    /** @var \Katcher\Data\KatcherUrl $katcherURL */
?>

<div class="row">
    <div class="col-md-5">
        <form action="" method="POST">
            <?php if ($isAllDownloaded) : ?>
                <div class="alert alert-success">
                    All files were downloaded.
                </div>
            <?php else : ?>
                <?php if ($hasMissingFiles) : ?>
                    <div class="alert alert-danger">
                        Manually download these file(s) again:
                        <ul class="file-links">
                            <?php foreach ($missingFiles as $value) : ?>
                                <li><a href="<?= $katcherURL->fileURL($value) ?>"><?= $value ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($hasNonexistentFiles) : ?>
                    <div class="alert alert-warning">
                        These file(s) were not found:
                        <ul class="file-links">
                            <?php foreach ($nonexistentFiles as $value) : ?>
                                <li><a href="<?= $katcherURL->fileURL($value) ?>"><?= $value ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Combine Files</button>
        </form>
    </div>
</div>
