$(document).ready(function(){
    /**
     * Acces Data
     */

   $("#category-id").change(function(){
       
        $(".catgory-response").html('<div class="loading"><img src="'+basepath+'pix/ajaxloader.GIF"><p>Loading ...</p></div></div>');
        $(".alert-alt-message").hide();        
        var categoryid = document.getElementById('category-id').value;
        var id = $('#category-dl option').filter(function() {
            return this.value === categoryid;
        }).data('id');
        $.ajax({url:URL+'COURSE_QUERY', data : { id : id} ,success: function(course){
                 $(".catgory-response").hide();
                if(course.status) {                   
                    $("#course-dl").empty();
                    for (var key in course.list) {
                      $("#course-dl").append('<option data-id="'+key+'">'+course.list[key]+'</option>');
                    } 
                } else {
                    $(".catgory-response").html('<div class="alert alert-warning alert-dismissable padr2x">'+course.message+'<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>&nbsp;</div>');
                    $(".catgory-response").show();
                }

         }});
    });
    $("#course-dl").load(URL+'COURSE_QUERY', function( data, status, xhr ) {
        var json = JSON.parse(data);
        if(json.status) {
            $("#course-dl").empty();
            for (var key in json.list) {
              $("#course-dl").append('<option data-id="'+key+'">'+json.list[key]+'</option>');
            }
        } else {
            $("#course-dl").append('<option>No course available</option>');
        }
        $("#activity").hide();
        $("#users").hide();
    });
   /* $("#course-id").change(function(){
        $("#users-dl").empty();
        var courseid = document.getElementById('course-id').value;
        var id = $('#course-dl option').filter(function() {
            return this.value === courseid;
        }).data('id');
        
       var activityid = document.getElementById('activity-id').value;
        var actid = $('#activity-dl option').filter(function() {
            return this.value === activityid;
        }).data('id');
        // Find activty
        $.ajax({url:URL+'ACTIVITY_QUERY', data : { id : id} ,success: function(json){

                if(json.status) {
                  $("#activity-dl").empty();
                  for (var key in json.list) {
                    $("#activity-dl").append('<option data-id="'+key+'">'+json.list[key]+'</option>');
                  }
                  $("#activity").show();
                } else {
                    $("#activity-dl").append('<option>'+json.message+'</option>');
                }
           }});
        // find course related to this category.
           $.ajax({url:URL+'USER_QUERY', data : { id : id} ,success: function(json){

                if(json.status) {
                  $("#users-dl").empty();
                  for (var key in json.list) {
                    $("#users-dl").append('<option data-id="'+key+'">'+json.list[key]+'</option>');
                  }
                } else {
                    $("#users-dl").append('<option>'+json.message+'</option>');
                }
           }});
           
    });*/
    
// Added by Shiuli.
  $("#course").hide();
  $("#category-id").on('input', function(){
    $("#course").show();
  });

    $("#course-id").on('input', function(){ // .change to .select by Shiuli.
        $("#users-dl").empty();
        var courseid = document.getElementById('course-id').value;
        var id = $('#course-dl option').filter(function() {
            return this.value === courseid;
        }).data('id');
        $(".course-response").html('<div class="loading"><img src="'+basepath+'pix/ajaxloader.GIF"><p>Loading ...</p></div></div>');
        $.ajax({url:URL+'ACTIVITY_QUERY', data : { id : id} ,success: function(json){
                $(".course-response").html('');
                if(json.status) {
                  $("#activity-dl").empty();
                  for (var key in json.list) {
                    $("#activity-dl").append('<option data-id="'+key+'">'+json.list[key]+'</option>');
                  }
                  $("#activity").show();
                } else {
                    $(".activity-response").html('<div class="alert alert-warning alert-dismissable padr2x">'+json.message+'<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>&nbsp;</div>');
                    $(".activity-response").show();
                }
           }});
           $.ajax({url:URL+'USER_QUERY', data : { id : id} ,success: function(json){

                if(json.status) {
                  $("#users-dl").empty();
                  for (var key in json.list) {
                    $("#users-dl").append('<option data-id="'+key+'">'+json.list[key]+'</option>');
                  }
                  $("#users").show();
                } else {
                    $(".users-response").html('<div class="alert alert-warning alert-dismissable padr2x">'+json.message+'<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>&nbsp;</div>');
                    $(".users-response").show();
                }
           }});
           
    });
    
    /* 
     * BUtton events
     */
    
    $("#course-btn").click(function(){
        var courseid = document.getElementById('course-id').value;
        var id = $('#course-dl option').filter(function() {
            return this.value === courseid;
        }).data('id');
        window.location.href = CourseLevelURL+'id='+id;
    });
    
    $("#user-btn").click(function(){
        
        var activityid = document.getElementById('activity-id').value;
        var actid = $('#activity-dl option').filter(function() {
            return this.value === activityid;
        }).data('id');
        
        var userid = document.getElementById('user-id').value;
        var uid = $('#users-dl option').filter(function() {
            return this.value === userid;
        }).data('id');
        
        var courseid = document.getElementById('course-id').value;
        var cid = $('#course-dl option').filter(function() {
            return this.value === courseid;
        }).data('id');
        
        
        window.location.href = UserlevelURL+'userid='+uid+'&courseid='+cid;
    });
    
    $("#activity-btn").click(function(){    
        
        var courseid = document.getElementById('course-id').value;
        var cid = $('#course-dl option').filter(function() {
            return this.value === courseid;
        }).data('id');
        
        var activityid = document.getElementById('activity-id').value;
        var actid = $('#activity-dl option').filter(function() {
            return this.value === activityid;
        }).data('id');
                
        window.location.href = ActivitylevelURL+'id='+cid+'&activity='+activityid;
    });
    
});
