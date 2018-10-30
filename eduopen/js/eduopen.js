/*!
 * Start Bootstrap - Freelancer Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */



function filter_institution(countrydata){
$("#filterdiv").load("filterdata.php",{countryname:countrydata});
$("#instdetails").hide();
}


$(document).ready(function() {
      
        //Vertical Tab
        $('#parentVerticalnewTab').easyResponsiveTabs({
            type: 'vertical', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            closed: 'accordion', // Start closed if in accordion view
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function(event) { // Callback function if tab is switched
                var $tab = $(this);
                var $info = $('#nested-tabInfo2');
                var $name = $('span', $info);
                $name.text($tab.text());
                $info.show();
            }
        });

		/* $("#Certificate").hide();
       $("#Courses").hide();
      $("#Instructors").hide();
     $("#FAQs").hide();
    $("#fdin").click(function(){
        $("#Certificate").fadeToggle();
     });
    $("#fdclick").click(function(){
        $("#Courses").fadeToggle("slow");
});
$("#fdiout").click(function(){
        $("#Instructors").fadeToggle(2000);
});
$("#fdbyh").click(function(){
		$("#FAQs").fadeToggle(3000);
 });   */
  

    });
	
//code updated by nihar
$( "img[class='userpicture']" ).addClass( "img-circle" );
