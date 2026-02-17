<script type="text/javascript" src="https://crm.ontimegroup.com/livechat/assets/vendors/jquery/jquery-3.5.0.min.js"></script>
<div id="result"></div>
<ul id="list">
</ul>
<script>
var base_url = 'https://crm.ontimegroup.com/livechat/api/v1/';
var source = new EventSource("hitl.php");
var agent_id = 3420535658;
source.onmessage = function(event) {
	let id='';
	let botId='';
	let channel='';
	let userId='';
	let full_name='';
	let user_image_url='';
	let last_event_on='';
	let last_heard_on='';
	let paused='';
	let paused_trigger='';
	let thread_id='';

	var obj = JSON.parse(event.data);
	id = obj.id;
	botId = obj.botId;
	channel = obj.channel;
	userId = obj.userId;
	full_name = obj.full_name;
	user_image_url = obj.user_image_url;
	last_event_on = obj.last_event_on;
	last_heard_on = obj.last_heard_on;
	paused = obj.paused;
	paused_trigger = obj.paused_trigger;
	thread_id = obj.thread_id;



	$.ajax({
	    url: base_url+"conversation/check/",
	    type: 'POST',
	    data:JSON.stringify({'pg_id':id,'botId':botId,'channel':channel,'userId':userId,'full_name':full_name,'user_image_url':user_image_url,'last_event_on':last_event_on,'last_heard_on':last_heard_on,'paused':paused,'paused_trigger':paused_trigger,'thread_id':thread_id}),
	    success: function(res, textStatus, xhr) {
	        console.log(xhr.status);
	        console.log(paused);
	        console.log(res);
	        if(xhr.status==200 && paused==true && res==0)
	        {
				console.log("inside");
				//means its a waiting customer and not assigned yet
				let paused_user_data = $('<li class="contacts-item friends"><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name text-truncate">New Customer Request</h6></div><div class="contacts-texts"><button onclick="agent_takeover('+id+');" class="btn btn-sm btn-success ml-1">Start</button></div></li>');
	    		paused_user_data.appendTo("#list");	
	        }
	        else {
	        	console.log("else");
	        }
	    },
	    error:function(xhr, textStatus)
	    {
	    	console.log(xhr.status);	
	    }
	});	
	//document.getElementById("result").innerHTML += event.data + "<br>";
  
};
</script>