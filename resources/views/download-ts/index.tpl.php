<?php $this->layout('layouts/master', ['title' => 'Katcher - Download .ts Videos from katch.me'])
/** @var \Katcher\Data\Input $input */
?>

<div class="row">
    <div class="col-md-5">
        <form action="/download-ts" method="POST">
            <?php if (count($errors) > 0) : ?>
                <div class="alert alert-danger">
                    <h4>You have errors with your input!</h4>
                    <ul>
                        <?php foreach ($errors as $key => $value) : ?>
                            <li><?= $value ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <input type="url" name="url" value="<?= $this->e($input->getValue('url')) ?>" class="form-control" placeholder="URL" required>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <input type="number" name="first_part" value="<?= $this->e($input->getValue('first_part')) ?>" class="form-control" placeholder="First Part" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <input type="number" name="last_part" value="<?= $this->e($input->getValue('last_part')) ?>"  class="form-control" placeholder="Last Part" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Download Files</button>
        </form>
    </div>
</div>