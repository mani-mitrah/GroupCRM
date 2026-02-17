<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unihook extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function on_message()
    {
    	$dbconn = new PDO("pgsql:host=159.89.171.179;dbname=nanobots", "nanouser", "TigerTable149");
		$dbconn->exec('LISTEN "new_message"');   // those doublequotes are very important

		header("X-Accel-Buffering: no"); // disable ngnix webServer buffering
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		ob_end_flush();  // close PHP output buffering
		$inc=0;

		while (1) {
		  $result = "";
		  // wait for one Notify 10seconds instead of using sleep(10)
		  $result = $dbconn->pgsqlGetNotify(PDO::FETCH_ASSOC, 10000);

		  if ( $result ) {
		        echo "id: $inc\ndata: ".stripslashes(json_encode($result))."\n\n";
		        $inc++;
		  }

		  flush();
		}
    }
}
