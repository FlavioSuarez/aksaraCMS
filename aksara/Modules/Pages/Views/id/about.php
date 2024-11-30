<div class="py-3 py-md-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-3 col-sm-2 col-md-1">
                <i class="<?= $meta->icon; ?> mdi-4x"></i>
            </div>
            <div class="col-9 col-sm-10 col-md-11">
                <h3 class="mb-0">
                    <?= $meta->title; ?>
                </h3>
                <p class="lead">
                    <?= truncate($meta->description, 256); ?>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="py-3 py-md-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h6 class="mb-3">
                    <?= phrase('Local Variables'); ?>
                </h6>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-12">
                        <div class="mb-3">
                            <label class="d-block text-muted mb-0">
                                AKSARA
                            </label>
                            <label class="d-block fw-bold">
                                <?= aksara('version'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-12">
                        <div class="mb-3">
                            <label class="d-block text-muted mb-0">
                                <?= phrase('Build Version'); ?>
                            </label>
                            <label class="d-block fw-bold">
                                <?= aksara('build_version'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="d-block text-muted mb-0">
                        <?= phrase('Last Modified'); ?>
                    </label>
                    <label class="d-block">
                        <?= aksara('date_modified'); ?>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="mb-3">
                    <?= phrase('The Laborant'); ?>
                </h6>
                <div class="mb-3">
                    <label class="d-block mb-0">
                        <a href="//abydahana.github.io" class="fw-bold" target="_blank">
                            Aby Dahana
                            <i class="mdi mdi-open-in-new"></i>
                        </a>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="mb-3">
                    <?= phrase('Contributors'); ?>
                </h6>
                <div class="mb-3">
                    <label class="d-block mb-0">
                        <a href="//ganjar.id" class="fw-bold" target="_blank">
                            Ganjar Nugraha
                            <i class="mdi mdi-open-in-new"></i>
                        </a>
                    </label>
                </div>
            </div>
        </div>
        <br />
        <h6 class="mb-3">
            <?= phrase('Dependencies'); ?>
        </h6>
        <div class="mb-3">
            <a href="//codeigniter.com" target="_blank">
                CodeIgniter
            </a>
            &middot;
            <a href="//geophp.net" target="_blank">
                GeoPHP
            </a>
            &middot;
            <a href="//hybridauth.github.io" target="_blank">
                Hybridauth
            </a>
            &middot;
            <a href="//github.com/halaxa/json-machine" target="_blank">
                JSON Machine
            </a>
            &middot;
            <a href="//mpdf.github.io" target="_blank">
                mPDF
            </a>
            &middot;
            <a href="//github.com/chillerlan/php-qrcode" target="_blank">
                PHP QR-Code
            </a>
            &middot;
            <a href="//github.com/picqer/php-barcode-generator" target="_blank">
                PHP Barcode
            </a>
            &middot;
            <a href="//github.com/phpoffice/phpspreadsheet" target="_blank">
                PHPSpreadSheet
            </a>
        </div>
        <br />
        <h6 class="mb-3">
            JS/CSS <small class="text-muted">(<?= phrase('sorted ascending'); ?>)</small>
        </h6>
        <div class="mb-3">
            <a href="//github.com/dreamerslab/jquery.actual" target="_blank">
                Actual
            </a>
            &middot;
            <a href="//devbridge.com/sourcery/components/jquery-autocomplete/" target="_blank">
                Autocomplete
            </a>
            &middot;
            <a href="//github.com/BobKnothe/autoNumeric" target="_blank">
                autoNumeric
            </a>
            &middot;
            <a href="//getbootstrap.com" target="_blank">
                Bootstrap
            </a>
            &middot;
            <a href="//victor-valencia.github.io/bootstrap-iconpicker/" target="_blank">
                Bootstrap Iconpicker
            </a>
            &middot;
            <a href="//bootstrap-datepicker.readthedocs.io/en/latest/" target="_blank">
                Bootstrap Datepicker
            </a>
            &middot;
            <a href="//datatables.net" target="_blank">
                Datatables
            </a>
            &middot;
            <a href="//fullcalendar.io" target="_blank">
                FullCalendar
            </a>
            &middot;
            <a href="//html2canvas.hertzen.com" target="_blank">
                html2canvas
            </a>
            &middot;
            <a href="//highcharts.com" target="_blank">
                HighCharts
            </a>
            &middot;
            <a href="//jquery.com" target="_blank">
                jQuery
            </a>
            &middot;
            <a href="//jqueryui.com/draggable/" target="_blank">
                jQuery UI Draggable
            </a>
            &middot;
            <a href="//github.com/MrRio/jsPDF" target="_blank">
                jsPDF
            </a>
            &middot;
            <a href="//stuk.github.io/jszip/" target="_blank">
                JSZip
            </a>
            &middot;
            <a href="//materialdesignicons.com" target="_blank">
                Material Design Icons
            </a>
            &middot;
            <a href="//manos.malihu.gr/jquery-custom-content-scroller/" target="_blank">
                mCustomScrollbar
            </a>
            &middot;
            <a href="//www.mediaelementjs.com/" target="_blank">
                Mediaelementjs
            </a>
            &middot;
            <a href="//github.com/moment/moment/" target="_blank">
                MomentJS
            </a>
            &middot;
            <a href="//openlayers.org" target="_blank">
                OpenLayers
            </a>
            &middot;
            <a href="//prismjs.com" target="_blank">
                Prism
            </a>
            &middot;
            <a href="//github.com/julien-maurel/jQuery-Scanner-Detection" target="_blank">
                Scanner
            </a>
            &middot;
            <a href="//select2.org" target="_blank">
                Select2
            </a>
            &middot;
            <a href="//camohub.github.io/jquery-sortable-lists/" target="_blank">
                Sortable
            </a>
            &middot;
            <a href="//summernote.org" target="_blank">
                Summernote
            </a>
            &middot;
            <a href="//swiperjs.com" target="_blank">
                SwiperJS
            </a>
            &middot;
            <a href="//github.com/customd/jquery-visible" target="_blank">
                Visible
            </a>
        </div>
        <br />
        <br />
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <p class="lead text-center">
                    Terima kasih kepada para pengembang yang membagikan karya mereka secara cuma-cuma. Tanpa mereka, <a href="//aksaracms.com" class="text-primary fw-bold" target="_blank">Aksara</a> tidak akan pernah terwujud.
                    <br />
                    Kita luar biasa!
                    <br />
                    <br />
                    <i class="mdi mdi-heart text-danger"></i>
                    <a href="//abydahana.github.io" target="_blank">
                        <b>
                            Aby Dahana
                        </b>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
