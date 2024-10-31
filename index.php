<?php
//if(isset($_POST['go']))
//{
//    update_cookies_date_end($_POST['apiKey']);
//}


/*
Plugin Name: SDP Cookies Press
Plugin URI: http://smartdataprotection.eu/store/cookies/wordpress
Description: El plugin más prorfesional para cumplir la "ley de cookies".
Version: 3.1.1
Author: Smart Data Protection
Author URI: http://smartdataprotection.eu
License: GPL2
*/
global $plugin_list;

//Set settings link
function sdp_cookies_settings_link($actions) {
    static $plugin;

    if (!isset($plugin))
        $plugin = plugin_basename(__FILE__);

    $settings = array('settings' => '<a href="'. esc_url( get_admin_url(null, '?page=sdpcookiespress%2Findex.php') ) .'">' . __('Settings', 'General') . '</a>');
    $site_link = array('support' => '<a href="https://smartdataprotection.eu/es/store/cookies?utm_source=wordpress&utm_medium=plugin&utm_campaign=sdpcookiespress_info" target="_blank">Support</a>');

    $actions = array_merge($site_link, $actions);
    $actions = array_merge($settings, $actions);

    return $actions;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'sdp_cookies_settings_link' );

//On active plugin
function get_sdp_cookie_defaults () {

    $options = $parameters = get_option('sdp_cookies_options');
    $parameters = array(
        'apiKey' => '',
        'dateEnd' => '',
        'mail' => '',
        'mode' => '',
        'license' => '',
        'notice' => 'Al utilizar nuestro sitio web, consiente nuestro uso de cookies de acuerdo con nuestra política de cookies.',
        'consentmodel' => '',
        'style' => '',
        'automatic_page' => '1',
        'noticeUrl' => '',
        'gacode' => '',
        'dateReg' => '',
        'nif' => '',
        'address' => '',
        'phone' => '',
        'name' => '',
//        'deactivemodules' => '',
    );

    update_option('sdp_cookies_options', $parameters);
}
register_activation_hook( __FILE__, 'get_sdp_cookie_defaults' );

//show notice when active
add_action('admin_notices', 'sdp_cookies_admin_notices');
function sdp_cookies_admin_notices() {
//    if (!get_option('sdp_cookies_notice_shown')){

    $parameters = get_option('sdp_cookies_options');

    if ($parameters['dateReg'] == "")
        $parameters['dateReg'] = date("Y/m/d");

    if ($parameters['apiKey'] == "") {
        echo "
            <div class='updated sdp_cookies_notice'>
                <img class='img_logo_cookiespro' src='".plugins_url('/img/sdp_logo_red_75.png', __FILE__)."' />
                <a href='".get_admin_url(null, 'admin.php?page=sdpcookiespress/index.php')."' class='sdp_link' >Configura SDPCookiesPress</a>
                <p>Estás muy cerca de cumplir con la <em>Ley de Cookies</em> y tener los textos legales, solo debes activar tu cuenta.</p>
            </div>
        ";
    }
    else if ($parameters['legal'] != 1) {
        echo "
            <div class='updated sdp_cookies_notice'>
                <img class='img_logo_cookiespro' src='".plugins_url('/img/sdp_logo_red_75.png', __FILE__)."' />
                <a href='".get_admin_url(null, 'admin.php?page=sdpcookiespress/index.php')."' class='sdp_link' >Activa tus textos legales</a>
                <p>Los textos de aviso legal y la política de privacidad no están configurados para adaptarse a la legislación vigente. Recuerda hacerlo para adaptar correctamente tu página a la Ley de Servicio de Seguridad de la Información.</p>
            </div>
        ";        
    }
//    else {
//        if (strtotime($parameters['dateReg']) < strtotime('-10 days')) {
//            echo "
//            <div class='updated sdp_cookies_notice notice notice-error is-dismissible'>
//                <img class='img_logo_cookiespro' src='".plugins_url('/img/sdp_logo_red_75.png', __FILE__)."' />
//                <p><strong>¡Consejo!</strong> Recuerda que también debes mostrar la política de privacidad y el aviso legal. Con nuestra versión PRO los tendrás también</p>
//                <a href='https://smartdataprotection.eu/es/wordpress/bb8828bd2f4383fb2a3318f7d6227ecb/?utm_source=sdpcookies&utm_medium=wordpress&utm_campaign=Upgrade%20Banner' target='_blank' class='sdp_link' >Actualízate por 3,95€/mes</a>
//            </div>
//        ";
//        }
//    }

}

