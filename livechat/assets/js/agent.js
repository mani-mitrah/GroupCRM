var xhr;
$("#stop_calls").hide();	
$("#main_chat").hide();
$("#default_main").show();
//$("#random_quote").html(get_random_quote());
var agent_id = document.getElementById("include_js_id").getAttribute("data-agentid");
var base_url = 'https://crm.ontimegroup.com/livechat/api/v1/';
var app_url = 'https://crm.ontimegroup.com/livechat/';
//set the last_updated_at as zero for the first call
var last_updated_at=0;
var last_timestamp=0;
var timeOutId = 0;
var expanded_conversation_id = '';

on_message_broadcast();


function scrollSmoothToBottom (id) {
   var div = document.getElementById(id);
   $('#' + id).animate({
      scrollTop: div.scrollHeight - div.clientHeight
   }, 500);
}

// Get the input field
var input = document.getElementById("messageInput");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById("send_message").click();
  }
});

function get_random_quote()
{
	let quote ='';
	$.ajax({
	    url: base_url+"conversation/random_quote/",
	    type: 'GET',
	    dataType: 'json', 
	    success: function(res) {
	    	var items = eval(res); //Converted to actual JSON data
			for (var item in items) {
			    quote = items[item]['quote'];
			}
	    }
	});
	return quote;
}



var ongoingFn = function () {
	//console.log('pinged ongoing');
	$.ajax({
	    url: base_url+"conversation/ongoing/"+agent_id,
	    type: 'GET',
	    dataType: 'json', 
	    success: function(res) {
      
	        let ac_id='';
	        let pg_id='';
	        let agent_id='';
	        let bot_id='';
	        let conversation_id='';
	        let created_at='';
	        let customer_id='';
	        let email_address='customer_email@customer.com';
	        let full_name='Customer';
			let mobile_number='+91 999999999';
			let last_updated_at='';
			let customer_name='';
			let customer_email='';
			let customer_mobile='';


		  	//console.log(res);

			//let json_obj = JSON.parse(res);
			
			if(res.length > 0)
			{
				$('#ongoing_conversations').html("");
				//notify_ongoing();
			}


			for (var i = 0; i < res.length; i++){

			    let obj = res[i];
			    for (var key in obj){
			        var attrName = key;
			        var attrValue = obj[key];

			        if(key==='id')
			        {
			        	ac_id = attrValue;
			        }

			        if(key==='pg_id')
			        {
			        	pg_id = attrValue;
			        }

			        if(key==='agent_id')
			        {
			        	agent_id = attrValue;
			        }

			        if(key==='botId')
			        {
			        	bot_id = attrValue;
			        }

			        if(key==='thread_id')
			        {
			        	conversation_id = attrValue;
			        }

			        if(key==='last_event_on')
			        {
			        	created_at = attrValue;
			        }
			        if(key==='userId')
			        {
			        	customer_id = attrValue;
			        }
			        if(key==='email_address')
			        {
			        	email_address = attrValue;
			        }
			        if(key==='full_name')
			        {
			        	full_name = attrValue;
			        }
			        if(key==='mobile_number')
			        {
			        	mobile_number = attrValue;
			        }
			        if(key==='last_heard_on')
			        {
			        	last_updated_at = attrValue;
			        }

			        //customer name & contact data
			        if(key==='customer_name')
			        {
			        	customer_name = attrValue;
			        }

			        if(key==='customer_email')
			        {
			        	customer_email = attrValue;
			        }

			        if(key==='customer_mobile')
			        {
			        	customer_mobile = attrValue;
			        }
			    }

			    let paused_user_data = $('<li id="con'+conversation_id+'" class="contacts-item friends"><a class="contacts-link" data-acid="'+ac_id+'" data-pgid="'+pg_id+'" data-agentid="'+agent_id+'" data-botid="'+bot_id+'" data-coversationid="'+conversation_id+'" data-created="'+created_at+'" data-customerid="'+customer_id+'" data-email="'+customer_email+'" data-fullname="'+customer_name+'" data-mobile="'+customer_mobile+'" data-updatedat="'+last_updated_at+'"  data-isclosed="0"   href="javascript:void(0);" onclick="javascript:expand(this);"><div class="avatar avatar-online avatar_ongoing">'+customer_name.substring(0,2).toUpperCase()+'</div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name text-truncate">'+customer_name+'</h6><div class="chat-time">'+moment.utc(last_updated_at).local().fromNow()+'</div></div><div class="contacts-texts"><p class="text-truncate">'+customer_mobile+'</p><div id="concount'+conversation_id+'" style="display:none;" class="badge badge-rounded badge-primary ml-1">0</div></div></div></a></li>');
			    paused_user_data.appendTo("#ongoing_conversations");

			}
	    }
	});	
}
ongoingFn();


