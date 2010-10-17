<?php
/*
STDIO.php Ver 0.1
Copyright (c) 2008 MailChimp.com - Jesse Peterson - www.mailchimp.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
function lol_core_io($what, $where){
    if ($where == "STDIN"){
        $fh = fopen('php://stdin','r');
        echo "gimmeh input: ";
        $result = fgets($fh);
        fclose($fh);
    } elseif ($where == "BODY"){
	$filtered = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST["Body"]);


	$result=rawurlencode($filtered);
    } elseif (substr($where,0,7)=='http://'){
        ob_start();
	$url = parse_url($where);
	return file_get_contents($where);

       // return $url['host'];
	$sock = fsockopen($url['host'], 80, $errno, $errstr, 30);
        if(!$sock) {
            echo "Could not connect (ERR $errno: $errstr)";
            ob_end_clean();
            return '';
        }
        $payload = "GET " . $where . " HTTP/1.0\r\n";
        $payload .= "Host: " . $url["host"] . "\r\n";
        $payload .= "User-Agent: LOLCode\r\n";
        $payload .= "Content-length: 0\r\n";
        $payload .= "Connection: close \r\n\r\n";
        fwrite($sock, $payload);
        while(!feof($sock)) {
            $response .= fread($sock, 4096);
        }
        fclose($sock);
        ob_end_clean();
        list($throw, $result) = explode("\r\n\r\n", $response, 2);
    } else {
        if (!file_exists($where)){
            die("borked!11! $where ain't thar.\n");
        }
       $result = file_get_contents($where);
    }
    switch($what){
        case "LAL": return $result; break;
        case "WERD": $parts = explode(' ',$result);
                     return $parts[0]; break;
        case "LETTA": return substr($parts[0],0,1); break;
        case "LINE": 
        default:
                $parts = explode("\n",$result);
                return $parts[0]; break;
    }
}
?>
