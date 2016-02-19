<?php $this->layout('layouts/master', ['title' => 'Katcher - Download .ts Videos']) ?>

<div class="row">
    <div class="col-md-5">
        <form action="" method="POST">
            <div class="form-group">
                <input type="url" name="url" class="form-control" placeholder="URL" required>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <input type="number" name="first_part" class="form-control" placeholder="First Part">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <input type="number" name="last_part"  class="form-control" placeholder="Last Part">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Download Files</button>
        </form>
    </div>
</div>