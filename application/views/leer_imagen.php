<script src="<?php echo base_url('files/tesseract/dist/tesseract.dev.js'); ?>"></script>
<h3 style="font-size: 20px; float: left; width: 100%; margin-top: 65px">Seleccionar DNI</h3>
<script>

function progressUpdate(packet){
	var log = document.getElementById('log');

	if(packet.status == 'done'){
			var line = document.createElement('div');
			var pre = document.createElement('p');
			var res = packet.data.text.split(" ");
			var result = '';
			for(var i = 0; i < res.length; i++) {
			   // Trim the excess whitespace.
			   res[i] = res[i].replace(/^\s*/, "").replace(/\s*$/, "");
			   // Add additional code here, such as:
			   if(res[i].length > 8){
			   	if(/\d/.test(res[i])){
			   		result = result.concat(res[i]);
			   	}
			   }
			}
			pre.appendChild(document.createTextNode(result));
			line.innerHTML = '';
			line.appendChild(pre);

	}
	document.querySelector("#log").innerHTML = '';
	log.insertBefore(line, log.firstChild);
}

function recognizeFile(file){
	document.querySelector("#log").innerHTML = 'Leyendo imágen...';

	Tesseract.recognize(file, {
		lang: 'spa'
	})
	.then(function(data){
		console.log(data);
		progressUpdate({ status: 'done', data: data })
	})
}
</script>

<input type="file" onchange="recognizeFile(window.lastFile=this.files[0])">

<div id="log"></div>


<style>
#log > div {
    color: #313131;
    border-top: 1px solid #dadada;
    padding: 9px;
    display: flex;
}
#log > div:first-child {
    border: 0;
}


.status {
	min-width: 250px;
}

#log {
    border: 1px solid #dadada;
    padding: 10px;
    margin-top: 20px;
    min-height: 100px;
}

progress {
    display: block;
    width: 100%;
    transition: opacity 0.5s linear;
}
progress[value="1"] {
    opacity: 0.5;
}
</style>