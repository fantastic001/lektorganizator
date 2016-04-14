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
if (isset($_GET['lektor']) && $_GET['hiddeninput'] !== "" ) {

$lektor = $_GET['lektor'];
$primalac = explode(" ",$lektor);
$to = $primalac[2];
$ime = str_replace("_", " ", $primalac[0]);
$tekst = $_GET['tekst'];
$naslov = $_GET['naslov'];
$tekst2 = $tekst;
if (substr($tekst2, -1) == " ") {
	$tekst2 = substr($tekst2, 0, -1);
	$tekst2 .= "+";
}
//$tekst = str_replace(" ", "+", $tekst);
$pravoime = $primalac[1];
//$pravoime = $pravoime[1];

$link = "https://libre.lugons.org/wiki/doku.php?id=wiki:" . $tekst2;
$rok = $_GET['hiddeninput'];

$subject = 'Lektorisanje';
$message = '<b>Drag' . $ime . ",</b><br> <br> Tekst za tebe za ovaj broj: " . $link . " — ". $naslov ."<br> <br> Rok: " . $rok . ". <br> <br><b>Molim te, javi mi ako ti rok ne odgovara.</b> <br> <br> Pozdrav!";
$headers = 'From: nikibozinovic@gmail.com' . "\r\n" .
    'Reply-To: nikibozinovic@gmail.com' . "\r\n" .
    'Content-type: text/html; charset=utf-8' . "\r\n";
    
//$result = mail($to, $subject, $message, $headers);

//Poslato ili nije.
//var_Export($result);

$mejl = $_GET['mejl'];
echo "<form action=sendmail.php>".
	"Pošiljalac: " . base64_decode($mejl)
/*<select name='taskOption'>
  <option disabled selected value> -- odabir -- </option>
  <option value='admir'>Admir</option>
  <option value='aleksandra'>Aleksandra Ristović</option>
  <option value='jelena'>Jelena Munćan</option>
  <option value='milana'>Milana Vojnović</option>
  <option value='saska'>Saška Spišjak</option>
  <option value='i_Aleksandre bozinovic.aleksandar@gmail.com'>Aleksandar</option>
</select>
	<br>*/
.	"<br>Primalac:  " . $to .
"<br>
	<input type='hidden' name='primalac' value='$to' style='display: none;'>
	<input type='hidden' name='posiljalac' value='$mejl' style='display: none;'>
	<input type='hidden' name='rok' value='$rok' style='display: none;'>
	<input type='hidden' name='kolektor' value='$pravoime' style='display: none;'>
	<input type='hidden' name='tekst' value='$tekst' style='display: none;'>
	Tekst:
<div id='textBox' name='textBox' contentEditable='true' name='poruka' style='border:2px solid red;' >" . $message. "</div> <br>
<textarea id='hiddeninput' name='hiddeninput' style='display: none;'></textarea>

<input type='submit' id='save' name='save' value='Pošalji pismo'>
</form>";


//$result = mail($to, $subject, $poruka, $headers);

// Sending email
} elseif (isset($_GET['primalac'])) {
	$posiljalac = $_GET['posiljalac'];
	$posiljalac = base64_decode($posiljalac);
	$to = $_GET['primalac'];
	$subject = "Lektorisanje";
	$poruka = $_GET['hiddeninput'];
	$headers = 'From: '. $posiljalac . "\r\n" .
    'Reply-To: ' . $posiljalac . "\r\n" .
    'Content-type: text/html; charset=utf-8' . "\r\n";
    $rok = $_GET['rok'];
    $lektor = $_GET['kolektor'];
    $tekst = $_GET['tekst'];
    //echo $tekst;
  //  $cuvanje = $tekst . "," . $lektor . "," . $rok;
   
    $cuvanje = array($tekst, $lektor, $rok);
	
	if(mail($to, $subject, $poruka, $headers)) {

    echo 'Pismo je uspešno poslato. <br>';
    
    $fileName = 'prihvaceno';
    if ( file_exists($fileName) && ($handle = fopen($fileName, "a"))!==false ) {
		
		//file_put_contents($fileName, $cuvanje, FILE_APPEND);
		fputcsv($handle, $cuvanje);
        fclose($handle);

      echo    'Podaci su ubeleženi u bazu. <br>';
    }
    else
    {
echo    'Greška prilikom čuvanja podataka. Fajl "prihvaceno" ili ne postoji, ili mu je nemoguće pristupiti. <br>';
    }
    
    
  /*  $cuvanje = array($tekst, $to, $rok);
    $handle = fopen("prihvaceno", "a");
    fputcsv($handle, $cuvanje);
	fclose($handle);*/
} else{

    echo 'Greška: pismo nije poslato. Za više informacija proveriti /var/log/mail.log';
   }
	} else {
	echo "Neka od sledećih stavki nije odabrana: <br> - lektor, <br> - rok. <br> Možda nijedna nije podešena.";
}
	
?>

<script type="text/javascript">
$(function(){
    $('#save').click(function () {
        var mysave = $('#textBox').html();
        $('#hiddeninput').val(mysave);
    });
});
</script>

 </body>
</html>
