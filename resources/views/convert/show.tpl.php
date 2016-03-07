<?php
    $this->layout('layouts/master', ['title' => 'Katcher - Convert to .mp4'])
    /** @var \Katcher\Data\KatcherUrl $katcherURL */
?>

<div class="row">
    <div class="col-md-5">
        <form action="<?= ($hasMissingFiles) ? '/download-ts/missing' : '' ?>" method="POST">
            <?php if ($isAllDownloaded) : ?>
                <div class="alert alert-success">
                    All files were downloaded.
                </div>
            <?php else : ?>
                <?php if ($hasMissingFiles) : ?>
                    <div class="alert alert-danger">
                        Download these file(s) again:
                        <ul class="file-links">
                            <?php foreach ($missingFiles as $value) : ?>
                                <li><a href="<?= $this->e($katcherURL->getFileURL($value)) ?>"><?= $this->e($value) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($hasNonexistentFiles) : ?>
                    <div class="alert alert-warning">
                        These file(s) were not found:
                        <ul class="file-links">
                            <?php foreach ($nonexistentFiles as $value) : ?>
                                <li><a href="<?= $this->e($katcherURL->getFileURL($value)) ?>"><?= $this->e($value) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($hasMissingFiles) : ?>
                <input type="hidden" name="folder" value="<?= $this->e($folder) ?>">
                <button type="submit" class="btn btn-primary">Download Missing Files</button>
            <?php else : ?>
                <button type="submit" class="btn btn-primary">Convert</button>
            <?php endif; ?>
        </form>
    </div>
</div>
