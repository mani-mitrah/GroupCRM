<?php
$dbconn = new PDO("pgsql:host=159.89.171.179;dbname=ontimebots", "nanouser", "TigerTable149");
$dbconn->exec('LISTEN "new_hitl"');   // those doublequotes are very important

header("X-Accel-Buffering: no"); // disable ngnix webServer buffering
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
ob_end_flush();  // close PHP output buffering
$inc=0;
error_log("ganesh testing hitl");
while (1) {
  $result = "";
  // wait for one Notify 10seconds instead of using sleep(10)
  $result = $dbconn->pgsqlGetNotify(PDO::FETCH_ASSOC, 10000);
  if(!empty($result))
  {
    $payload = $result['payload'];
    $payload_json = stripslashes(json_encode($payload));
    $clean_payload = trim($payload_json,'"');

    if ( $result ) {
          echo "id: $inc\ndata: ".$clean_payload."\n\n";
          //echo "id: $inc\ndata: ".stripslashes(json_decode($result))."\n\n";
          //echo "id: $inc\ndata: ".$result."\n\n";
          $inc++;
    }

    flush();
  }
}
?>