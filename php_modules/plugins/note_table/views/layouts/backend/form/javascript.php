<script>
    function loadAction()
    {
        var number_col = $('.list-products th').length;
        if (number_col < 3)
        {
            $('.list-products .remove-product').addClass('d-none');
        }
        else
        {
            $('.list-products .remove-product').removeClass('d-none');
        }

        var number_row = $('.feature-list .feature-item').length
        if (number_row < 2)
        {
            $('.feature-list .remove-feature').addClass('d-none');
        }
        else
        {
            $('.feature-list .remove-feature').removeClass('d-none');
        }

        $('.feature-list .remove-feature').off('click').on('click', function(e){
            e.preventDefault();
            $(this).parents('.feature-item').remove();
            loadAction();
        });

        $('.list-products .remove-product').off('click').on('click', function(e){
            e.preventDefault();
            var index = $(this).parents('.product-item').index();
            $(this).parents('.product-item').remove();
            $('.feature-item').each(function(i)
            {
                $(this).find('.feature-content').eq(index-1).remove();
            });
            loadAction();
        });

        $('.list-products .product-item').off('click').on('click', function(){
            $('#note_select_id').val(null);
            $('#note_select_id').trigger('change');

            $('#popupFormCol').modal('show');
            var index  = $(this).index();
            var title = $(this).find('input[name=title_product]').val();
            var link = $(this).find('input[name=link_product]').val();
            var id = $(this).find('input[name=id_product]').val();
            
            $('#name_product').val(title);
            $('#link_product').val(link);
            $('#id_product').val(id);
            $('#index_product').val(index);
        });

        $('.feature-item .feature-title').off('click').on('click', function(){
            $(this).find('.content').addClass('d-none');
            $(this).find('input[name=feature-title]').removeClass('d-none');
            $(this).find('input[name=feature-title]').focus();
        });

        $('.feature-item .feature-title input[name=feature-title]').off('keypress').on("keypress",function(e){
            if(e.which === 13){
                e.preventDefault();
                $(this).parents('.feature-title').find('.content').removeClass('d-none');
                $(this).parents('.feature-title').find('.content').text($(this).val());
                $(this).addClass('d-none');
            }
        });

        $('.feature-item .feature-title input[name=feature-title]').off('blur').on('blur', function(){
            $(this).parents('.feature-title').find('.content').removeClass('d-none');
            $(this).parents('.feature-title').find('.content').text($(this).val());
            $(this).addClass('d-none');
        });

        $('.feature-item .feature-content').off('click').on('click', function(){
            var index_row = $(this).parents('.feature-item').index();
            var index_col = $(this).index();
            var content = $(this).find('input[name=feature-content]').val();
            tinyMCE.get('data').setContent(content ?? '');
            $('#popupDesFeature #index_row').val(index_row);
            $('#popupDesFeature #index_col').val(index_col);

            $(this).find('.content').addClass('d-none');
            $(this).find('input[name=feature-title]').removeClass('d-none');
            $('#popupDesFeature').modal('show');
        });
    }
    $(document).ready(function(e) {
        loadAction();

        $("#note_select_id").select2({
            matcher: matchCustom,
            placeholder: 'Select Note',
            minimumInputLength: 1,
            dropdownParent : "#popupFormCol",
            ajax: {
                url: '<?php echo $this->link_note_search ?>',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function(params) {
                    var ignore = [];
                    $('.product-item input[name=id_product]').each(function(){
                        var id = $(this).val();
                        if (id)
                        {
                            ignore.push(id);
                        }
                    });
                    return {
                        search: params.term,
                        ignore: ignore.join(','),
                    };
                },
                processResults: function(data, params) {
                    let items = [];
                    let list = data.data;
                    if (Array.isArray(list) && list.length > 0) {
                        list.forEach(function(item) {
                            items.push({
                                id: item.id,
                                text: item.title,
                                link: item.link,
                                features: item.features
                            })
                        })
                    }

                    return {
                        results: items,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true
            },
        });

        $('#update_feature_des').on('click', function(){
            var content = tinymce.get("data").getContent();
            var index_row = $('#index_row').val();
            var index_col = $('#index_col').val();
            $('.feature-list .feature-item').eq(index_row).find('.feature-content').eq(index_col - 1).find('.des').html(content);
            $('.feature-list .feature-item').eq(index_row).find('.feature-content').eq(index_col - 1).find('input[name=feature-content]').val(content);
            $('#popupDesFeature').modal('hide');
        });

        $('#add-note-button').on('click', function(){
            select = $('#note_select_id').select2('data');
            var index_product = $('#index_product').val();
            if(!select.length)
            {
                alert('Please select note!');
                return false;
            }
            var note = {
                title : select[0].text,
                id : select[0].id,
                link : select[0].link,
                features : select[0].features,
            }

            if(index_product > 0)
            {
                updateCol(note, index_product);
            }
            else
            {
                addCol(note);
            }

        });

        function matchCustom(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            // Return `null` if the term should not be displayed
            return null;
        }

        $(".btn_save_close").click(function(e) {
            e.preventDefault();
            $("#save_close").val(1);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val())
            {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            loadStructure();
            $('#form_submit').submit();
        });

        $(".btn_apply").click(function(e) {
            e.preventDefault();
            $("#save_close").val(0);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val())
            {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            loadStructure();
            $('#form_submit').submit();
        });

        $('#new_col').on('click', function(){
            var note = {
                id : 0,
                title : '',
                link : ''
            };
            addCol(note);
        })

        $('#new_row').on('click', function(){
            addRow();
        })

        $('#new-col-form').on('submit', function(e){
            e.preventDefault();
            var title = $('#name_product').val();
            var link = $('#link_product').val();
            var id = $('#id_product').val();

            var item = {
                title : title,
                link : link,
                id : id,
                row: [],
            };

            var index = $('#index_product').val();
            if(index > 0)
            {
                updateCol(item, index);
            }
            else
            {
                addCol(item);
            }
        });
    });

    function addCol(item)
    {
        var product = `
        <th scope="col" class="border-top product-item position-relative">
            <div class="content p-0">
                ${item.title}
            </div>
            <a class="remove-product position-absolute" href="">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <input type="hidden" name="title_product" value="${item.title}">
            <input type="hidden" name="link_product" value="${item.link}">
            <input type="hidden" name="id_product" value="0">
        </th>`;
        var feature_content =`<td class="feature-content">
                    <div class="des">
                    </div>
                    <input type="hidden" name="feature-content">
                </td>`;
        $('.list-products').append(product);
        $('.feature-item').append(feature_content);
        var index = $('.list-products .product-item').length;
        if(item.features)
        {
            item.features.forEach(function(value, i){
                var tmp = $('.feature-list .feature-item').eq(i);
                if(tmp.length)
                {
                    tmp.find('.content').text(value.title);
                    tmp.find('input[name=feature-title]').val(value.title);
                    tmp.find('.feature-content').eq(index - 1).find('.des').html(value.content);
                    tmp.find('.feature-content').eq(index - 1).find('input[name=feature-content]').val(value.content);
                }
                else
                {
                    addRow();
                    tmp = $('.feature-list .feature-item').eq(i);
                    tmp.find('.content').text(value.title);
                    tmp.find('input[name=feature-title]').val(value.title);
                    tmp.find('.feature-content').eq(index - 1).find('.des').html(value.content);
                    tmp.find('.feature-content').eq(index - 1).find('input[name=feature-content]').val(value.content);
                }
            })
        }
        $('#popupFormCol').modal('hide');
        loadAction();
    }

    function updateCol(item, index)
    {
        var product = $('.list-products .product-item').eq(index -1 );
        product.find('.content').text(item.title);
        product.find('input[name=title_product]').val(item.title);
        product.find('input[name=link_product]').val(item.link);
        // product.find('input[name=id_product]').val(item.id);
        if(item.features)
        {
            item.features.forEach(function(value, i){
                var tmp = $('.feature-list .feature-item').eq(i);
                if(tmp.length)
                {
                    tmp.find('.content').text(value.title);
                    tmp.find('input[name=feature-title]').val(value.title);
                    tmp.find('.feature-content').eq(index - 1).find('.des').html(value.content);
                    tmp.find('.feature-content').eq(index - 1).find('input[name=feature-content]').val(value.content);
                }
                else
                {
                    addRow();
                    tmp = $('.feature-list .feature-item').eq(i);
                    tmp.find('.content').text(value.title);
                    tmp.find('input[name=feature-title]').val(value.title);
                    tmp.find('.feature-content').eq(index - 1).find('.des').html(value.content);
                    tmp.find('.feature-content').eq(index - 1).find('input[name=feature-content]').val(value.content);
                }
            })
        }
        $('#popupFormCol').modal('hide');
        loadAction();
    }

    function addRow()
    {
        var number_col = $('.list-products th').length;
        var feature = `<tr class="feature-item">`;
        for (let index = 0; index < number_col; index++) {
            if(index == 0)
            {
                feature += `<th scope="row" class="feature-title position-relative">
                                <div class="content p-0">
                                </div>
                                <input type="text" name="feature-title" class="form-control d-none">
                                <a class="remove-feature position-absolute" href="">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </th>`;
            }
            else
            {
                feature += `<td class="feature-content">
                    <div class="des">
                    </div>
                    <input type="hidden" name="feature-content">
                </td>`;
            }
        }

        feature += `</tr>`;
        $('.feature-list').append(feature);
        loadAction();
    }

    function loadStructure()
    {
        var products = [];
        $('.list-products .product-item').each(function(){
            index = $(this).index();
            product_tmp = {
                title : $(this).find('input[name=title_product]').val(),
                link : $(this).find('input[name=link_product]').val(),
                id : $(this).find('input[name=id_product]').val(),
            };

            var features = [];
            $('.feature-list .feature-item').each(function(){
                var feature_tmp = {
                    'title' : $(this).find('.feature-title input[name=feature-title]').val(),
                    'content' : $(this).find('.feature-content').eq(index -1).find('input[name=feature-content]').val(),
                };
                features.push(feature_tmp);
            });
            product_tmp.features = features;
            products.push(product_tmp);
        })

        $('#structure').val(JSON.stringify(products));
    }

</script>