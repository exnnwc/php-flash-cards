<?PHP

include ("phpQuery-onefile.php");
define("DEFINITION", "span.dc-title");
//define ("DEFINITION", "div.refnamediv");
define("DESCRIPTION", "div.description");
define("PARAMETERS", "div.parameters");
define("RETURN", "div.returnvalues");
$invalid_keys = ["HDOM", "DEFA", "MAX_"];
$string = "";
$arr = get_defined_functions()["internal"];
sort($arr);
if (!isset($_GET['id'])) {
    $id = rand(0, sizeof($arr));
} else {
    $id = $_GET['id'];
}
//$random_id=rand(1,sizeof($arr));

/* echo "<div>
  <a href='".$_SERVER['PHP_SELF']."?id=".($id-1)."'>Prev</a>
  <a href='". $_SERVER['PHP_SELF'] ."?id=$random_id'>Random </a>
  <a href='".$_SERVER['PHP_SELF']."?id=".($id+1)."'>Next</a>
  </div>";

  echo "<div><a target='_blank' href='$function_url'>$arr[$id]</a></div>";
 */

$file = fopen("shit.txt", "w");

$fields = [];
error_reporting(E_ALL &  ~E_WARNING);
for ($i = 0; $i < 3; $i++) {
    $id = rand(0, sizeof($arr));
    $function_url = "http://php.net/manual/en/function." . str_replace("_", "-", $arr[$id]) . ".php";
    $html = phpQuery::newDocumentFile($function_url);
	if(strpos(error_get_last()["message"], "404 Not Found")==false){	
	
	echo "Fetching $function_url... <br /><br />";
        $name = $arr[$id];
        foreach (get_defined_constants(true)["user"] as $key => $value) {
            if (!in_array(substr($key, 0, 4), $invalid_keys)) {
                foreach (pq(constant($key)) as $element)
                    $string = $string .  process_string($element->textContent, $name) ;       
            } 
        } 
        $info = $string;
	echo "<span style='font-weight:bold'>$arr[$id]</span> <BR> $info";
    fwrite($file, "\"$name\", \"$info\" \n");
    } else {
	echo "<BR>$arr[$id] - $function_url<BR>";
        $i--;
    }
}

        
        

fclose($file);

function process_string($string, $name){
$EOL = ["</div>", "</h3>", "</dt>", "</dd>", "</p>"];
$bold_words=["Description", "Parameters", "Return Values"];
$delete = ["/[<>]/","/\W$name\W/"];
$newline_trim=["SHITSHIT"];
$string=  
  preg_replace("/SHIT\s+SHIT/", " ",
    str_replace("SHITSHIT", "SHIT",
      str_replace("\n", "SHIT",
        preg_replace($delete, "", 
          strip_tags(
	    preg_replace($EOL, "\n", 
	      $string))))));

for ($i=0;$i<10;$i++){
}
foreach ($bold_words as $bold_word){
	$string=str_replace("$bold_word", "<span style='font-weight:bold'>$bold_word</span>", $string);
}

//$string=htmlspecialchars($string);
return $string;


}

function show_html(){
}
