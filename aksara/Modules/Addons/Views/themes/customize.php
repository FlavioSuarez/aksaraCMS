
<?php if (! $writable): ?>
    <div class="alert alert-danger border-0 rounded-0 mb-0">
        <div class="container">
            <h5>
                <?= phrase('Notice'); ?>
            </h5>
            <p class="mb-0 text-danger">
                <b><?= ROOTPATH . 'themes' . DIRECTORY_SEPARATOR . $detail->folder . DIRECTORY_SEPARATOR . 'package.json'; ?></b> <?= phrase('is not writable'); ?>
            </p>
        </div>
    </div>
<?php endif; ?>
<div class="container-fluid py-3">
    <form action="<?= current_page(); ?>" method="POST">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body p-2 rounded" style="background:<?= (isset($detail->colorscheme->page->background) ? $detail->colorscheme->page->background : '#ffffff'); ?>; color:<?= (isset($detail->colorscheme->page->text) ? $detail->colorscheme->page->text : '#333333'); ?>">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <b>
                                    <?= phrase('Page Color Scheme'); ?>
                                </b>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Background'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[page][background]" class="form-control form-control-color background-color" value="<?= (isset($detail->colorscheme->page->background) ? $detail->colorscheme->page->background : '#ffffff'); ?>" />
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Foreground'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[page][text]" class="form-control form-control-color foreground-color" value="<?= (isset($detail->colorscheme->page->text) ? $detail->colorscheme->page->text : '#333333'); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body p-2 rounded" style="background:<?= (isset($detail->colorscheme->header->background) ? $detail->colorscheme->header->background : '#333333'); ?>; color:<?= (isset($detail->colorscheme->header->text) ? $detail->colorscheme->header->text : '#fafafa'); ?>">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <b>
                                    <?= phrase('Header Color Scheme'); ?>
                                </b>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Background'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[header][background]" class="form-control form-control-color background-color" value="<?= (isset($detail->colorscheme->header->background) ? $detail->colorscheme->header->background : '#333333'); ?>" />
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Foreground'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[header][text]" class="form-control form-control-color foreground-color" value="<?= (isset($detail->colorscheme->header->text) ? $detail->colorscheme->header->text : '#fafafa'); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body p-2 rounded" style="background:<?= (isset($detail->colorscheme->sidebar->background) ? $detail->colorscheme->sidebar->background : '#ffffff'); ?>; color:<?= (isset($detail->colorscheme->sidebar->text) ? $detail->colorscheme->sidebar->text : '#333333'); ?>">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <b>
                                    <?= phrase('Sidebar Color Scheme'); ?>
                                </b>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Background'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[sidebar][background]" class="form-control form-control-color background-color" value="<?= (isset($detail->colorscheme->sidebar->background) ? $detail->colorscheme->sidebar->background : '#fafafa'); ?>" />
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Foreground'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[sidebar][text]" class="form-control form-control-color foreground-color" value="<?= (isset($detail->colorscheme->sidebar->text) ? $detail->colorscheme->sidebar->text : '#333333'); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body p-2 rounded" style="background:<?= (isset($detail->colorscheme->footer->background) ? $detail->colorscheme->footer->background : '#ffffff'); ?>; color:<?= (isset($detail->colorscheme->footer->text) ? $detail->colorscheme->footer->text : '#333333'); ?>">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <b>
                                    <?= phrase('Footer Color Scheme'); ?>
                                </b>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Background'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[footer][background]" class="form-control form-control-color background-color" value="<?= (isset($detail->colorscheme->footer->background) ? $detail->colorscheme->footer->background : '#ffffff'); ?>" />
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <?= phrase('Foreground'); ?>
                                    </span>
                                    <input type="color" name="colorscheme[footer][text]" class="form-control form-control-color foreground-color" value="<?= (isset($detail->colorscheme->footer->text) ? $detail->colorscheme->footer->text : '#333333'); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="opt-btn-overlap-fix"></div>
        <div class="row opt-btn">
            <div class="col-md-8">
                <a href="<?= current_page('../', ['theme' => null]); ?>" class="btn btn-link --xhr">
                    <i class="mdi mdi-arrow-left"></i>
                    <?= phrase('Back'); ?>
                </a>
                <button type="submit" class="btn btn-primary float-end">
                    <i class="mdi mdi-check"></i>
                    <?= phrase('Update'); ?>
                    <em class="text-sm">(ctrl+s)</em>
                </button>
            </div>
        </div>
    </form>
</div>
