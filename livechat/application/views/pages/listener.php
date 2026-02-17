<div id="result"></div>
<script>
var source = new EventSource("https://crm.ontimegroup.com/livechat/sse.php");
source.onmessage = function(event) {
  document.getElementById("result").innerHTML += event.data + "<br>";
};
</script>