//window.addEventListener("DOMContentLoaded", paused_poll);

function agent_takeover(pg_id)
{
	//console.log(pg_id);
	//console.log(base_url+"conversation/agent_takeover?pg_id="+pg_id+"&agent_id="+agent_id);
	
	$.ajax({
	    url: base_url+"conversation/agent_takeover?pg_id="+pg_id+"&agent_id="+agent_id,
	    async: false,
	    type: 'GET',
	    dataType: 'json', 
	    success: function(res) {
	        alert(res);
	        //console.log(res);
	        //paused_poll();
	    }
	});
}


function expand(convdata) 
{
	$("#default_main").hide();
	let ac_id=$(convdata).data('acid');
	let pg_id=$(convdata).data('pgid');
    let agent_id=$(convdata).data('agentid');
    let bot_id=$(convdata).data('botid');
    let conversation_id=$(convdata).data('coversationid');
    let created_at=$(convdata).data('created');
    let customer_id=$(convdata).data('customerid');
    let email_address=$(convdata).data('email');
    let full_name=$(convdata).data('fullname').toUpperCase();
	let mobile_number=$(convdata).data('mobile');
	let last_updated_at=$(convdata).data('updatedat');
	let last_updated_by=$(convdata).data('updatedby');
	let is_closed=$(convdata).data('isclosed');


	//remove the ongoing notification
	$('#concount'+conversation_id).hide();
	$('#concount'+conversation_id).html(0);

	//console.log(bot_id+" - "+ac_id+" - "+agent_id+" - "+conversation_id+" - "+created_at+" - "+customer_id+" - "+email_address+" - "+full_name+" - "+mobile_number+" - "+last_updated_at+" - "+last_updated_by+" - "+is_closed);

	$('#main_chat').show();
	$('#user_conversation').html("");
	$('#customer_name').html(full_name);
	$('#customer_email').html(email_address);
	$('#customer_mobile').html(mobile_number);

	//set values for sending message
	$('#send_message').data('botid',bot_id);
	$('#send_message').data('userid',customer_id);
	$('#send_message').data('conversationid',conversation_id);

	// set values for end chat
	$('#endchat').data('botid',bot_id);
	$('#endchat').data('customerid',customer_id);
	$('#endchat').data('conversationid',conversation_id);
	$('#endchat').data('pgid',pg_id);

	// if the conversation is from history, disable chat controls
	if(is_closed === 1) {
		//console.log("ended conversation");
		$("#messageInput").hide();
	    $("#send_message").hide();
	    $("#endchat").hide();
	}

	$.ajax({
	    url: base_url+"conversation/expand/"+conversation_id,
	    type: 'GET',
	    dataType: 'json', 
	    success: function(res) {
	        //console.log(res);
	        let message_id='';
	        let belongs_to='';
	        let message='';
	        let timestamp='';
	        let type='';
	        let message_side ='';
	        let user_icon='';

	       
	        //console.log(res.length);
	        if(res.length > 0)
			{
				$('#user_conversation').html("");
			}

			for (var i = 0; i < res.length; i++)
			{
			    let obj = res[i];
			    for (var key in obj){
			        var attrName = key;
			        var attrValue = obj[key];

			        if(key==='id')
			        {
			        	message_id = attrValue;
			        }

			        if(key==='name')
			        {

			        	if(attrValue==='User')
			        	{
			        		belongs_to = full_name;
			        		message_side='';
			        		user_icon='customer_chat_icon.png';

			        	}
			        	else if(attrValue==='Bot')
			        	{
			        		belongs_to = attrValue;
			        		message_side='self';	
			        		user_icon='chatbot_chat_icon.png';
			        	} 
			        	else if(attrValue==='Agent')
			        	{
			        		belongs_to = attrValue;
			        		message_side='agent';	
			        		user_icon='agent_chat_icon.png';
			        	}
			        }

			        if(key==='text')
			        {
			        	message = attrValue;
			        }
			        if(key==='timestamp')
			        {
			        	timestamp = attrValue;
			        }

			        if(key==='type')
			        {			 
			        	type = attrValue;
			        }
			    }

			    if(type==='session_reset') {
			    	let user_conversations = $('<div class="message-divider pb-2" data-label="User has reset the conversation">&nbsp;</div>');
			    	user_conversations.appendTo("#user_conversation");

			    } else {


			    	let user_conversations = $('<div class="message '+message_side+'"><div class="message-wrapper"><div class="message-content"><h6 class="text-dark">'+belongs_to+'</h6><span>'+message+'</span></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'+app_url+'/assets/media/icons/'+user_icon+'"></div><span class="message-date">'+moment.utc(timestamp).local().fromNow()+'</span></div></div>');
					user_conversations.appendTo("#user_conversation");
					//console.log('timestamp: '+timestamp);
				}
				
			}
			
		    $("#chat-finished").get(0).scrollIntoView();
			//get_recent_message(conversation_id,timestamp,full_name);
			//get_recent_message_in_conv(conversation_id);
			expanded_conversation_id = conversation_id;
			

	    }
	});
}

