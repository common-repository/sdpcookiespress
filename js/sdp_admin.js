var APIkey = false;
jQuery(document).ready(function () {
    if (jQuery('input[name="apiKey"]').val() == "") {
        jQuery(".register_new").show();
        jQuery(".register_table").show();
    } else{
        jQuery(".register_new").hide();
        jQuery(".congrats_zone").show();
        jQuery(".dateend").show();

        if (jQuery('input[name="legal"]').val() == "") {
            jQuery(".second_step").show();
        } else {
            jQuery(".third_step").show();
        }
    }
    jQuery('#pricingLink').on("click",function(event){
        event.preventDefault();
        jQuery("#pricingTable").toggle();
    });
    jQuery(".register").on("click",function(event) {
        jQuery(".reg_api").hide();
        jQuery(".first_choice").hide();
        jQuery(".register_new").show();
        jQuery(".register_table").show();
    });
    jQuery(".api").on("click",function(event) {
        jQuery(".register_new").hide();
        jQuery(".register_table").hide();
        jQuery('input[name="mail"]').val("");
        jQuery(".register_new").show();
        jQuery(".reg_api").show();
    });
    jQuery(".showStyles").on("click",function(event){
        event.preventDefault();
        jQuery(".showStyles").hide();
        jQuery(".hideStyles").show();
        jQuery("#showStyles").show("slow");
    });
    jQuery(".hideStyles").on("click",function(event){
        event.preventDefault();
        jQuery(".showStyles").show();
        jQuery(".hideStyles").hide();
        jQuery("#showStyles").hide("slow");
    });
    jQuery('#pricingLink').on("click",function(event){
        event.preventDefault();
        jQuery("#pricing").toggle();
    });
    jQuery('form#sdpCookiesForm').submit(function(event) {
        event.preventDefault();
        // tb_show('', 'TB_inline?width=600&height=450&inlineId=loading-modal');
        // alert('ok');

        jQuery(".first_choice").hide();
        jQuery(".register_new").hide();
        jQuery(".loading").show();
        //Send data to the server
        var dataj = {
            email: jQuery('input[name="mail"]').val(),
            domain: jQuery('input[name="domain"]').val(),
        };

        var url = "https://v2.smartdataprotection.eu/api/v2/legaltexts";
        jQuery.ajax({
            async: false,
            type: 'POST',
            url: url,
            jsonp: "response",
            dataType: 'json',
            data: dataj,
            beforeSend: function( xhr ) {
                jQuery('#loading-modal').show();
                // tb_show('', 'TB_inline?width=600&height=450&inlineId=loading-modal');
                alert('Procedemos a analizando tu sitio web y generar los textos legales. Este proceso puede durar unos minutos y la pantalla se puede quedar bloqueada mientras tanto. Por favor, sé paciente :)');
            }
        }).done(function (apiresponse) {
            var results = apiresponse;
            jQuery('input[name="apiKey"]').val(results);
            jQuery('form#sdpCookiesForm')[0].submit();
        }).fail(function (xhr, ajaxOptions, thrownError) {
            console.log("Error registering user: " + xhr.responseText + thrownError);
            jQuery(".register_new").show();
            jQuery(".loading").hide();
            alert("Ha ocurrido un error en el registro, vuelve a intentarlo o contacta con nuestro equipo para solucionarlo lo antes posible. Disculpa las molestias.")
        });
        tb_remove();
    });
    jQuery('form#sdpCookiesCompanyForm').submit(function(event) {
        event.preventDefault();
        if (jQuery('input[name="mail"]').val() == "") {
            alert('Ha ocurrido un error. Por favor, ponte en contacto con nosotros indicando el siguiente número en el asunto: ' + jQuery('input[name="apiKey"]').val());
        }
        // tb_show('', 'TB_inline?width=600&height=450&inlineId=loading-modal');
        // alert('ok');

        jQuery(".first_choice").hide();
        jQuery(".register_new").hide();
        jQuery(".loading").show();
        //Send data to the server
        var dataj = {
            domain: jQuery('input[name="domain"]').val(),
            nif: jQuery('input[name="nif"]').val(),
            email: jQuery('input[name="email"]').val(),
            name: jQuery('input[name="name"]').val(),
            phone: jQuery('input[name="phone"]').val(),
            address: jQuery('input[name="address"]').val(),
        };

        var url = "https://v2.smartdataprotection.eu/api/v2/companies";
        jQuery.ajax({
            async: false,
            type: 'POST',
            url: url,
            jsonp: "response",
            dataType: 'json',
            data: dataj,
            beforeSend: function( xhr ) {
                jQuery('#loading-modal').show();
                // tb_show('', 'TB_inline?width=600&height=450&inlineId=loading-modal');
                alert('Procedemos a enviar los datos...');
            }
        }).done(function (apiresponse) {
            if (apiresponse.nif && apiresponse.nif != "") {
                jQuery('input[name="legal"]').val('1');
                jQuery('form#sdpCookiesCompanyForm')[0].submit();
            }
        }).fail(function (xhr, ajaxOptions, thrownError) {
            console.log("Error registering user: " + xhr.responseText + thrownError);
            jQuery(".register_new").show();
            jQuery(".loading").hide();
            alert("Ha ocurrido un error en el registro de los datos, vuelve a intentarlo o contacta con nuestro equipo para solucionarlo lo antes posible. Disculpa las molestias.")
        });
        tb_remove();
    });
});