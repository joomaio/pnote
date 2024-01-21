<script>
    $(document).ready(function(e) {
        var tableId = '<?php echo $this->data['id']; ?>';
        var data = JSON.parse($(`#data-table-${tableId}`).html());

        const container = document.querySelector(`#preview-table-${tableId}`);
        var myHeaders = data ? data['colHeaders'] : [''];
        var tableData = data ? data['data'] : [['']];

        const hot = new Handsontable(container, {
            readOnly: true,
            contextMenu: false,
            disableVisualSelection: true,
            manualColumnResize: true,
            manualRowResize: true,
            colHeaders: myHeaders,
            rowHeaders: true,
            data: tableData,
            colWidths: 200,
            height: 'auto',
            cells: function(row, col, prop) {
                var cellProp = {};
                cellProp.renderer = htmlRenderer;
                return cellProp
            },
            licenseKey: 'non-commercial-and-evaluation'
        });

        function decodeHtmlEntities(encodedString) {
            var elem = document.createElement('textarea');
            elem.innerHTML = encodedString;

            return elem.value;
        }

        function htmlRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.HtmlRenderer.apply(this, arguments);
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = decodeHtmlEntities(value);

            var scriptTags = tempDiv.getElementsByTagName('script');
            for (var i = scriptTags.length - 1; i >= 0; i--) {
                scriptTags[i].parentNode.removeChild(scriptTags[i]);
            }
            td.innerHTML = tempDiv.innerHTML;
        }
    });
</script>