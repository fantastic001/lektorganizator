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

echo '<p>Lektorganizator — Prikaz broja</p>';

echo "
<div id='top'>
<div id='first' style='display:inline-block; float: left; margin-top:2px;'>Rok:</div>
<div id='textBox' name='textBox' contentEditable='true' style='width:250px; height:20px; border:2px solid red; display:inline-block; margin-left:5px;'></div>
</div>
<br>";
//echo "<button id='save' onclick='myFunction()'>Try it</button>";
if(isset($_GET['broj'])) {
	global $broj;
	$broj = $_GET['broj'];
			
	prikaz();
} else {
echo "Broj nije odabran.";
}

function prikaz()
{

function parsuj_red(&$stanje1, &$stanje2, &$link, &$naslov)
{
	global $redovi;
	for ($i = 0; $i < count($redovi); $i++) {
		//echo $stanje2;
		$uporedno = strrpos($redovi[$i], '[[');
		$novi = substr($redovi[$i], $uporedno);
		//echo $novi . "<br>";
		if (strpos($novi, $stanje2) !== false && strpos($redovi[$i], "[[") !== false) {

			$stanje1[] = $redovi[$i];
			//echo end($stanje1) . "<br>";
			$startli = strrpos(end($stanje1), '[[');
			$endli = strrpos(end($stanje1), '|', $startli + 1);
			$lengthli = $endli - $startli;
			$link[] = substr(end($stanje1), $startli + 2, $lengthli - 2);
		
			$startnas = strpos(end($stanje1), '**');
			$endnas = strpos(end($stanje1), '**', $startnas + 1);
			$lengthnas = $endnas - $startnas;
			$naslov[] = substr(end($stanje1), $startnas + 2, $lengthnas - 2);
			//echo $stanje1[$i] . $stanje2 . $link[$i] . $naslov[$i] . "<br>"; 
		}
	
	}
#zagrada i kraj funkcije parsuj_red().	
}

function prikaz_tekstova($stanje, $link, $naslov)
{
	if (count($link) > 0) {
     echo "<table style='width:75%; text-align:center;'><tr><th></th><th>". $stanje ."</th><th>Stanje</th></tr>";
     // output data of each row
     for ($i = 0; $i < count($link); $i++) {
         //echo "<tr><td>" . $i. "</td><td>" . $link[$i]. "</td></tr>";
	$z = $i + 1;
	echo 
"<tr>
<td style='width:20px;'>" .
$z
. "</td>

<td>"
.'<a href="https://libre.lugons.org/wiki/doku.php?id=wiki:'.$link[$i].'">'.$naslov[$i].'</a>'.
"</td>
<td style='width:20%; ";
switch ($stanje) {
    case "LEKTORISAN":
        echo "background: linear-gradient(
  to top,
  #ffcd04,
  #fff2c0
); border-radius: 25px; font-weight: bold;'>";
        break;
    case "PROVEREN":
        echo "background: linear-gradient(
  to top,
  #0cad53,
  #80f9b5
); border-radius: 25px; font-weight: bold;'>";
        break;
    case "U_PRIPREMI":
        echo "background: linear-gradient(
  to top,
  #0cad53,
  #80f9b5
); border-radius: 25px; font-weight: bold;'>";
	break;
    case "ORIGINAL":
		echo "background: linear-gradient(
  to top,
  #b7b7b7,
  #fbfbfb
); border-radius: 25px; font-weight: bold;'>";
		break;
	case "FIXME":
		echo "background: linear-gradient(
  to top,
  #fed500,
  #fef694
); border-radius: 25px; font-weight: bold;'>";
		break;
    default:
		echo "'>";
}

echo  $stanje .
"</td>
</tr>";
     }
     echo "</table> <br>";
} else {
     echo "Trenutno nema tekstova sa oznakom: " . $stanje;
}
//kraj prikaz_tekstova().	
}



//###izvan svih funkcija


$username = "aleksa";
$password = "librelektor20";
$context = stream_context_create(array (
    'http' => array (
        'header' => 'Authorization: Basic ' . base64_encode("$username:$password")
    )
));
global $broj;
$content = file_get_contents("https://libre.lugons.org/wiki/doku.php?id=wiki:broj_". $broj ."&do=export_raw", false, $context);
//echo $content;

global $redovi;
$redovi = explode("\n", $content);

//Čita se samo između određenih delova.

$unosi = array();
$brojac = 0;
for ($i=0; $i < count($redovi); $i++ ) {
	//echo $redovi[$i] . "<br>";
	if ($brojac == 1) {
		$unosi[] = $redovi[$i];
	}
	if (strpos($redovi[$i], "==== Tekstovi razvrstani po rubrikama ====") !== false) {
	//	echo "<br> Brojač stari iznosi:" . $brojac;
		$brojac++;
	//	echo "<br> Brojač NOVI iznosi:" . $brojac;
	} elseif (strpos($redovi[$i], "==== PDF ====") !== false) {
		$brojac++;
	}
	//if (count($redovi) - $i == 1) {echo "<hr> <br>";}
}
$redovi = $unosi;


$prihvacen = array();
$lektorisan = array();
$original = array();
$fixme = array();
$proveren = array();
$upripremi = array();

$pri_l = array();
$lek_l = array();
$ori_l = array();
$fixme_l = array();
$prov_l = array();
$upri_l = array();

