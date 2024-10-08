<?php
    $video_type = null;

    if (stripos($results->field_data->video_url->value, '/youtube.com') !== false) {
        $video_type = 'video/x-youtube';
    } else if (stripos($results->field_data->video_url->value, 'facebook.com') !== false) {
        $video_type = 'video/facebook';
    } else if (stripos($results->field_data->video_url->value, 'vimeo.com') !== false) {
        $video_type = 'video/vimeo';
    } else if (stripos($results->field_data->video_url->value, 'dailymotion.com') !== false || stripos($results->field_data->video_url->value, 'dai.ly') !== false) {
        $video_type = 'video/dailymotion';
    } else if (stripos($results->field_data->video_url->value, 'twitch.tv') !== false) {
        $video_type = 'video/twitch';
    }
?>

<div class="row g-0 bg-light">
    <div class="col-lg-8">
        <div class="sticky-top">
            <div class="full-height bg-secondary p-3 d-flex align-items-center">
                <video role="videoplayer" id="video" class="rounded-4">
                    <source src="<?= $results->field_data->video_url->value; ?>" type="<?= $video_type; ?>">
                </video>
            </div>
        </div>
    </div>
    <div class="col-lg-4 p-3 bg-white">
        <div class="sticky-top">
            <div class="row align-items-center mb-3">
                <div class="col-2 pe-0">
                    <a href="<?= base_url('user/' . $results->field_data->username->value); ?>" class="--xhr">
                        <img src="<?= get_image('users', $results->field_data->photo->value, 'thumb'); ?>" class="img-fluid rounded-circle" />
                    </a>
                </div>
                <div class="col-10">
                    <h5 class="fw-bold mb-0">
                        <a href="<?= current_page('../'); ?>" class="float-end btn btn-close --xhr">&nbsp;</a>
                        <a href="<?= base_url('user/' . $results->field_data->username->value); ?>" class="--xhr">
                            <?= $results->field_data->first_name->value . ' ' . $results->field_data->last_name->value; ?>
                        </a>
                    </h5>
                    <p class="mb-0">
                        <span class="text-muted" data-bs-toggle="tooltip" title="<?= $results->field_data->timestamp->value; ?>">
                            <?= time_ago($results->field_data->timestamp->value); ?>
                        </span>
                    </p>
                </div>
            </div>
            <h4>
                <?= $results->field_data->title->value; ?>
            </h4>
            <div>
                <?= custom_nl2br($results->field_data->description->value, 1); ?>
            </div>
            <div>
                <?= comment_widget(['post_id' => $results->field_data->id->value, 'path' => service('uri')->getRoutePath()]); ?>
            </div>
        </div>
    </div>
</div>
