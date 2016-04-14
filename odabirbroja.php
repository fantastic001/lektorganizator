<html>
 <head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<style>
table, th, td {
     border: 1px solid black;
}
</style>
  <title>Lektorganizator</title>
 </head>
 <body>
 <?php 

header('Content-Type: text/html; charset=utf-8');

echo '<p>Lektorganizator — Odabir broja</p>';  

/*
Korisničko ime: <input type='text' id='user' name='user'><br>
Lozinka: <input type='text' id='pass' name='pass'> <br>
 */
 /*
$username = $_GET[''];
$password = $_GET[''];
$context = stream_context_create(array (
    'http' => array (
        'header' => 'Authorization: Basic ' . base64_encode("$username:$password")
    )
));
*/
if (isset($_GET['pri']) && $_GET['pri'] !== "") {
 $enkodiran = $_GET['pri'];
 
 $context = stream_context_create(array (
    'http' => array (
        'header' => 'Authorization: Basic ' . $enkodiran
    )
));


require 'profil.php';

if ($loginuspeh==true) {
echo "Vaš mejl je: ". $mejl;

$content = file_get_contents("https://libre.lugons.org/wiki/doku.php?id=wiki:arhivirani_brojevi&do=export_raw", false, $context);
//echo $content;

global $redovi;
$redovi = explode("\n", $content);

//echo $content;
// print_r($redovi); Preglednije je kroz „for“ petlju.

//Parsovanje: Čitam samo redove koji se nalaze između ======Arhiva objavljenih brojeva ====== i ====== Specijali: ======. Za to mi treba brojač
$unosi = array();
$broj = array();
$link = array();
$brojac = 0;
for ($i=0; $i < count($redovi); $i++ ) {
	//echo $redovi[$i] . "<br>";
	if ($brojac == 1) {
		$unosi[] = $redovi[$i];
	}
	if (strpos($redovi[$i], "======") !== false) {
	//	echo "<br> Brojač stari iznosi:" . $brojac;
		$brojac++;
	//	echo "<br> Brojač NOVI iznosi:" . $brojac;
	}


	if (count($redovi) - $i == 1) {echo "<hr> <br>";}
}

//$broj = (array_filter($broj));
/*
foreach($broj as $linija)
{
    if($linija == '')
    {
        unset($linija);
    }
}*/
//Sređivanje liste — brisanje praznih linija, preuređivanje indeksa i brisanje poslednjeg unosa.
$unosi = array_filter($unosi);
$unosi = array_values(array_filter($unosi));
array_pop($unosi);
//*/
//print_r($broj);

for ($i = 0; $i < count($unosi); $i++) {

$startli = strrpos($unosi[$i], '[[');
$endli = strrpos($unosi[$i], '|', $startli + 1);
$lengthli = $endli - $startli;
$link[$i] = substr($unosi[$i], $startli + 2, $lengthli - 2);
$link[$i] = "https://libre.lugons.org/wiki/doku.php?id=" . $link[$i];

$startbr = strrpos($unosi[$i], '|');
$endbr = strrpos($unosi[$i], ']]', $startbr + 1);
$lengthbr = $endbr - $startbr;
$broj[$i] = substr($unosi[$i], $startbr + 1, $lengthbr - 1);

//echo '<a href='.$link[$i].'>'.$broj[$i].'</a> <br>';

}

$poslednjibroj = end($broj);
$last = intval(substr($poslednjibroj, -1)) + 1;
$poslednjibroj = substr($poslednjibroj, -2, -1) . $last;

$poslednjilink = end($link);
$last = intval(substr($poslednjilink, -1)) + 1;
$poslednjilink = substr($poslednjilink, 0, -1) . $last;

//echo "Pretpostavljeni trenutni broj je " . '<a href='.$poslednjilink.'>'.$poslednjibroj.'</a> <br>';

echo "<form action='prikazbroja.php' id=".$poslednjibroj."method='get' onsubmit='mysecondFunction()'>
Pretpostavljeni trenutni broj je " . '<a href='.$poslednjilink.'>'.$poslednjibroj. "</a> <br>
<input type='hidden' name='broj' value='$poslednjibroj'>
<input type='hidden' id='maska' name='mejl' value='$mejl' style='display: none;'>
<input type='submit' value='Potvrdi izbor'>
</form>
";

echo "
<form action='prikazbroja.php' id=".$poslednjibroj."method='get' onsubmit='mysecondFunction()'>
<hr> Ako ovo nije poslednji broj, unesite željeni broj! <br>
<input type='number' name='broj' style='width:50px' >
<input type='hidden' id='maskadva' name='mejl' value='$mejl' style='display: none;'>
<input type='submit' value='Potvrdi izbor'>
</form>";

} else { echo "Korisničko ime i lozinka su pogrešni.";}


} elseif (isset($_GET['pri']) !== true) {
	echo "
		<div>
		<div id='first' style='display:inline-block; float: left; margin-top:2px;'>Korisničko ime:</div>
		<input type='text' id='user' name='user'>
		</div>
		<div>
		<div id='second' style='display:inline-block; float: left; margin-top:2px;'>Lozinka:</div>
		<input type='password' id='pass' name='pass'>
		</div>

		<form action='odabirbroja.php' method='get' onsubmit='myFunction()' >
		<input type='hidden' id='pri' name='pri' style='display: none;'>
		<input type='submit' id='hash' value='Prijavi se'>
		</form>";
	
}
/*
 <div id='pass' name='textBox' contentEditable='true' style='width:250px; height:20px; border:2px solid red; display:inline-block; margin-left:5px; white-space:nowrap; overflow:hidden;'></div>
 */ 

?>
<script type="text/javascript">

function myFunction() {
    //var ime = document.getElementsByID('#user');
    var ime=document.getElementById("user").value
    var loza=document.getElementById("pass").value
        //var ime = $('#user').html();
  //  var loza = $('#pass').html();
    
   // var loza = $('pass').html();
  //  window.alert(ime + ":" + loza);
    var encodedData = window.btoa(ime + ":" + loza);
  //  window.alert(encodedData);
        //$('hiddeninput').val(mysave);
    document.getElementById("pri").value = encodedData;
    
    
         
      /*   var els=document.getElementsByName("hiddeninput");
		for (var i=0;i<els.length;i++) {
		els[i].value = mysave;}*/
         
}
function mysecondFunction() {
    //var ime = document.getElementsByID('#user');
   // var ime=document.getElementById("user").value;
   // var loza=document.getElementById("pass").value;
    var mejl=document.getElementById("maska").value;
    var mejlEnc=window.btoa(mejl);
    
 //   window.alert(mejlEnc);
    
    document.getElementById("maska").value = mejlEnc;
    document.getElementById("maskadva").value = mejlEnc;
    //console.log(mejl);
    //var ime = $('#user').html();
  //  var loza = $('#pass').html();
    
   // var loza = $('pass').html();
  //  window.alert(ime + ":" + loza);
///    var encodedData = window.btoa(ime + ":" + loza);
  //  window.alert(encodedData);
        //$('hiddeninput').val(mysave);
///    document.getElementById("pri").value = encodedData;
    
    
         
      /*   var els=document.getElementsByName("hiddeninput");
		for (var i=0;i<els.length;i++) {
		els[i].value = mysave;}*/
         
}

</script>
 </body>
</html>