//  this function adds the settings page to the Appearance tab
function sdp_cookies_add_page() {
//    add_menu_page('SDP Cookies', 'SDP Cookies', 'administrator', __FILE__, 'sdp_cookies',plugins_url('/img/cookies_icon.png', __FILE__));
    add_menu_page('SDP Cookies', 'SDP Cookies', 'administrator', __FILE__, 'sdp_cookies',plugins_url('/img/cookies_icon.png', __FILE__));
}
add_action('admin_menu', 'sdp_cookies_add_page');

function sdp_cookies (){
    $options = $parameters = get_option('sdp_cookies_options');

    if (! empty($_POST["custom_submit"]) ) {
        $parameters['apiKey'] = strip_tags(stripslashes($_POST["apiKey"]));
        $parameters['style'] = strip_tags(stripslashes($_POST["style"]));
        $parameters['email'] = strip_tags(stripslashes($_POST["mail"]));
    }
    if (! empty($_POST["contact_submit"]) ) {
        if (! empty($_POST["nif"]) ) {
            $parameters['legal'] = 1;
        }
    }

    if ( $options != $parameters ) {
        $options = $parameters;
        update_option('sdp_cookies_options', $options);
    }

    ?>

    <header>
        <img class='img_logo' src='<?php echo plugins_url('/img/sdp_logo_red_75.png', __FILE__); ?>' />
        <h3>SDP Cookies Press | La solución profesional para incluir el banner de cookies y los textos legales de forma <strong>gratuita</strong>.</h3>
    </header>

    <div id="SDPCookiesPress" class="sdp-content" style="width: 69%; margin-right: 1%; float: left;">

        <?php add_thickbox(); ?>

        <div id="loading-modal" style="display:none;">
            <h3>Procesando... Se paciente, podemos tardar un ratito!</h3>
            <p>Estamos analizando tu sitio web y generando los textos legales. Este proceso puede durar unos minutos. Por favor, sé paciente :)</p>
            <div class="loading" style="text-align: center; background: none;">
                <img alt="cookies xandrusoft" src="<?php echo plugins_url('/img/loading.gif', __FILE__); ?>">
            </div>
        </div>

        <div class="first_choice" style="display: none;">
            <p><button class="register">¡Genera los textos legales y banner de cookies ahora!</button></p>
        </div>

        <div class="loading" style="display: none">
            <p>Procesando datos...</p>
        </div>

        <br>

        <div class="register_new api_table first_step" style="display: none">
            <form method="post" id="sdpCookiesForm">
                <input type="hidden" name="type" value="cookies" size="" />
                <input type="hidden" name="domain" value="<?php echo $_SERVER['HTTP_HOST']; ?>" size="" />
                <table class="register_table" style="display: none;">
                    <tr><td><h3>Registro en la aplicación</h3></td></tr>
                    <tr valign="top">
                        <td><b>Introduce tu e-mail: <span class="req">*</span></b></td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" name="mail" value="<?php echo $options['email'] ?>" size="255" required="required" />
                            <br>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td><b>Selecciona el estilo del aviso: <span class="req">*</span> </b></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="style">
                                <option value="STYLE_1" disabled>Color y fuente personalizado (Sólo para usuarios PRO)</option>
                                <option value="STYLE_1">Estilo 1</option>
                                <option value="STYLE_2">Estilo 2 </option>
                            </select>

                            <a href="#" class="showStyles">--> Ver estilos <--</a>
                            <a href="#" class="hideStyles" style="display: none;">Ocultar estilos</a>

                            <div id="showStyles" style="display: none;">
                                <figure class="cookies_style">
                                    <p><strong>El estilo personalizado permite customizar los coloes, fuentes y posición del banner. Sólo para usuarios PRO</strong></p>
                                    <figcaption>Personalizado</figcaption>
                                </figure>
                                <figure class="cookies_style">
                                    <img alt="cookies xandrusoft" src="<?php echo plugins_url('/img/examples/cookie_notice_1.png', __FILE__); ?>">
                                    <figcaption>Estilo 1</figcaption>
                                </figure>
                                <figure class="cookies_style">
                                    <img alt="cookies xandrusoft" src="<?php echo plugins_url('/img/examples/cookie_notice_2.png', __FILE__); ?>">
                                    <figcaption>Estilo 2</figcaption>
                                </figure>
                            </div>
                            <br>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td><b>Tipo de consentimiento: (Ambos estan contemplados por la ley)<span class="req">*</span> </b></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="consentmodel">
                                <option value="0" disabled>Cuando el usuario navege por el sitio (Sólo para usuarios PRO)</option>
                                <option value="1">Cuando el usuario pulse ACEPTAR </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <br>
                            <input name="check" type="checkbox" required="required"> Acepto la <a href="http://smartdataprotection.eu/es/static/privacy/bb8828bd2f4383fb2a3318f7d6227ecb">Política de privacidad</a> el <a href="http://smartdataprotection.eu/es/static/lssi/bb8828bd2f4383fb2a3318f7d6227ecb">Aviso Legal</a> y <a href="http://smartdataprotection.eu/es/static/cookies/bb8828bd2f4383fb2a3318f7d6227ecb"> las Condiciones de contratación de SDP COOKIES</a> <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="apiKey" value="<?php echo $options['apiKey'] ?>" size="255"/>
                            <input type="hidden" name="custom_submit" value="true" />
