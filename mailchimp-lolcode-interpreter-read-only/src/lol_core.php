<?php
/*
lol_core.php Ver 0.3
Copyright (c) 2007 Jeff Jones, www.tetraboy.com
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
function lol_core_parse($code) {
    $code = lol_core_replace($code);
    return $code;
}
//@todo only replace inside tags
function lol_core_replace($code) {
    $array = array(
        '/^HAI$/','<?php',
        '/^OH HAI$/','<?php',
        '/^KTHXBYE$/','',

        '/&&([a-zA-Z0-9_-]+)&&/','$$$1',
        '/&([a-zA-Z0-9_-]+)&/','$$1',
//        '/&([a-zA-Z0-9_-]+)#([a-zA-Z0-9,_-]+)&/','lol_core_pregArray(\'$1\',\'$2\');',
        '/&([a-zA-Z0-9_-]+)#([a-zA-Z0-9,_-]+)&/','$$1["$2"]',

        '/^[\s]*CAN HAS STDIO\?$/','',
        '/^[\s]*MUST HAS STDIO$/','',
        '/^[\s]*CAN HAS SQL\?$/','',
        '/^[\s]*MUST HAS SQL$/','',
        '/^[\s]*CAN HAS ([^\.]+)\?$/','include(\'$1\');',
        '/^[\s]*MUST HAS ([^\.]+)$/','require(\'$1\');',
        
        '/^[\s]*VISIBLE ([a-zA-Z0-9-]+)$/','echo $$1;',
        '/^[\s]*VISIBLE[\s]*$/','echo "\n";',
        '/^[\s]*VISIBLE __(.*)__$/','echo __$1__;',
        '/^[\s]*VISIBLE (.*\s*)$/','echo "$1";',
        '/^[\s]*VISIBLEZ (\$[[\]\'"a-zA-Z0-9-]+)[\s]*$/','echo $1."\n";',
        '/^[\s]*VISIBLEZ ([a-zA-Z0-9-]+)$/','echo $$1."\n";',
        '/^[\s]*VISIBLEZ[\s]*$/','echo "\n";',
        '/^[\s]*VISIBLEZ __(.*)__$/','echo __$1__."\n";',
        '/^[\s]*VISIBLEZ (.*)$/','echo "$1\n";',

        '/^[\s]*INVISIBLE (.*)$/','',
        
        '/^[\s]*I HAS ([a-zA-Z0-9_-]+)$/','$$1 = null;',
        '/^[\s]*I HAS ([a-zA-Z0-9_-]+) IZ (BUKKIT)[\s]*$/','$$1 = array(',
        '/^[\s]*I HAS ([a-zA-Z0-9_-]+) IZ ([a-zA-Z0-9]+)$/','$$1 = \'$2\';',
        '/^[\s]*I HAS ([a-zA-Z0-9_-]+) IZ (.*)$/','$$1 = $2;',
        
        '/^[\s]*I HAS[\s]+(.*)[\s]+IN MAH BUKKIT ITZ[\s]*\n?\r?[\s]*(.*)[\s]*\n?\r?KTHX!/m', '\'$1\' => trim(\'$2\'),',

        '/[\s]*I IS BORED[\s]*$/', ');',
                
        '/^[\s]*GIMMEH ([a-zA-Z0-9_-]+)[\s]*$/', '$$1 = lol_core_io("LINE", "STDIN");',
        '/^[\s]*GIMMEH LINE[\s]+([a-zA-Z0-9_-]+)[\s]*$/', '$$1 = lol_core_io("LINE", "STDIN");',
        '/^[\s]*GIMMEH WERD ([a-zA-Z0-9_-]+)[\s]*$/', '$$1 = lol_core_io("WERD", "STDIN");',  
        
        '/^[\s]*GIMMEH[\s]+([a-zA-Z0-9_-]+) OUTTA (.*)$/', '$$1 = lol_core_io("LINE", "$2");',  
        '/^[\s]*GIMMEH[\s]+([A-Z]*)[\s]+([a-zA-Z0-9_-]+) OUTTA (.*)$/', '$$2 = lol_core_io("$1", "$3");',
        
        '/^[\s]*MOARAGINS[\s]+([a-zA-Z0-9_-]+)[\s]*$/', 'eval(\'?>\'.lol_core_parse($$1));',


        '/^[\s]*([a-zA-Z0-9_-]+) IZ (BUKKIT)$/','$$1 = array(',
        '/^[\s]*([a-zA-Z0-9_-]+) IZ ([a-zA-Z0-9]+)$/','$$1 = \'$2\';',
        '/^[\s]*([a-zA-Z0-9_-]+) IZ (.*)$/','$$1 = $2;',

        '/^[\s]*KTHX[\s]*$/','}',
        
        '/^[\s]*([a-zA-Z0-9_-]+) UPUP![\s]*$/','$$1++;',
        '/^[\s]*([a-zA-Z0-9_-]+) DOWNDOWN![\s]*$/','$$1--;',
        
        '/^[\s]*I FOUND MAH ([a-zA-Z0-9_-]+)[\s]*$/','return $$1;',
        '/^[\s]*I FOUND MAH (.*)$/','return $1;',
        
        '/^[\s]*SO IM LIKE ([a-zA-Z0-9_-]+) WITH (.*)$/','lol_core_pregFunc(\'$1\',\'$2\');',
        '/^[\s]*BTW (.*)$/','',
        '/^[\s]*BTW![\s]*$/','/*',
        '/^[\s]*!BTW[\s]*$/','*/',
        '/^[\s]*ALWAYZ ([a-zA-Z0-9_-]+) IZ (.*)$/','define("__$1__","$2");',
        '/^[\s]*(IZ) (.*)$/','lol_core_pregExpression(\'if\',\'$2\');',
        '/^[\s]*(ORLY) (.*)$/','lol_core_pregExpression(\'elseif\',\'$2\');',
        '/^[\s]*(NOWAI)[\s]*$/','lol_core_pregExpression(\'else\',\'$2\');',
        '/^[\s]*BUKKIT[\s]*$/',');',
        '/^[\s]*BAG[\s]*$/','),',
        '/^[\s]*(!!) FISH (".*") !![\s]*$/','$2,',
        '/^[\s]*([a-zA-Z0-9_-]+) FISH IZ BAG[\s]*$/','\'$1\' => array(',
        '/^[\s]*([a-zA-Z0-9_-]+) FISH (".*") !![\s]*$/','\'$1\' => \'$2\',',
        '/^[\s]*IM IN UR ([a-zA-Z0-9_-]+) ITZA ([a-zA-Z0-9_-]+)[\s]*$/','foreach($$1 as $$2) {',
        '/^[\s]*IM IN UR ([a-zA-Z0-9_-]+) ITZA ([a-zA-Z0-9_-]+) IZ ([a-zA-Z0-9_-]+)[\s]*$/','foreach($$1 as $$2=>$$3) {',
        '/^[\s]*IM IN UR ([a-zA-Z0-9_-]+) ITZA (.*)$/','foreach($$1 as $2) {',
        '/^[\s]*DIAF (.*)$/','die($1);',
        '/^[\s]*POOPONIT! (.*)$/','var_dump($$1,true);'
    );
    $search = array();
    $replace = array();
    $lines = explode("\n",$code);

    foreach($array as $key=>$var){
        if(1 & $key)    {
            $replace[] = $var;
        } else {
            $search[] = $var;
        }
    }
    $code = "";
    $errors = array();
    $i = 1;
    list($errors, $code) = lolparse($lines, $replace, $search);
    if (sizeof($errors)==0){
        return $code;
    } else {
        echo "\n\t\t]]]]]OMGWTFBBQ!!1! suhmting borked1![[[\n\n";
        foreach($errors as $err){
            echo $err."\n";
        }
        die();
    }
    
}

