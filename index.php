<?PHP
include ("simple_html_dom.php");
define ("DEFINITION", "span.dc-title");
define("DESCRIPTION", "div.description");
define("PARAMETERS", "div.parameters");
define("RETURN", "div.returnvalues"); 
$arr = get_defined_functions()["internal"];
sort($arr);
if (!isset($_GET['id'])){
	$id=rand(0, sizeof($arr));
} else {
	$id=$_GET['id'];
}
//$random_id=rand(1,sizeof($arr));
$function_url="http://php.net/manual/en/function." . str_replace("_", "-", $arr[$id]) . ".php";
/*echo "<div>
	<a href='".$_SERVER['PHP_SELF']."?id=".($id-1)."'>Prev</a>
	<a href='". $_SERVER['PHP_SELF'] ."?id=$random_id'>Random </a>
	<a href='".$_SERVER['PHP_SELF']."?id=".($id+1)."'>Next</a>
      </div>";
	
echo "<div><a target='_blank' href='$function_url'>$arr[$id]</a></div>";
*/
if (!$html=file_get_html($function_url)){
	$html=file_get_html("http://php.net/manual/en/" . str_replace("_", "-", $arr[$id]) . ".php");
}

$invalid_keys=["HDOM", "DEFA", "MAX_"];
$EOL=["</div>", "</h3>", "</p>"];
echo $arr[$id] . "<BR><BR>";
foreach (get_defined_constants(true)["user"] as $key=>$value){
	if (!in_array(substr($key,0,4), $invalid_keys)){
		foreach($html->find(constant($key)) as $element)
		       echo nl2br(preg_replace("/\W$arr[$id]\W/", "", strip_tags(preg_replace($EOL, "\n", $element->innertext)))) . "<BR><BR>";
	}

}