<!--ok-->
                            <!--                            <p class="submit"><input alt="#TB_inline?width=600&height=550&inlineId=loading-modal" class="enviar_form thickbox" type="submit" value="Guardar"/></p>-->
                            <p class="submit"><input class="enviar_form" type="submit" value="Guardar"/></p>
                        </td>
                    </tr>
                </table>
                <p>Si tienes cualquier problema con el plugin puedes ponerte en contacto con nosotros en <a href="mailto:soporte@smartdataprotection.eu">soporte@smartdataprotection.eu</a> indicando en el asunto tu página web.</p>
            </form>
            <form method="post" id="sdpApiForm">
                <table class="reg_api" style="display: none;">
                    <tr><td><h3>Licencia</h3></td></tr>
                    <tr>
                        <td>Introduce aquí tu APIKey que se te ha facilitado la plataforma Smart Data Protection</td>
                    </tr>
                    <tr valign="top">
                        <td scope="row"><b>APIKey </b> <span class="req">*</span> </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="api" name="apiKey" value="<?php echo $options['apiKey'] ?>" size="255"/>
                            <input type="hidden" name="dateEnd" value="<?php echo $options['dateEnd'] ?>" size="255"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="custom_submit" value="true" />
                            <p class="submit"><input class="enviar_form" type="submit" value="Guardar"/></p>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="api_table second_step" style="display: none; text-align: center">
            <div>
                <h2>Último paso para que tu web se adapte a la normativa vigente... los Textos Legales</h2>
                <p>Hemos generado de forma totalmente automatizada los textos legales y banner de cookies, pero para que los textos estén adaptados a la normativa vigente, la ley exige mostrar los datos de contacto del propietario del sitio web. Simplemente, introduce los datos de contacto y nuestro sistema los incuirá de forma automática en los textos legales generados.</p>
                <div style="position: relative;overflow: hidden;">
                    <form method="post" id="sdpCookiesCompanyForm" style="width: 60%;float: left;">
                        <input type="hidden" name="legal" value="<?php echo $options['legal'] ?>" size="255"/>
                        <table class="register_table">
                            <tr><td><h3>Datos de contacto</h3></td></tr>
                            <tr>
                                <td>Introduce los datos de contacto del responsable de la página web para completar los textos legales</td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><b>NIF de la empresa / responsable </b> <span class="req">*</span> </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="nif" value="<?php echo $options['nif'] ?>" size="255"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><b>Nombre de la empresa / responsable </b> <span class="req">*</span> </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="name" value="<?php echo $options['name'] ?>" size="255"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><b>Email de contacto</b> <span class="req">*</span> </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="email" value="<?php echo $options['email'] ?>" size="255"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><b>Teléfono de contacto </b> <span class="req">*</span> </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="phone" value="<?php echo $options['phone'] ?>" size="255"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><b>Dirección de contacto </b> <span class="req">*</span> </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="address" value="<?php echo $options['address'] ?>" size="255"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" name="contact_submit" value="true" />
                                    <p class="submit"><input class="enviar_form" type="submit" value="Guardar"/></p>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <figure class="cookies_style" style="width: 40%; float: right;margin: 0; margin-top: 135px;">
                        <img alt="textos legales xandrusoft" src="<?php echo plugins_url('/img/textos_leles_sdp_example.jpeg', __FILE__); ?>" style="width: 100%;">
                        <figcaption>Ejemplo de atos de contacto incluidos en los textos legales</figcaption>
                    </figure>
                </div>
            </div>
        </div>
        <div class="api_table third_step" style="display: none; text-align: center">
            <div>
                <img src='<?php echo plugins_url('/img/smarty_congrats.png', __FILE__); ?>' alt="congrats" style="max-width: 140px; width: 70%;">
            </div>
            <div>
                <h2>¡Enhorabuena! Todos los textos de tu página web han sido creados con éxito.</h2>
                <p>También debes insertar los links de los textos legales (<code>&lt;a href="#" class="sdpCookiesAdviceLink">Aviso de cookies&lt;/a> | &lt;a href="#" class="sdpPrivacyPolicyLink">Política de privacidad&lt;/a> | &lt;a href="#" class="sdpLegalAdviceLink">Aviso legal&lt;/a></code>) en todas las páginas. Un buen sitio puede ser el footer o pie de página. </p>

            </div>
            <div>
                <p>Recuerda que los textos se han generado de forma totalmente automatizada por lo que no podemos garantizar que se encuentren adaptados a la totalidad de tu actividad. Si quieres que uno de nuestros profesionales asociados los verifique, puedes solicitarlo sin compromiso alguno y en la mayor brevedead nos pondremos en contacto.<br>
                    En ese caso o si tienes cualquier problema con el plugin puedes ponerte en contacto con nosotros en <a href="mailto:soporte@smartdataprotection.eu">soporte@smartdataprotection.eu</a> indicando en el asunto tu número de identificador: <?php echo $options['apiKey'] ?></p>
            </div>
        </div>

    </div>

    <aside class="sdp-aside" style="width: 30%; float: left;">
        <div class="sdp-support congrats_zone" style="margin-bottom: 40px; display: none;">
            <p>Te proporcionamos este plugin de forma gratuita pero necesitamos tu ayuda. Vótanos y comparte en Twitter para reconocer nuestro trabajo.</p>
            <div class="inside" style="padding-top: 10px">
                <div style="height: 24px;     padding: 10px 0; overflow: hidden; position: relative;">
                    <div style="float: left;margin-right: 10px;"><strong>Vota nuestro plugin</strong></div>
                    <div style="float: left;">
                        <a style="text-decoration: none" href="//wordpress.org/support/view/plugin-reviews/sdpcookiespress" target="_blank">
                            <img title="5 estrellas" src="<?php echo plugins_url('/img/star.png', __FILE__); ?>">
                            <img title="5 estrellas" src="<?php echo plugins_url('/img/star.png', __FILE__); ?>">
                            <img title="5 estrellas" src="<?php echo plugins_url('/img/star.png', __FILE__); ?>">
                            <img title="5 estrellas" src="<?php echo plugins_url('/img/star.png', __FILE__); ?>">
                            <img title="5 estrellas" src="<?php echo plugins_url('/img/star.png', __FILE__); ?>">
                        </a>
                    </div>
                </div>

                <div class="the_champ_clear"></div>

                <div style="height: 24px;">
                    <div style="float: left; width: 40px;">
                        <iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/follow_button.html?screen_name=smartdata_es" style="width:250px; height:20px;"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="sdp-support congrats_zone" style="margin-bottom: 40px; display: none;">
            <p>Nos encanta que te encante nuestro plugin. Si quieres, <strong>nos puedes ayudar con el mantenimiento</strong> de los servidores, web, emails, etc. Alguna sugerencias son:</p>
            <ul>
                <li>Paquete de mini cookies: 0.80€</li>
                <li>Paquete de cookies grande: 1.50€</li>
                <li>Paquete familiar galletas Príncipe: 5€</li>
                <li>Me he ahorrado un abogado: 50€</li>
            </ul>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="CJ3Y7W8EBPZ94">
                <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal, la forma rápida y segura de pagar en Internet.">
                <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
            </form>
            <p><strong>Muchas gracias!</strong></p>
        </div>

        <div class="sdp-about">
            <h3>Acerca de</h3>
            <div class="inside">
                <p><strong>SDP Cookies</strong> por <strong><a href="//smartdataprotection.eu" target="_blank">Smart Data Protection</a></strong></p>
                <p>Somos la empresa de seguridad digital global que te ofrece todas las herramientas para cumplir con la normativa legal para tu página web y empresa.</p>
                <div style="height:32px">
                    <a href="//twitter.com/smartdata_es" target="_blank" title="Twitter"><img style="box-shadow:1px 1px 10px 1px #888888" class="theChampLoginButton theChampTwitterButton"></a>
                </div>
            </div>
        </div>
    </aside>


    <?php
}

