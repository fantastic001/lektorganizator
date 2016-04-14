
 <?php 

header('Content-Type: text/html; charset=utf-8');

//echo '<p>Lektorganizatoar</p>';  
//echo exec('whoami');
 //file_put_contents('prihvaceno.txt',$shit); za pisanje.
if (isset($_GET['broj']) && is_int((int)$_GET['broj']) && (int)$_GET['broj'] !== 0) {
	
$f = fopen("prihvaceno",'r');
$c = 0;

$tekst_link = Array();
$lektor = Array();
$rok = Array();

while($line=fgetcsv($f)){
	$c++;
    if(count($line)<3){
		die("Line $c is FUCKED!");
	}
	$tekst_link[] = $line[0];
	$lektor[] = $line[1];
	$rok[] = $line[2];
}
//echo "<hr>Uspeo ubaciti csv.<hr>";
fclose($f);
} else {
	echo "Broj nije (dobro) podešen.";
	//echo (int)$_GET['broj'];
}

?>

