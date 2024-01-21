<?php 
    $this->theme->add($this->url . 'assets/handsontable/css/handsontable.full.min.css', '', 'handsontable-css');
    $this->theme->add($this->url . 'assets/handsontable/js/handsontable.full.min.js', '', 'bootstrap-handsontable');
    echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-sm-12">
            <textarea style="height:0px;visibility:hidden;" id="data-table-<?php echo $this->data['id']; ?>"><?php echo json_encode($this->data['products']); ?></textarea>
            <div id="preview-table-<?php echo $this->data['id']; ?>"></div>
        </div>
    </div>
</div>

<?php echo $this->render('backend.preview.javascript'); ?>
