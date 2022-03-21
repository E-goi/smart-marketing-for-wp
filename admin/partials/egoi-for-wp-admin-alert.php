<?php

require_once dirname( __DIR__ ) . '/index.php';
out( $arr );
$id    = getLis();
$apend = '';
if ( $id == 0 ) {
	$apend = '
    document.getElementById("remove_alert").disabled = true;
    document.getElementById("remove_alert").setAttribute("title", "' . __( 'Available for paid plans' ) . '");
    document.getElementById("remove_alert").style.cursor = "not-allowed";';
}
$alert = '';

if ( array_filter( $arr ) ) {
	$alert = '
    <style>
    .alert-contract {
        background-color:#FFE560;
        overflow: auto;
        display: -webkit-box;
    }
    .alert-contract .sub-subtitle {
        margin-bottom: 19px;
        margin-top: 2px;
        padding-left: 12px;
        text-align: left;
    }
    .alert-contract .subtitle {
        color: #00313d; 
        font-size: 1rem;
        font-weight: 500;
        line-height: 26px;
        margin-bottom: 0;
        margin-top: 19px;
        padding-left: 12px;
        text-align: left;
    }
    .toggle-content {
        display: none;
        height: 0;
        opacity: 0;
        overflow: hidden;
        transition: height 350ms ease-in-out, opacity 750ms ease-in-out;
    }
    
    .toggle-content.is-visible {
        display: -webkit-box;
        height: auto;
        opacity: 1;
    }
    </style>
    <div class="alert-contract toggle-content is-visible" id="remove_alert_div">

        <div id="check_free" class="subtitle">
            <input type="checkbox" id="remove_alert" name="remove_alert" onClick="checkUser()">
            <div class="loading loading-lg" style="padding: 10px;display: none;" id="remove_alert_loading"></div>
        </div>

        <div id="text" style="width:80%">
            <p class="subtitle">' . __( 'Remove Email Marketing by E-goi', 'egoi-for-wp' ) . '</p>
            <p class="sub-subtitle">' . __( 'Available only with', 'egoi-for-wp' ) . ' <a href="https://www.e-goi.com/pricing/" target="_blank">' . __( 'Egoi paid plan »', 'egoi-for-wp' ) . '</a></p>
        </div>

    </div>
    <script>' . $apend . '
        function checkUser(){
            document.getElementById("remove_alert").style.display = "none";
            document.getElementById("remove_alert_loading").style.display = "-webkit-box";
            

            jQuery.post(
                smsnf_dashboard_ajax_object.ajax_url,
                {
                    action: "smsnf_kill_alert"
                },
                function(response) {
                    if(response.data == "1"){
                        document.getElementById("remove_alert").checked = true;
                        setTimeout(function(){ hide(document.getElementById("remove_alert_div")); },1000);
                    }
                    document.getElementById("remove_alert").disabled = true;
                    document.getElementById("remove_alert").style.display = "-webkit-inline-box";
                    document.getElementById("remove_alert_loading").style.display = "none";

                }
            );


            
        }

        var hide = function (elem) {

            elem.style.height = elem.scrollHeight + \'px\';
        
            window.setTimeout(function () {
                elem.style.height = \'0\';
            }, 1);
        
            window.setTimeout(function () {
                elem.classList.remove(\'is-visible\');
            }, 350);
        
        };
    </script>
    ';
}

return $alert;


