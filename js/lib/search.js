function action(text) {
	text = text.trim();
	if (text.length > 0) {
		var resultados = document.getElementById("resultados");
		var ajax = Ajax();
		var url = "public/Modules/Repairs/search.php?text=" + text;
		ajax.open("POST", url, true);
		ajax.send(null);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				resultados.innerHTML = ajax.responseText;
				resultados.className = "visible";
			}
		};
	}
}