function send_message(button_data)
{
	let bot_id=$(button_data).data("botid");
	let customer_id=$(button_data).data("userid");//userid
	let text=$('#messageInput').val();
	let agent='yes'; 
	let agent_name=$(button_data).data("agentname");
	let conversation_id = $(button_data).data("conversationid");

	//console.log(bot_id+" - "+customer_id+" - "+text+" - "+agent+" - "+agent_name);

	$.ajax({
	    url: base_url+"conversation/send_msg/",
	    type: 'POST',
	    data: { 
	    	'bot_id' : bot_id,
			'user_id' : customer_id,
			'text' : text,
			'agent' : agent,
			'agent_name' : agent_name
    	},
	    success: function(res) {
	    	$('#messageInput').val('');
	        //console.log("send message success: "+res);
	        //get_recent_message(conversation_id,);
	    },
	    error: function(err) {
		  //console.log(err.responseText);
		}
	});	

}

function get_recent_message_in_conv(event)
{
	//recent.onmessage = function(event) {

		let id='';
		let conversationId ='';
		let incomingEventId ='';
		let eventId ='';
		let userId ='';
		let message_type ='';
		let message_text ='';
		let message_raw ='';
		let message_data ='';
		let payload ='';
		let full_name ='';
		let avatar_url ='';
		let sent_on ='';
		let payload_text='';
		let payload_agent='';
		let payload_agent_name='';
		let payload_type='';

		//choose side
		let belongs_to='';
		let message_side='';
		let user_icon='';
		
		//parse event data
		var obj = JSON.parse(event.data);
		id = obj.id;
		conversationId = obj.conversationId;
		incomingEventId = obj.incomingEventId;
		eventId = obj.eventId;
		userId = obj.userId;
		message_type = obj.message_type;
		message_text = obj.message_text;
		message_raw = obj.message_raw;
		message_data = obj.message_data;
		payload = obj.payload;
		full_name = obj.full_name;
		avatar_url = obj.avatar_url;
		sent_on = obj.sent_on;

		if(conversationId===expanded_conversation_id)
		{
			//parse payload
		    let payload_data = JSON.stringify(payload);
		    let payload_json = JSON.parse(payload_data);
		    
		    if(payload_json.hasOwnProperty('text')){
		    	payload_text = payload_json.text;
		    	//console.log(payload_text);
			}
			if(payload_json.hasOwnProperty('agent')){
		    	payload_agent = payload_json.agent;
		    	full_name = 'Agent';
		    	payload_agent_name = payload_json.agent_name;
			}
			payload_type = payload_json.type;

			//choose side to display
			if(full_name==='User')
        	{
        		belongs_to = full_name;
        		message_side='';
        		user_icon='customer_chat_icon.png';

        	}
        	else if(full_name==='Bot')
        	{
        		belongs_to = full_name;
        		message_side='self';	
        		user_icon='chatbot_chat_icon.png';
        	} 
        	else if(full_name==='Agent')
        	{
        		belongs_to = payload_agent_name;
        		message_side='agent';	
        		user_icon='agent_chat_icon.png';
        	}

			if(payload_type==='text' || payload_type==='quick_reply' || payload_type==='session_reset' )
			{
				if(payload_type==='session_reset') {
			    	let user_conversations = $('<div class="message-divider pb-2" data-label="User has reset the conversation">&nbsp;</div>');
			    	user_conversations.appendTo("#user_conversation");

			    } else {

			    	let user_conversations = $('<div class="message '+message_side+'"><div class="message-wrapper"><div class="message-content"><h6 class="text-dark">'+belongs_to+'</h6><span>'+message_text+'</span></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'+app_url+'/assets/media/icons/'+user_icon+'"></div><span class="message-date">'+moment.utc(sent_on).local().fromNow()+'</span></div></div>');
					user_conversations.appendTo("#user_conversation");
				}
				$("#chat-finished").get(0).scrollIntoView();
		   	}
		}
		
	//};
}

