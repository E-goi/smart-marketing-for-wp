<?php

function getLoader($id ,$on = true, $spacer=false){
    $class = $on==false ?' style="display: none;" ' :' ';
    $spacer = $spacer == true?'&nbsp;&nbsp;':'';
    return '<div id="'.$id.'" class="loader-egoi-self" role="status"'.$class.'>'.$spacer.'<i class="loading">'.__('Loading...','egoi-for-wp').'</i></div>';
}