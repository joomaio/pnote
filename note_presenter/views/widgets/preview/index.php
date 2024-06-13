<?php use SPT\Theme;

static $presenter;
if(!isset($presenter))
{
    $this->theme->add($this->url . 'assets/fabric/fabric.min.js', '', 'fabric.min.js', 'top');
}
?>
<?php if ($this->data['status'] != -2) : ?>
    <div class="container align-items-center preview-mode mx-auto pt-3">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row " id="preview-<?php echo $this->data['id'] ?>">
                    <div class="col-12 overflow-auto" id="editor-canvas-<?php echo $this->data['id']; ?>">
                        <canvas id="canvas-<?php echo $this->data['id']; ?>"></canvas>
                    </div>
                    <div class="col-12">
                        <div class="container-fluid text-center my-3">
                            <span class="index-page-canvas">
                            </span> / 
                            <span class="total-page-canvas">
                            </span>
                        </div>
                        <div class="container-fluid text-center my-3">
                            <a class="btn btn-primary me-3 previous-button"><i class="fa-solid fa-chevron-left"></i>
                            </a>
                            <a class="btn btn-primary me-3 next-button"><i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input name="presenter-<?php echo $this->data['id']; ?>" type="hidden" id="presenter-<?php echo $this->data['id']; ?>" value='<?php echo $this->data['data'] ?>' />
    <script>
        $(document).ready(function(e) {
            var canvas_<?php echo $this->data['id']; ?>;
            var fix_height = 0;
            if (!canvas_<?php echo $this->data['id']; ?>) {
                canvas_<?php echo $this->data['id']; ?> = new fabric.Canvas('canvas-<?php echo $this->data['id']; ?>');
            }
            canvas_<?php echo $this->data['id']; ?>.setDimensions({
                width: $('#editor-canvas-<?php echo $this->data['id']; ?>').width(),
                height: 600
            });

            canvas_<?php echo $this->data['id']; ?>.setBackgroundColor('#565656', canvas_<?php echo $this->data['id']; ?>.renderAll.bind(canvas_<?php echo $this->data['id']; ?>));
            var objects = canvas_<?php echo $this->data['id']; ?>.getObjects();
            for (var i = 0; i < objects.length; i++) {
                objects[i].selectable = false;
            }

            var data_canvas = $('#presenter-<?php echo $this->data['id'] ?>').val();
            data_canvas = data_canvas ? JSON.parse(data_canvas) : [];
            var total_page_canvas = 1;
            var canvas_index = 0;
            if (data_canvas && data_canvas.length)
            {
                total_page_canvas = data_canvas.length;
                Import(data_canvas[canvas_index]);
            }
            else
            {
                data_canvas = [];
            }

            loadPagination(total_page_canvas, canvas_index);

            function Import(data) {
                canvas_<?php echo $this->data['id']; ?>.loadFromJSON(data, function () {
                    canvas_<?php echo $this->data['id']; ?>.renderAll();
                    reRender();
                });
            }

            function reRender() {
                backgroundImage =  canvas_<?php echo $this->data['id']; ?>.backgroundImage;
                var current_width = $("#editor-canvas-<?php echo $this->data['id']; ?>").width();
                var current_height = $("#editor-canvas-<?php echo $this->data['id']; ?>").height();
                if (backgroundImage) {
                    let width = backgroundImage.getScaledWidth();
                    let height = backgroundImage.getScaledHeight();
                    // console.log(height);
                    canvas_<?php echo $this->data['id']; ?>.setZoom(1);
                    canvas_<?php echo $this->data['id']; ?>.setDimensions({
                        width: width,
                        height: height
                    });
                     canvas_<?php echo $this->data['id']; ?>.set();
                }
                else {
                    if(!fix_height)
                    {
                        fix_height = 600;
                    }

                     canvas_<?php echo $this->data['id']; ?>.setDimensions({
                        width: $("#editor-canvas-<?php echo $this->data['id']; ?>").width(),
                        height: fix_height
                    });
                }

                var outerCanvasContainer = $("#editor-canvas-<?php echo $this->data['id']; ?>");

                var ratio          =  canvas_<?php echo $this->data['id']; ?>.getWidth() /  canvas_<?php echo $this->data['id']; ?>.getHeight();
                var containerWidth = outerCanvasContainer.width();
                var scale          = containerWidth /  canvas_<?php echo $this->data['id']; ?>.getWidth();
                var zoom           =  canvas_<?php echo $this->data['id']; ?>.getZoom() * scale;
                if(!fix_height)
                {
                    fix_height = containerWidth / ratio;
                }

                canvas_<?php echo $this->data['id']; ?>.setDimensions({width: containerWidth, height: fix_height ? fix_height : containerWidth / ratio});
                canvas_<?php echo $this->data['id']; ?>.setViewportTransform([zoom, 0, 0, zoom, 0, 0]);
            }

            $('#preview-<?php echo $this->data['id'] ?> .previous-button').on("click", function()
            {
                data_canvas[canvas_index] = canvas_<?php echo $this->data['id'];?>.toJSON();
                canvas_index--;
                loadPagination(total_page_canvas, canvas_index);
                var content = data_canvas[canvas_index];
                Import(content);
            });

            $('#preview-<?php echo $this->data['id'] ?> .next-button').on("click", function()
            {
                data_canvas[canvas_index] = canvas_<?php echo $this->data['id'];?>.toJSON();
                canvas_index++;
                loadPagination(total_page_canvas, canvas_index);
                var content = data_canvas[canvas_index];
                Import(content);
            });

            function loadPagination(totalPage = 0, index = 0)
            {
                $('#preview-<?php echo $this->data['id'] ?> .next-button').removeClass('d-none');
                $('#preview-<?php echo $this->data['id'] ?> .previous-button').removeClass('d-none');

                $('#preview-<?php echo $this->data['id'] ?> .index-page-canvas').text(index + 1);
                $('#preview-<?php echo $this->data['id'] ?> .total-page-canvas').text(totalPage);


                if (!totalPage || !index || totalPage == 1 || index == 0) {
                    $('#preview-<?php echo $this->data['id'] ?> .previous-button').addClass('d-none');
                }

                if (!totalPage || index + 1 == totalPage) {
                    $('#preview-<?php echo $this->data['id'] ?> .next-button').addClass('d-none');
                }
            }

        });
    </script>
<?php endif; ?>