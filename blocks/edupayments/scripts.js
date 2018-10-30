var tmpamount = 0;
function get_promotions (divid, courseid, itemname) {
    document.getElementById(divid).innerHTML = '<div  class="alert alert-warning" style="padding:12px;"><a href="#" style="top:-10px;right:-7px;" class="close" data-dismiss="alert" aria-label="close">&times;</a><div id="input-group-'+itemname+'" class="input-group"><input type="hidden" autocomplete="off" name="courseid" value="'+courseid+'"/><input autocomplete="off" type="text" id="input-'+itemname+'" placeholder="Enter ediscount coupon code" class="form-control"> <span class="input-group-btn "> <button type="button" style ="padding: 7px 11px;" value="'+itemname+'" onclick="check_promotions(this.value, '+divid+')" class="btn btn-primary input-group-sm"><i class="icon icon-search"></i></button></span></div><div style="margin-top:5px" id="'+itemname+'-response"></div><p id="'+itemname+'showmsgform"></p></div>';
    return false;
}

function check_promotions(itemname){
    var promocode = document.getElementById('input-'+itemname).value;
    var courseid =  document.querySelector('[name="courseid"]').value;
    var currencycode =  document.querySelector('[name="currency_code"]').value;
    var content = '';
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var json = JSON.parse(xhttp.responseText);
             if(json.status) {
                tmpamount = json.discountamount;
                if(json.discountamount === 0) {
                    document.getElementById('form'+itemname).action = promoconfig.autoEnrolUrl;
                    document.getElementById('form'+itemname).setAttribute('onsubmit','return showMessage("'+itemname+'","'+json.freemessage+'")');
                }
                document.getElementById(itemname+'amount').innerHTML = '<en style="text-decoration:line-through">'+currencycode+' '+json.actualamount+'</en>'+' <en class="discount-amount"> Disount '+currencycode+' '+json.discountamount+'</en>';
                document.getElementById(itemname).value = json.discountamount;
                content += '<div class="alert alert-success">'+json.message+'</div>';
                document.getElementById('input-'+itemname).disabled = true;
                var custom = document.getElementById('custom'+itemname).value;
                var strarray = custom.split('|', 3);
                    strarray.push(promocode);
                document.getElementById('custom'+itemname).value = '';
                document.getElementById('custom'+itemname).value = strarray.join('|');                   
            } else {
                content += '<div class="alert alert-error">'+json.message+'</div>';
            }
            document.getElementById(itemname+'-response').innerHTML = content;
            document.getElementById('input-'+itemname).value  = '';
         }
    }
    xhttp.open("POST", promoconfig.url+'?action=check_promotions&courseid='+courseid+'&itemname='+itemname+'&promocode='+promocode, true);
    xhttp.send();
    //Do not follow link
    return false;
}

function showMessage(itemname, message) {
    document.getElementById(itemname+'showmsgform').setAttribute('class','alert alert-error mr5px');
    document.getElementById(itemname+'showmsgform').innerHTML = message;
    var discount = document.getElementById(itemname).value;
    console.log(tmpamount);
    if(discount != tmpamount) {
        alert('Soory can\'t modify amount');
        return false;
    }
    return true;
}