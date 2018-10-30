$(document).ready(function(){
    $("#search-query").on("input",function(){
        var option = $('input:radio[name=option]:checked').val();
        var self = $("#self").val();
        var query = $("#search-query").val();
        $(".error-div").html('');
        if(query == '') {
            $(".error-div").html('<label class="control-label" for="inputError">Please type search name</label>');
            return false;
        }           
        $(".search-response").html('<img src="'+imagepath+'"><p>Loading ...</p>');
        $.getJSON( searchUrl, { option: option,self :self, search: query } )
         .done(function( data ) { 
             $(".search-response").html('');
             var items = [];
             if(data.status) {
                 $("#results ul").html('');
                 $.each( data.list, function( key, val ) {
                     items.push( "<li class='divider'><input type='radio' name='id' value='"+val.id+"'> <span>" + val.firstname+' '+val.lastname+ "</span></li>" );
                 });
                 $( "<ul/>", {
                     "class": "user-lists",
                     html: items.join( "" )
                 }).appendTo( "#results" );
                 $("#button-div").removeClass('hide');
             } else {
                  $("#button-div").addClass('hide');
                  $("#results").html('');
                  $(".search-response").html('<div class="alert alert-warning padr2x" style="padding: 8px 16px;">'+data.message+'<button aria-hidden="true" data-dismiss="alert" class="close" style="right: -6px;" type="button">×</button>&nbsp;</div>');
             }
         })
         .fail(function( jqxhr, textStatus, error ) {
             var err = textStatus + ", " + error;
             console.log( "Request Failed: " + err );
         });
    });
     $("#generate-report").on("click",function(){
        var id = $('input:radio[name=id]:checked').val();
        $(".error-div").html('');
        if (typeof id == 'undefined') {
            $(".error-div").html('<label class="control-label redmark" for="inputError">Please select an user</label>');
            return true;
        } else {
            window.location.href = destinationUrl+id;
        }
     });       
});

