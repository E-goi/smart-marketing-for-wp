<?php

function getLoader($id ,$on = true, $spacer=false){
    $class = $on==false ?' style="display: none;" ' :' ';
    $spacer = $spacer == true?'&nbsp;&nbsp;':'';
    return '<div id="'.$id.'" class="loader-egoi-self" role="status"'.$class.'>'.$spacer.'<i class="loading">'.__('Loading...','egoi-for-wp').'</i></div>';
}

?>
<style>
    #wpcontent {
        padding: 0;
        background-color: #f4f8fa;
        height: auto;
        min-height: calc( 100vh - 32px);
    }
    #wpwrap{
        height: auto;
        min-height: 100%;
    }
</style>
