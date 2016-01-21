<?PHP

include ("phpQuery-onefile.php");
$string = "";
$file = fopen("http_status_codes.csv", "w");

$fields = [];
$first=true;
$url = "http://wiki.apache.org/httpd/CommonHTTPStatusCodes/";
$html = phpQuery::newDocumentFile($url);
echo  "Fetching $url... <br /><br />";
foreach (pq(".line862") as $element){
    $element->textContent=str_replace("\"", "&quot;", $element->textContent);
    if ((int)substr($element->textContent, 0, 1)>0  && !$first){
    	 $string = $string .  "\" \n ";
    }
    $string = strpos($element->textContent, "x - ") 
      ? $string . str_replace("x - ", "x, \"", $element->textContent) 
      : $string . $element->textContent;
    if ((int)$element->textContent>0 && substr($element->textContent, 1, 2)!="xx"){
    	 $string = $string .  ", \"";
    } else if ($element->textContent==" "){

    } else {
    	 $string = $string .  "<BR>";
    }
    if ($first){
    	$first=false;
    }
}
           $string = $string .  "\"";       
   
echo  str_replace("\n", "<br><Br>", $string);
fwrite($file, $string);
fclose($file);