function debug($str){
    global $DBG;
    if ($DBG){
        echo $str;
    }
}

function lolparse($lines, $replace, $search, $lineno=1, $bukkitsofbukkits=false){
    $errors = array();
    $code = '';
    for($i=0;$i<sizeof($lines);$i++){
        $line = $lines[$i];
        if ($line!=''){
            $cnt = 0;
            if ($bukkitsofbukkits && strpos($line,'IZ BUKKIT')){
                debug("beg recur bukkit\n");
                $pat = '/^[\s]*I HAS ([a-zA-Z0-9_-]+) IZ (BUKKIT)[\s]*$/';
                $rep = '\'$1\' => array(';
                $newline = preg_replace($pat,$rep,$line);
            } elseif ($bukkitsofbukkits && strstr($line,'I IS BORED') ){
                debug("end recur bukkit\n");
                $pat = '/^[\s]*I IS BORED[\s]*$/';
                $rep = '),';
                $newline = preg_replace($pat,$rep,$line);
            } else {
                $newline = preg_replace($search,$replace,$line);
                
                if (preg_match('/^lol_core_.*/',trim($newline))){
                    debug($newline."\n");
                    eval('$newline = '.$newline);
                    debug($newline."\n");
                }
            }
            debug("$i: [$bukkitsofbukkits] [$line] = [$newline]\n");
            if ($newline!=$line && $newline != ''){
                if (is_array($newline)){
                    $code .= implode("\n",$newline);
                } else {
                    $code .= $newline."\n";
                }
                //echo "$i: ".$line."\n";
                if ( strpos($line,'IZ BUKKIT') ){
                    $i++;
                    $str = $lines[$i];
                    //$str = $line;
                    //echo "IZ $i: ".$str."\n";
                    $line = '';
                    $beg_bukkits = 1;
                    $term_bukkits = 0;
                    do{
                        if (strpos($str,'IZ BUKKIT')){
                            $beg_bukkits++;
                        }
                        $line .= "\n".$str;
                        $items++;
                        $i++;
                        $str = $lines[$i];
                        if ( strpos($lines[$i],'I IS BORED') ){
                            $term_bukkits++;
                        }
                        debug("$beg_bukkits:$term_bukkits ".$str."\n");
                    } while ( (!preg_match('/^[\s]*I IS BORED[\s]*$/', $str) || $beg_bukkits != $term_bukkits) && $str != false);
                    if ($term_bukkits > 1){
                        $line = preg_replace('/I IS BORED$/','',$line);
                        $i--;
                    }
                    if ($str === false){
                        $errors[] = "Line: $i: There's a BUKKIT but no terminating I IS BORED !!!!...";
                    } else {
                        if ($items == 0){
                            $line .= " ";
                        }
                        debug("$i-----------\n$line\n----------\n");

                        if ($beg_bukkits>1){
                            $moarlines = explode("\n",$line);
                            list($errs, $moarcode) = lolparse($moarlines, $replace, $search, $i, true);
                        } else {
                            list($errs, $moarcode) = lolparse(array($line), $replace, $search, $i);
                        }
                        $errors = array_merge($errors, $errs);
                        $code .= $moarcode;
                    }
                    $i--;
                }
            } elseif ($newline !='') {
                //echo "Line: $i: parse error at or around - ".substr($line,0,100)."...\n";
                $errors[] = "Line: $i: parse error near - ".substr($line,0,100)."...";
            }
       }
    }
    return array($errors, $code);
}//lolparse

function lol_core_pregArray($name,$string) {
    $var = '$'.$name;
    $keys = explode(',',$string.',');
    foreach($keys as $key) {
        if($key !== '') {
            $var .= "['{$key}']";
        }
    }
    return $var;
}
function lol_core_pregExpression($name,$string) {
    switch($name) {
        case 'if':
            $expr = "if ($string) {";
        break;
        case 'elseif':
            $expr = "} elseif ($string) {";
        break;
        case 'else':
            $expr = "} else {";
        break;
    }
   // echo $expr;
    return $expr;
}
function lol_core_pregFunc($name,$args) {
    $func = 'function '.$name.' (';
    $args = explode(' ',$args);
    $i=0;
    foreach ($args as $arg) {
        if($i==1){$func .= ',';}
        $func .= '$'.$arg;
        $i=1;
    }
    $func .= ') {';
    return $func;
}
?>