$pri_n = array();
$lek_n = array();
$ori_n = array();
$fixme_n = array();
$prov_n = array();
$upri_n = array();

$pri_t = "PRIHVACEN";
$lek_t = "LEKTORISAN";
$ori_t = "ORIGINAL";
$fixme_t = "FIXME";
$prov_t = "PROVEREN";
$upri_t = "U_PRIPREMI";

require 'csv.php';

parsuj_red($prihvacen, $pri_t, $pri_l, $pri_n);
parsuj_red($lektorisan, $lek_t, $lek_l, $lek_n);
parsuj_red($original, $ori_t, $ori_l, $ori_n);
parsuj_red($fixme, $fixme_t, $fixme_l, $fixme_n);
parsuj_red($proveren, $prov_t, $prov_l, $prov_n);
parsuj_red($upripremi, $upri_t, $upri_l, $upri_n);

if (count($pri_l) > 0) {
	echo "<table style='width:75%; text-align:center;'><tr><th></th><th>". $pri_t ."</th><th>Lektor</th></tr>";

for ($i = 0; $i < count($pri_l); $i++) {
	$z = $i + 1;
	if (in_array($pri_l[$i], $tekst_link)) {
		//echo "'". $pri_l[$i] . "' je u ";
		//print_r($tekst_link);
		//echo "<br>";
		//napiši ime lektora
		$index = array_search($pri_l[$i],$tekst_link);
			echo
"<tr>
<td style='width:20px;'>"
.$z.
"</td>
<td>"
.'<a href="https://libre.lugons.org/wiki/doku.php?id=wiki:'.$pri_l[$i].'">'.$pri_n[$i].'</a>'.
"</td>
<td style='width:10%;'>"
.$lektor[$index].
"</td>
<td style='width:10%;'>"
.$rok[$index].
"</td>
</tr>";
	} else {
	//	echo "'" .$pri_l[$i] . "' nije u ";
	//	print_r($tekst_link);
	//	foreach ($tekst_link as $blabla) {
	//		echo "<br>'" . $blabla . "'";
	//	}
	//	echo "<br>";
	//	echo "<br>";
		//stavi odabir lektora
		$mejl = $_GET['mejl'];
		echo 
"<tr>
<td style='width:20px;'>"
.$z.
"</td>
<td>"
.'<a href="https://libre.lugons.org/wiki/doku.php?id=wiki:'.$pri_l[$i].'">'.$pri_n[$i].'</a>'.
"</td>
<form action='sendmail.php' id='posalji' method='get' onsubmit='myFunction()' >
<input type='hidden' name='tekst' value='$pri_l[$i]'>
<input type='hidden' name='naslov' value='$pri_n[$i]'>
<input type='hidden' name='mejl' value='$mejl'>
<textarea id='hiddeninput' name='hiddeninput' style='display: none;'></textarea>
<td style='width:10%;'>
<select name='lektor'>
  <option disabled selected value> -- odabir -- </option>
  <option value='i_Admire Admir bozinovic.aleksandar@gmail.com'>Admir</option>
  <option value='a_Aleksandra Aleksandra bozinovic.aleksandar@gmail.com'>Aleksandra Ristović</option>
  <option value='a_Jelena Jelena bozinovic.aleksandar@gmail.com'>Jelena Munćan</option>
  <option value='a_Milana Milana bozinovic.aleksandar@gmail.com'>Milana Vojnović</option>
  <option value='a_Saška Saška bozinovic.aleksandar@gmail.com'>Saška Spišjak</option>
  <option value='i_Aleksandre Aleksandar bozinovic.aleksandar@gmail.com'>Aleksandar</option>
</select>
</td>
<td style='width:10%;'>
<input type='submit' id='save' name='save' value='Potvrdi izbor'>
</form>
</td>
</tr>";
		
	}
}
echo "</table> <br>";
}

//prikaz_prihtekstova($pri_t, $pri_l, $pri_n);
prikaz_tekstova($lek_t, $lek_l, $lek_n);
prikaz_tekstova($prov_t, $prov_l, $prov_n);
prikaz_tekstova($ori_t, $ori_l, $ori_n);
prikaz_tekstova($fixme_t, $fixme_l, $fixme_n);
prikaz_tekstova($upri_t, $upri_l, $upri_n);



//zatvorena zagrada funkcije prikaz().
}

?>

<script type="text/javascript">
/*$(function(){
    $('#save').click(function () {
        var mysave = $('#textBox').html();
     //   $.each($('[name="hiddeninput"]'),function() { $(this).value(mysave)});
     //   console.log($(this));
        $('[name="hiddeninput"]').each(function() {
			console.log( $(this).context);
			$(this).value(mysave);
});
       // $.each('[name="hiddeninput"]').value(mysave);
		//for (var i=0;i<els.length;i++) {
		//els[i].value = mysave;}
      //document.getElementsByName('hiddeninput').value = ;
    //  $('[name="hiddeninput"]').value(mysave);
      //  $('hiddeninput').val(mysave);
      //   window.alert(mysave);
    });
});
*/
function myFunction() {
    var mysave = $('#textBox').html();
        //$('hiddeninput').val(mysave);
       // document.getElementsByName('#hiddeninput').value = mysave;
    //     window.alert(mysave);
         var els=document.getElementsByName("hiddeninput");
		for (var i=0;i<els.length;i++) {
		els[i].value = mysave;}
         
}
</script>

 </body>
</html>
