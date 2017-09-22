<?php
$data = $_POST;

$url_str = $data['url'];
if(isset($data)){ ?>
	
	<a id="TB_closeWindowButton">X</a>
    <iframe src="http://<?php echo $url_str;?>" width="700" height="600" style="border: 0 none;" onload="window.parent.parent.scrollTo(0,0);"></iframe><?php

}