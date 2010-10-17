<?php
/*
exec.php Ver 0.1
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
include('lol_core.php');
include('STDIO.php');
include('SQL.php');

$DBG = false;

$file = trim($argv[1]);
if (trim($file) === ''){
    $msg = "The LOLCode interpreter expects exactly 1 (one) parameter and it should be a file name that exists.\n";
    die($msg);
}
if (!file_exists($file)){
    $msg = "Unable to stat file $file.\n";
    die($msg);
}
$code = file_get_contents($file);
if(!is_string($code)){die("Unable to parse file, please check it and try again.\n");}

$code = lol_core_parse($code);

if ($DBG){
    echo "==========Generated PHP code to be eval'd=================\n";
    echo $code;
    echo "==========================================================\n";
}
eval('?>'.$code.'');
?>