require('scripts.php');




function create_new_cookies_page() {

    //
//    if(is_user_admin()){
    $titulo = 'Aviso legal Cookies';
    $slug = 'aviso-de-cookies';
    $the_page = get_page_by_title( $titulo );

    if ( ! $the_page ) {

        // Se crea el objeto del post
        $_p = array();
        $_p['post_title'] = $titulo;
        $_p['post_content'] = file_get_contents( plugins_url( 'notice.html', __FILE__ ) );
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';

        // Se guarda en la base de datos
        $page_id = wp_insert_post( $_p );

    }
    else {
        // significa que el plugin ya estaba activado antes

        $page_id = $the_page->ID;

        //Por si no esta publicada, la publicamos
        $the_page->post_status = 'publish';
        $page_id = wp_update_post( $the_page );

    }
    delete_option( 'sdp_cookies_page_id' );
    add_option( 'sdp_cookies_page_id', $page_id );
//    }
}

function update_cookies_date_end ($api){
//    echo($api);

    $ch = curl_init();

    $data = array(
        'apiKey' => $api
    );

    $data_string = json_encode($data);

    // Setting options

    curl_setopt($ch, CURLOPT_URL,"https://smartdataprotection.eu/es/services/getDateend");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $sdp_response = curl_exec ($ch);

    // cerramos la sesión cURL
    curl_close ($ch);

//    echo $sdp_response;
    $obj = json_decode($sdp_response);
    $dateend = $obj->{'dateend'};

    //receive the dateend and update the wordpress variable
    $options = $parameters = get_option('sdp_cookies_options');
    $parameters['dateEnd'] = $dateend;
    if ( $options != $parameters ) {
        $options = $parameters;
        update_option('sdp_cookies_options', $options);
    }
}

