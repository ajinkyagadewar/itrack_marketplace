 // Ajax course & enroll
 $(document).ready(function(){
    $("select#id_category").change(function () {
       $("#fitem_id_category .fselect").append('<div class="loader"><img src="pix/loader.gif"/> loading...</div>');
       $.getJSON("ajax_calls.php", {id: $(this).val(), ajax: 'true', action: 'COURSE_LIST'}, function (data) {
           $(".loader").remove();
           var options = '<option>-- select --</option>';
           for (var key in data) {
               options += '<option value="' + key + '">' + data[key] + '</option>';
           }
           $("#id_courses").html(options);
       })
    });
    $("select#id_courses").change(function () {
       $.getJSON("ajax_calls.php", {id: $(this).val(), ajax: 'true', action: 'ENROLL_LIST'}, function (data) {
           var options = '';
           for (var key in data) {
               options += '<option value="' + key + '">' + data[key] + '</option>';
           }
           $("select#id_enrol").html(options);
       });
    });
});