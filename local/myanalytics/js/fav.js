$(document).ready(function(){
    $("#fav-check").click(function() {
            var $input = $( this );
            var link = $input.val();
        if($(this).prop("checked")){
             var label = prompt("Enter favourite name", "");
            if (label == null) {
               return false;
            }

            $.post(fav.addUrl, {level:fav.level, label: label, link: link }).done(function( data ) {
                if(data) {
                    alert('Favourite successfully added');
                } else {
                     alert('Something went problem!!, Contact to site Administrator');
                }
            });
        } else {    
          $.post(fav.deleteUrl, {link: link }).done(function( response ) {
                if(response.status) {
                    alert('Favourite successfully removed');
                } else {
                     alert('Something went problem!!, Contact to site Administrator');
                }
            });
        }
    });
});