function simple_cookies_mode (){
    $options = $parameters = get_option('sdp_cookies_options');

    if(isset($_POST['cacheMode']) && $_POST['cacheMode'] == 'Yes') {
        $parameters['mode'] = 1;
    } else {
        $parameters['mode'] = 0;
    }
    if ( $options != $parameters ) {
        $options = $parameters;
        update_option('sdp_cookies_options', $options);
    }
}

//function deactive_plugins()
//{
//    $parameters = get_option('sdp_cookies_options');

//    if(isset($_COOKIE["smartdataprotection_lssi"]))
//    {
//        foreach ($parameters['deactivemodules'] as $active) {
//        if ( is_plugin_inactive($parameters['deactivemodules']) ) {
//            activate_plugin($active);
//        }
//        }
//    }
//    else
//    {
//        if ( is_plugin_active($parameters['deactivemodules']) ) {
//            deactivate_plugins($parameters['deactivemodules']);
//        }
//    }
//    echo "deactive";
//}
//add_action('wp_head', 'deactive_plugins', 1);

//function wpmdbc_exclude_plugins( $plugins ) {
//    $parameters = get_option('sdp_cookies_options');
//    return $parameters['deactivemodules'];
//}
//add_filter( 'option_active_plugins', 'wpmdbc_exclude_plugins' );

require('view.php');

?>