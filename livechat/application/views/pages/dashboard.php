<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
</head>
<body>
<script type="text/javascript">
	var last_date_time=0;
	function poll () {
	  var xhr = new XMLHttpRequest();
	  xhr.open("GET", "https://crm.ontimegroup.com/livechat/api/v1/conversation/paused?timestamp="+last_date_time);
	  console.log('https://crm.ontimegroup.com/livechat/api/v1/conversation/paused?timestamp='+last_date_time);
	  xhr.timeout = 30000;
	  xhr.ontimeout = poll;
	  xhr.onload = function () {
	  	//console.log(xhr.responseText);
		var json_obj = JSON.parse(xhr.responseText);
		console.log(json_obj.length);
		for (var i = 0; i < json_obj.length; i++){
		    var obj = json_obj[i];
		    for (var key in obj){
		        var attrName = key;
		        var attrValue = obj[key];

		        if(key==='last_updated_at')
		        {
		        	last_date_time = attrValue;
		        }
		    }
		}
	  	poll();

      };
	  var data = new FormData();
      data.append('timestamp', last_date_time);
      xhr.send(data);
	}
	window.addEventListener("DOMContentLoaded", poll);
</script>
</body>
</html>
