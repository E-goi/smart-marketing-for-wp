
	function tabs(id, idlink1, idlink2, element, rme, rme2, isForm){

		document.getElementById(element).style.display = 'block';
		document.getElementById(element).className += ' tab-active';
		document.getElementById(id).className += ' nav-tab-active';


		if(isForm){
			document.getElementById('tab-forms').style.display = 'none';
		}else{
			document.getElementById('egoi-bar-preview').style.display = 'none';
		}

		document.getElementById(rme).style.display = 'none';
		document.getElementById(rme).className = 'tab';
		document.getElementById(idlink1).className = 'nav-tab '+idlink1;

		document.getElementById(rme2).style.display = 'none';
		document.getElementById(rme2).className = 'tab';
		document.getElementById(idlink2).className = 'nav-tab '+idlink2;

		if(isForm){
			document.getElementById('nav-tab-forms').className = 'nav-tab nav-tab-forms';
		}else{	
			document.getElementById('nav-tab-preview').className = 'nav-tab nav-tab-preview';
		}

	}

	function preview_bar(){

		document.getElementById('egoi-bar-preview').style.display = 'block';

		document.getElementById('tab-settings').style.display = 'none';
		document.getElementById('tab-appearance').style.display = 'none';
		document.getElementById('tab-messages').style.display = 'none';

		document.getElementById('nav-tab-settings').className = 'nav-tab nav-tab-settings';
		document.getElementById('nav-tab-appearance').className = 'nav-tab nav-tab-appearance';
		document.getElementById('nav-tab-messages').className = 'nav-tab nav-tab-messages';
		
		document.getElementById('nav-tab-preview').className += ' nav-tab-active';
	}

	function show_forms(){

		document.getElementById('tab-forms').style.display = 'block';

		document.getElementById('tab-main-bar').style.display = 'none';
		document.getElementById('tab-widget').style.display = 'none';

		document.getElementById('nav-tab-main-bar').className = 'nav-tab nav-tab-main-bar';
		document.getElementById('nav-tab-widget').className = 'nav-tab nav-tab-widget';
		
		document.getElementById('nav-tab-forms').className += ' nav-tab-active';
	}

	function show_bar(){

		document.getElementById('tab-main-bar').style.display = 'block';

		document.getElementById('tab-forms').style.display = 'none';
		document.getElementById('tab-widget').style.display = 'none';

		document.getElementById('nav-tab-forms').className = 'nav-tab nav-tab-main-bar';
		document.getElementById('nav-tab-widget').className = 'nav-tab nav-tab-widget';
		
		document.getElementById('nav-tab-main-bar').className += ' nav-tab-active';
	}

	function show_widget(){

		document.getElementById('tab-widget').style.display = 'block';

		document.getElementById('tab-main-bar').style.display = 'none';
		document.getElementById('tab-forms').style.display = 'none';

		document.getElementById('nav-tab-main-bar').className = 'nav-tab nav-tab-main-bar';
		document.getElementById('nav-tab-forms').className = 'nav-tab nav-tab-widget';
		
		document.getElementById('nav-tab-widget').className += ' nav-tab-active';
	}

	function show_options() {
		document.getElementById('tab-forms-options').style.display = 'block';

		document.getElementById('tab-forms-appearance').style.display = 'none';

		document.getElementById('nav-tab-forms-appearance').className = 'nav-tab-forms-options';
		
		document.getElementById('nav-tab-forms-options').className += ' nav-tab-active';
	}

	function show_appearance() {
		document.getElementById('tab-forms-appearance').style.display = 'block';

		document.getElementById('tab-forms-options').style.display = 'none';

		document.getElementById('nav-tab-forms-options').className = 'nav-tab-forms-options';
		
		document.getElementById('nav-tab-forms-appearance').className += ' nav-tab-active';
	}