<script>
    function loadShortcut()
    {
        $.ajax({
            url: '<?php echo $this->link_shortcut_list; ?>',
            type: 'GET',
            success: function(result) {
                console.log(result);
            }
        });
    }

    $(document).ready(function(){
        $('#form_shortcut').on('submit', function(e){
            e.preventDefault();
            var form = new FormData($('#form_shortcut')[0])
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                success: function(result) {
                    if (result.status != 'done') {
                        var message = result.message ? result.message : 'Save Failed';
                        alert(result.message);
                    } 
                    
                    loadShortcut();
                    $('#shortcutModel').modal('hide');
                }
            });
        })
    })
</script>