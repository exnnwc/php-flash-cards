<?PHP
$num_of_functions=0;
include ("phpQuery-onefile.php");
define("DEFINITION", "span.dc-title");
//define ("DEFINITION", "div.refnamediv");
define("DESCRIPTION", "div.description");
define("PARAMETERS", "div.parameters");
define("RETURN", "div.returnvalues");
$invalid_keys = ["HDOM", "DEFA", "MAX_"];
$function_list = get_defined_functions()["internal"];
sort($function_list);
if (!isset($_GET['id'])) {
    $id = rand(0, sizeof($function_list));
} else {
    $id = $_GET['id'];
}

$file = fopen("php_functions.csv", "w");

$fields = [];
error_reporting(E_ALL &  ~E_WARNING);//sizeof($function_list)
for ($i = 0; $i < 3; $i++) {
    $string = "";
    $function_url = "http://php.net/manual/en/function." . str_replace("_", "-", $function_list[$i]) . ".php";
    $html = phpQuery::newDocumentFile($function_url);
	if(strpos(error_get_last()["message"], "404 Not Found")==false){	
        $num_of_functions++;
   	echo "Fetching $function_url... \n";
        $name = $function_list[$i];
        foreach (get_defined_constants(true)["user"] as $key => $value) {
            if (!in_array(substr($key, 0, 4), $invalid_keys)) {
                foreach (pq(constant($key)) as $element){
                   $string = $string .  process_string($element->textContent, $name) ;       
                }
            } 
        } 
        $info = $string;
    	echo "<span style='font-weight:bold'>$function_list[$id]</span> <BR> $info";
        fwrite($file, "\"$name\", \"$info\" \n");
    } else {
    	echo "$function_list[$i] - $function_url does not exist.\n";
    }
    sleep(1);
}
fclose($file);
echo "$num_of_functions out of ".sizeof($function_list) ." functions have been processed.";
function process_string($string, $name){
    $EOL = ["</div>", "</h3>", "</dt>", "</dd>", "</p>"];
    $bold_words=["Description", "Parameters", "Return Values"];
    $delete = ["/[<>]/","/\W$name\W/"];
/*    $string=  
      str_replace ("Procedural style ", "<br />Procedural style ",
        preg_replace("/XPLACEHOLDERX\s+XPLACEHOLDERX/", " ",
          str_replace("XPLACEHOLDERX", "<BR>", 
            str_replace("XPLACEHOLDERXXPLACEHOLDERX", "XPLACEHOLDERX",
                preg_replace($delete, "", 
                  htmlspecialchars(
                    strip_tags(
          	          preg_replace($EOL, "XPLACEHOLDERX", 
          	            $string))))))));*/
    $string = str_replace("$name", "NAME", $string);
    foreach ($bold_words as $bold_word){
    	$string=str_replace("$bold_word", "</pre><div style='font-weight:bold;margin-top:16px;'>$bold_word</div><pre>", $string);
    }
    return $string;
}