function notify_ongoing(event)
{
	//recent.onmessage = function(event) {

		//reset the values to zero
		let int_message_count=0;
		let new_count=0;

		//initialize value
		let id='';
		let conversationId ='';
		let incomingEventId ='';
		let eventId ='';
		let userId ='';
		let message_type ='';
		let message_text ='';
		let message_raw ='';
		let message_data ='';
		let payload ='';
		let full_name ='';
		let avatar_url ='';
		let sent_on ='';
		let payload_text='';
		let payload_agent='';
		let payload_agent_name='';
		let payload_type='';

		//parse event data
		var obj = JSON.parse(event.data);
		id = obj.id;
		conversationId = obj.conversationId;
		incomingEventId = obj.incomingEventId;
		eventId = obj.eventId;
		userId = obj.userId;
		message_type = obj.message_type;
		message_text = obj.message_text;
		message_raw = obj.message_raw;
		message_data = obj.message_data;
		payload = obj.payload;
		full_name = obj.full_name;
		avatar_url = obj.avatar_url;
		sent_on = obj.sent_on;


		$('#concount'+conversationId).show();
		let message_count = $('#concount'+conversationId).html();
		//console.log("message_count: "+message_count);
		int_message_count = parseInt(message_count);
		//console.log("int_message_count: "+int_message_count);
		new_count = int_message_count +1;
		//console.log("new_count: "+new_count);
		$('#concount'+conversationId).html(new_count);
		if(new_count > 0)
		{
			/* Audio link for notification */ 
            var audio = new Audio(app_url+"assets/media/sound/notification.mp3"); 
            audio.play(); 
		}
	//};
}
function waiting_customers(event)
{
	console.log("inside waiting_customers");
	console.log(event);

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
	        if(xhr.status==200 && paused==true)
	        {
				//console.log("waiting_customers inside");
				//means its a waiting customer and not assigned yet
				if(res==0)
				{
					//console.log("waiting_customers inside res 0: waiting conv");
					let paused_user_data = $('<li id="wc'+id+'" class="contacts-item friends"><a class="contacts-link"><div class="contacts-content"><div class="contacts-info"><div class="avatar avatar-online avatar_waiting">NEW</div><h6 class="chat-name text-truncate">New Customer Request</h6></div><div class="contacts-texts"><p class="text-truncate">Click start to accept</p><button onclick="agent_takeover('+id+');" class="btn btn-sm btn-success ml-1">Start</button></div></div></a></li>');
	    			paused_user_data.appendTo("#paused_conversations");		
				}
				else
				{
					console.log("waiting_customers inside res 1: removing waiting conv");
					$('#wc'+id).hide();
				}
				
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
//	document.getElementById("result").innerHTML += event.data + "<br>";
 
}

function on_message_broadcast() {
	var recent = new EventSource(app_url+"receive/sse.php");
	recent.onmessage = function(event) {
		notify_ongoing(event);
		get_recent_message_in_conv(event);
	}
	var waiting = new EventSource(app_url+"receive/hitl.php");
	waiting.onmessage = function(event) {
		waiting_customers(event);
		ongoingFn();
		historyFn();
	}

}

function end_chat(chatdata) {
	let bot_id=$(chatdata).data('botid');
	let pg_id=$(chatdata).data('pgid');
	let customer_id=$(chatdata).data('customerid');
	let conversation_id=$(chatdata).data('conversationid');

	//end_chat_get($bot_id, $user_id, $pg_id)

	$.ajax({
	    url: base_url+"conversation/end_chat/"+bot_id+"/"+customer_id+"/"+pg_id,
	    type: 'GET',
	    success: function(res) {
	        //console.log(res);
	        //console.log("conversation_id "+conversation_id);
	        $("#messageInput").attr("disabled", true);
	        $("#send_message").attr("disabled", true);
	        $("#endchat").hide();
	        $("#con"+conversation_id).hide();
	        $("#main_chat").hide();
	        $("#default_main").show();
	        $("#random_quote").html(get_random_quote());
	    },
	    error: function(e) {
	    	//console.log("Unpause failed "+ e);
	    }
	});
}

var historyFn = function () {
	//console.log('conversation history');
	$.ajax({
	    url: base_url+"conversation/history/"+agent_id,
	    type: 'GET',
	    dataType: 'json', 
	    success: function(res) {
	        let ac_id='';
	        let bot_id='';
	        let user_id='';
	        let agent_id='';
	        let conversation_id='';
	        let full_name='';
	        let email_address='';
			let mobile_number='';
			let last_updated_at='';

		  	//console.log(res);
			
			if(res.length > 0)
			{
				$('#all_conversations').html("");
			}

			for (var i = 0; i < res.length; i++){

			    let obj = res[i];
			    for (var key in obj){
			        var attrName = key;
			        var attrValue = obj[key];
			        if(key==='id')
			        {
			        	ac_id = attrValue;
			        }

			        if(key==='agent_id')
			        {
			        	agent_id = attrValue;
			        }

			        if(key==='bot_id')
			        {
			        	bot_id = attrValue;
			        }

			        if(key==='conversation_id')
			        {
			        	conversation_id = attrValue;
			        }

			        if(key==='user_id')
			        {
			        	customer_id = attrValue;
			        }
			        if(key==='email')
			        {
			        	email_address = attrValue;
			        }
			        if(key==='full_name')
			        {
			        	full_name = attrValue;
			        }
			        if(key==='mobile')
			        {
			        	mobile_number = attrValue;
			        }
			        if(key==='last_seen')
			        {
			        	last_updated_at = attrValue;
			        }
			    }

			    let all_user_data = $('<li id="con'+conversation_id+'" class="contacts-item friends"><a class="contacts-link" data-acid="'+ac_id+'" data-agentid="'+agent_id+'" data-botid="'+bot_id+'" data-coversationid="'+conversation_id+'"  data-customerid="'+customer_id+'" data-email="'+email_address+'" data-fullname="'+full_name+'" data-mobile="'+mobile_number+'" data-updatedat="'+last_updated_at+'" data-isclosed="1"   href="javascript:void(0);" onclick="javascript:expand(this);"><div class="avatar avatar_history">'+full_name.substring(0,2).toUpperCase()+'</div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name text-truncate">'+full_name+'</h6><div class="chat-time">'+moment.utc(last_updated_at).local().fromNow()+'</div></div><div class="contacts-texts"><p class="text-truncate">'+mobile_number+'</p><div id="concount'+conversation_id+'" style="display:none;" class="badge badge-rounded badge-primary ml-1">0</div></div></div></a></li>');
			    all_user_data.appendTo("#all_conversations");

			}
	    }
	});
}
historyFn();

	

	
