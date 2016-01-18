<?PHP

include ("phpQuery-onefile.php");
define("DEFINITION", "span.dc-title");
//define ("DEFINITION", "div.refnamediv");
define("DESCRIPTION", "div.description");
define("PARAMETERS", "div.parameters");
define("RETURN", "div.returnvalues");
$invalid_keys = ["HDOM", "DEFA", "MAX_"];
$EOL = ["</div>", "</h3>", "</dt>", "</dd>"];
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

//$file = fopen("test.csv", "w");

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
                    $string = $string . nl2br(preg_replace("/[<>]/", "", preg_replace("/\W$arr[$id]\W/", "", strip_tags(preg_replace($EOL, "\n", preg_replace("</p>", "\n", $element->textContent)))))) . "<BR>";
            } 
        } 
        $info = $string;
	echo "<span style='font-weight:bold'>$arr[$id]</span> <BR> $info";
  //  fwrite($file, "\"$name\", \"$info\" \n");
    } else {
	echo "<BR>$arr[$id] - $function_url<BR>";
        $i--;
    }
}

        
        

//fclose($file);
