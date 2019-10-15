function alertMsg(type, content) {
    if($('#alertMsg').length != 0) {
        return false;
    }
    if (type == 'error') {
        type = 'danger';
    }
    var html = '<div id="alertMsg" style="display: none" class="alert alert-'+type+' alert-dismissible">' +
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + content + '</div>';
    $('body').append(html);
    $('#alertMsg').slideDown();
    setTimeout(function(){
        $('#alertMsg').slideUp();
        setTimeout(function(){
            $('#alertMsg').remove();
        }, 1000);
    }, 2000);
}