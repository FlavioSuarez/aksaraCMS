<div class="py-3 py-md-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-md-start">
                <h1 class="display-5 fw-bold">
                    <?= $meta->title; ?>
                </h1>
                <p class="lead">
                    <?= $meta->description; ?>
                </p>
                <div class="row mb-5">
                    <div class="col-lg-10">
                        <form action="<?= base_url('blogs/search', ['per_page' => null]); ?>" method="GET" class="form-horizontal position-relative">
                            <div class="input-group input-group-lg">
                                <input type="text" name="q" class="form-control rounded-pill rounded-end" placeholder="<?= phrase('Search post'); ?>" />
                                <button type="submit" class="btn btn-dark  rounded-pill rounded-start">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <?php if ($spotlight): ?>
                    <div class="carousel slide" id="carouselExampleCaptions" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-4 overflow-hidden">
                            <?php foreach ($spotlight as $key => $val): ?>
                                <div class="carousel-item<?= (! $key ? ' active' : null); ?>">
                                    <div class="clip gradient-top"></div>
                                    <a href="<?= base_url(['blogs', $val->category_slug, $val->post_slug]); ?>" class="--xhr d-block">
                                        <img src="<?= get_image('blogs', $val->featured_image); ?>" class="d-block w-100" alt="..." style="max-height:360px;object-fit: cover">
                                    </a>
                                    <div class="carousel-caption text-start">
                                        <div class="mb-3">
                                            <a href="<?= base_url(['blogs', $val->category_slug, $val->post_slug]); ?>" class="--xhr d-block">
                                                <h4 class="text-light">
                                                    <?= truncate($val->post_title, 80); ?>
                                                </h4>
                                                <p class="text-light d-none d-md-inline">
                                                    <?= truncate($val->post_excerpt, 90); ?>
                                                </p>
                                            </a>
                                        </div>
                                        <div class="row g-0 align-items-center">
                                            <div class="col-1">
                                                <a href="<?= base_url('user/' . $val->username); ?>" class="text-sm text-secondary --xhr">
                                                    <img src="<?= get_image('users', $val->photo, 'icon'); ?>" class="img-fluid rounded-circle" alt="..." />
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?= base_url('user/' . $val->username); ?>" class="text-white ps-2 --xhr">
                                                    <b>
                                                        <?= $val->first_name . ' ' . $val->last_name; ?>
                                                    </b>
                                                </a>
                                            </div>
                                            <div class="col-5 text-end">
                                                <small class="text-white text-sm">
                                                    <i class="mdi mdi-clock-outline"></i> <?= time_ago($val->updated_timestamp); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"><?= phrase('Previous'); ?></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"><?= phrase('Next'); ?></span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($articles): ?>
<div class="py-3 bg-light">
    <div class="container">
        <?php foreach ($articles as $key => $val): ?>
            <div class="py-3">
                <a href="<?= base_url(['blogs', $val->category_slug]); ?>" class="--xhr">
                    <h3 class="text-center text-sm-start text-primary mt-3">
                        <?= $val->category_title; ?>
                    </h3>
                </a>
                <p class="text-center text-sm-start">
                    <?= $val->category_description; ?>
                </p>
                <div class="swiper" data-slide-count-sm="2" data-slide-count-md="2" data-slide-count-lg="3" data-slide-count-xl="4" data-autoplay="1">
                    <div class="swiper-wrapper">
                        <?php foreach ($val->posts as $_key => $_val): ?>
                            <div class="swiper-slide">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                    <a href="<?= base_url(['blogs', $val->category_slug, $_val->post_slug]); ?>" class="--xhr d-block">
                                        <div class="position-relative" style="background:url(<?= get_image('blogs', $_val->featured_image, 'thumb'); ?>) center center no-repeat; background-size: cover; height: 256px">
                                            <div class="clip gradient-top"></div>
                                            <div class="position-absolute bottom-0 p-3">
                                                <b class="text-light" data-toggle="tooltip" title="<?= $_val->post_title; ?>">
                                                <?= truncate($_val->post_title, 64); ?>
                                                </b>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="card-body">
                                        <p class="card-text text-secondary">
                                            <?= truncate($_val->post_excerpt, 64); ?>
                                        </p>
                                        <div class="row g-0 align-items-center">
                                            <div class="col-1">
                                                <a href="<?= base_url('user/' . $_val->username); ?>" class="text-sm text-secondary">
                                                    <img src="<?= get_image('users', $_val->photo, 'icon'); ?>" class="img-fluid rounded-circle" alt="..." />
                                                </a>
                                            </div>
                                            <div class="col-7">
                                                <a href="<?= base_url('user/' . $_val->username); ?>" class="text-sm text-dark ps-2">
                                                    <b>
                                                        <?= $_val->first_name . ' ' . $_val->last_name; ?>
                                                    </b>
                                                </a>
                                            </div>
                                            <div class="col-4 text-end">
                                                <small class="text-muted text-sm">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?= time_ago($_val->updated_timestamp); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
