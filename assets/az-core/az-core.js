function show_modal(id){
    jQuery('.az-modal-'+id).modal({
        backdrop: 'static',
        keyboard: true
    });
}
function show_loading(){
    jQuery(".az-loading").show();
}

function hide_loading(){
    jQuery(".az-loading").hide();
}

function clear(){
    jQuery(".az-modal form input").not(".x-hidden").val("");
    jQuery(".az-modal form select").each(function(index, value) {
        if (jQuery(this).hasClass("select2-ajax")) {
            jQuery(this).val("").trigger("change");
        }
        else {
            jQuery(this).val(jQuery(this).find("option:first").val()).trigger("change");
        }
    });

    jQuery(".az-modal form textarea").val("");
    var t_ckeditor = jQuery(".az-modal form .ckeditor");
    jQuery(t_ckeditor).each(function(){
        var id_ckeditor = jQuery(this).attr('id');
        CKEDITOR.instances[id_ckeditor].setData('');                
    });
    var filter_table = jQuery(".filter-tabel select");
    jQuery(filter_table).each(function(){
        var fil = jQuery(this).attr("fil");
        jQuery("#"+fil).val(jQuery("#f"+fil).val());
    });

    jQuery('#l_product_name').text('-');
}

function edit (url, id, form, table_id, callback){
    show_loading();
    clear();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: id,
        },
        success: function (response) {
            var f_input = jQuery('#'+form+' input');
            var arr_ajax = [];
            jQuery.each(response[0], function(index, valu){
                jQuery('#'+index).val(valu).trigger("change");
                if (jQuery('#'+index).hasClass("format-number")) {
                    jQuery('#'+index).val(thousand_separator(jQuery('#'+index).val()));
                }

                var ajax_ = index;

                if (ajax_.indexOf("ajax_") >= 0) {
                    arr_ajax.push(ajax_);
                }
            });

            if (arr_ajax.length > 0) {
                jQuery.each(arr_ajax, function(index_arr, value_arr) {
                    var idajax = value_arr.replace("ajax_", "");
                    if (response[0][value_arr] != null) {
                        jQuery("#"+idajax+".select2-ajax").append(new Option(response[0][value_arr], response[0][idajax], true, true)).trigger('change');
                    }
                });
            }
            

            var t_area = jQuery("#"+form+' .ckeditor');
            jQuery(t_area).each(function (){
                var id_ckeditor = jQuery(this).attr('id');
                CKEDITOR.instances[id_ckeditor].setData(response[0][id_ckeditor]);
            });
            hide_loading();
            callback(response);
        },
        error: function (response) {
         hide_loading();
        },
        dataType: "json"
    });

    jQuery(".modal-title span").text("Edit");
    show_modal(table_id);
};

function save(url, form, vtable, callback, data){
    show_loading();
    var formdata = new FormData();
   
    var txt_ckeditor = jQuery(form+' .ckeditor');
    jQuery(txt_ckeditor).each(function(){
        var id_ckeditor = jQuery(this).attr("id");
        CKEDITOR.instances[id_ckeditor].updateElement();            
    });
    $.each(jQuery('#'+form).serializeArray(), function (a, b) {
        formdata.append(b.name, b.value);
    }); 

    if (!data) {
        data = [];
    }

    jQuery.each(data, function (ke, va) {
        formdata.append(ke, jQuery(va).val());
    });


    $.ajax({
        url: url,
        data: formdata,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: "json",
        success: function (response) {
            hide_loading();
            if (response.sMessage != "") {
                var err_response = response.sMessage;
                err_response = err_response.replace(/\n/g, "<br>");
                bootbox.alert({
                    title: 'Error',
                    message: err_response
                });
            }
            else {
                bootbox.alert({
                    title: 'Sukses',
                    message: "Simpan data berhasil"
                });

                jQuery(".az-modal").modal("hide");
                var dtable = jQuery('#'+vtable).dataTable({bRetrieve: true});
                dtable.fnDraw();
                callback(response);
            }
        },
        error: function (response) {
            console.log(response);
            hide_loading();
        }
    });
}

function remove(url, id, vtable, callback){
    bootbox.confirm({
        title: 'Hapus Data',
        message: "Apakah anda yakin ingin menghapusnya?",
        callback : function(result) {
            if (result == true) {
                $.ajax({
                    url: url,
                    type: "post",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function (response) {
                        if (response.err_code > 0) {
                            bootbox.alert({
                                title: "Error",
                                message: response.err_message
                            });
                        }
                        else {
                            var dtable = jQuery('#'+vtable).dataTable({bRetrieve: true});
                            dtable.fnDraw();
                            callback(response);
                        }
                    },
                    error: function (er) {
                        bootbox.alert({
                            title: "Error",
                            message: "Hapus data gagal "+er
                        });
                    }
                });
            }
        }
    });
}

function thousand_separator(x){
    if(typeof x !== 'undefined') {
        return x.toString().replace(/\./g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
}

function remove_separator(x){
    if(typeof x !== 'undefined') {
        return x.toString().replace(/\./g, '');
    }
}

jQuery(document).ready(function(){
    jQuery("select.select").select2(); 
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    
    jQuery("body").append(jQuery(".az-modal"));

    jQuery('.az-modal').on('shown.bs.modal', function () {
        jQuery('input:text:visible:first', this).not('.x-hidden').focus();
    });  

    jQuery(document).on('show.bs.modal', '.modal', function () {
        var zIndex = 1040 + (10 * jQuery('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            jQuery('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    jQuery(document).on('hidden.bs.modal', '.modal', function () {
        jQuery('.modal:visible').length && jQuery(document.body).addClass('modal-open');
    });

    jQuery("body").on("change", ".filter-table select", function(){
        var table_id = jQuery(".filter-tabel").attr("tid");
        var dtable = jQuery('#'+table_id).dataTable({bRetrieve: true});
        dtable.fnDraw();
    });

    jQuery('.az-form').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });

    jQuery(".format-number").on('keyup keydown', function(e){
        jQuery(this).val(thousand_separator(jQuery(this).val()));
    });

    jQuery(".format-number").keydown(function(e){
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    jQuery(document).on( 'click', '.az-table tbody tr td', function (event) {
        var btn = jQuery(this).find('button');
        if (btn.length == 0) {
            jQuery(this).parents('tr').toggleClass('selected');
        }
    });
});