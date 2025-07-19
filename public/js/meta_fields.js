$(document).ready(function() {

    $('#generate_slug').on('click', function() {
    var name =$('#name').val();
    var slug = name.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-');
    $('#slug').val(slug);
    $('.prod_name').html(name);
    });

    if (document.body.contains(document.getElementById('meta_title'))) {
        $('#meta_title').keyup(function() {
            var max = 70;
            var len = $(this).val().length;
            var char = max - len;
            $('#mtitle').text(char);
            //if (len >= max) {
            //    $('#mtitle').text(' you have reached the limit');
            //} else {
            //    debugger
            //    var char = max - len;
            //    $('#mtitle').text(char + ' characters left');
            //}updateLinkRewrite
        });
        $('#meta_description').keyup(function() {
            var max = 160;
            var len = $(this).val().length;
            var char = max - len;
            $('#mDes').text(char);
        });
        $(document).ready(function() {
            $('#Body_Selectall').prop('checked', false);
            $('#Body_Unselectall').prop('checked', false);
            var max = 70;
            var len = $('#meta_title').val().length;
            var char = max - len;
            $('#mtitle').text(char);

            var max1 = 160;
            var len1 = $('#meta_description').val().length;
            var char1 = max1 - len1;
            $('#mDes').text(char1);
        });

        function DisableButton(id) {

            document.getElementbyId(id).disabled = true;
        }
    } else {
        //alert('Element does not exist!');
    }
        $('#meta_title').keyup(function() {
            var max = 70;
            var len = $(this).val().length;
            var char = max - len;
            $('#mtitle').text(char);
            //if (len >= max) {
            //    $('#mtitle').text(' you have reached the limit');
            //} else {
            //    debugger
            //    var char = max - len;
            //    $('#mtitle').text(char + ' characters left');
            //}updateLinkRewrite
        });
        $('#meta_description').keyup(function() {
            var max = 160;
            var len = $(this).val().length;
            var char = max - len;
            $('#mDes').text(char);
        });
        $('#Body_Selectall').prop('checked', false);
        $('#Body_Unselectall').prop('checked', false);
        var max = 70;
        var len = $('#meta_title').val().length;
        var char = max - len;
        $('#mtitle').text(char);

        var max1 = 160;
        var len1 = $('#meta_description').val().length;
        var char1 = max1 - len1;
        $('#mDes').text(char1);
});
