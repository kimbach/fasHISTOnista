<?php
echo "<html>";
echo "<head>";
echo '	<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
echo '	<link href="fashistorista.css" rel="stylesheet" />';
echo "</head>";
echo "<body>";
?>
<p>
<a href="https://github.com/kimbach/fasHISTOnista.git">Fork me on GitHub!</a>
</p>
<?php

    global $score;

    $score =  intval($_POST["score"]);

    if(empty($_POST["note"]))
    {
    }
    if(!(empty($_POST["note"])))
    {
        //echo $_POST["note"];
        //echo "<script>alert(" . $_POST["note"] . ")</script>";
    }


    if(empty($_POST["aarti"]))
    {
    }
    
    if(!(empty($_POST["aarti"])))
    {
    	// aarti correct?
    	$aarti = intval(intval($_POST["note"]) % 100 / 10) * 10;
    	if (intval($_POST["aarti"]) == intval(intval($_POST["note"]) % 100 / 10) * 10)
    	{
        	echo "<script>alert('Rigtigt årti - tillykke')</script>";
    		//if(!(empty($_POST["score"])))
    		{
    			$score =  $score + 1;
        		//echo "<script>alert($score)</script>";
        	}
        }
        else
        	echo "<script>alert('Forkert årti - det var ' + $aarti + 'erne')</script>";
        	//echo "<script>alert('Forkert årti - det var ' . $aarti . ')' . </script>";
    }

    
    if(!(empty($_POST["aarstal"])))
    {
    	// aarstal correct?
    	if (intval($_POST["aarstal"]) == intval($_POST["note"]))
        	echo "<script>alert('Rigtigt årstal - tillykke')</script>";
        else
        	echo "<script>alert('Forkert årstal - prøv igen')</script>";
    }

// User-Agent string from Chrome. I haven't tested anything else so I don't know
// what is actually required, but this works.
$context = stream_context_create(array(
  'http'=>array(
    'user_agent' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11'
   )
));

// Get data as a string
$xml2 = file_get_contents('dallevallekataloger.xml', FALSE, $context);
//echo $xml2;

// Convert string to a SimpleXML object
$xml2 = simplexml_load_string($xml2);
$item = $xml2->channel->item;
$catalogues = array();
foreach ($xml2->channel->item as $item) {

    //echo '<h2>' . $item->title . '</h2>';
    //echo '<p>' . $item->link . '</p>';
  
  	//Use that namespace
  $namespaces = $item->getNameSpaces(true);
  $md = $item->children($namespaces['md']); 
    //echo '<p>' . $md->mods->note . '</p>';
    
    // Put it into an array
	//echo $md->mods->identifier;
	$identifiers = array();
	foreach ($md->mods->identifier as $identifier) {
	//echo $identifier . "<br/>";
	}

	foreach ($md->mods->relatedItem as $relatedItem) {
	$filename = preg_replace("/\\.[^.\\s]{3,4}$/", "", "http://www.kb.dk/imageService/w600/h600/" . $relatedItem->identifier) . ".jpg"; 
	//echo $filename . "<br/>";
	//$identifiers[] = $filename;
	foreach ($relatedItem->relatedItem as $relatedItem) {
	$filename = preg_replace("/\\.[^.\\s]{3,4}$/", "", "http://www.kb.dk/imageService/w600/h600/" . $relatedItem->identifier) . ".jpg"; 
	//echo $filename . "<br/>";
	$identifiers[] = $filename;
	}
	}
	$catalogue = array('title' => $item->title, 'link' => $item->link, 'note' => $md->mods->note, 'identifiers' => $identifiers);
	$catalogues[] = $catalogue;

	//print_r($catalogues);
}

	// select random index
	$random=array_rand($catalogues, 1);
	//echo "<br/>" . $random . "<br/>";
	//echo $catalogues[$random][title] . "<br/>";
	
	foreach ($catalogues[$random][identifiers] as $identifier) {
		//echo($identifier) . "<br/>";
	}

	// select random image
	$randomimage=array_rand($catalogues[$random][identifiers], 1);
	//echo "<br/>" . $randomimage . "<br/>";
	//echo '<img src="' . $catalogues[$random][identifiers][$randomimage] . '"/>' . "<br/>";
	
	// random decade
	//echo '<script>alert(' . intval($catalogues[$random][note]) . ')</script>';
	//echo '<script>alert(' . intval(((intval($catalogues[$random][note]))) % 100 / 10) * 10 . ')</script>';
	$decadeCorrect = intval(((intval($catalogues[$random][note]))) % 100 / 10) * 10;
	$decade1 = rand (0 , 9) * 10;
	$decade2 = rand (0 , 9) * 10;
	$decadePOS = rand (1 , 3);
	
	switch ($decadePOS)
	{
		case 1:
			$decade1 = $decadeCorrect;
			$decade2 = rand (1 , 9) * 10;
			while ($decade2 == $decade1)
			{
				$decade2 = rand (1 , 9) * 10;
			}
			$decade3 = rand (1 , 9) * 10;
			while (($decade3 == $decade1) && ($decade3 == $decade2))
			{
				$decade3 = rand (1 , 9) * 10;
			}
			break;
		case 2:
			$decade2 = $decadeCorrect;
			$decade1 = rand (1 , 9) * 10;
			while ($decade2 == $decade1)
			{
				$decade1 = rand (1 , 9) * 10;
			}
			$decade3 = rand (1 , 9) * 10;
			while (($decade3 == $decade1) && ($decade3 == $decade2))
			{
				$decade3 = rand (1 , 9) * 10;
			}
			break;
		case 3:
			$decade3 = $decadeCorrect;
			$decade1 = rand (1 , 9) * 10;
			while ($decade3 == $decade1)
			{
				$decade1 = rand (1 , 9) * 10;
			}
			$decade2 = rand (1 , 9) * 10;
			while (($decade3 == $decade1) && ($decade3 == $decade2))
			{
				$decade2 = rand (1 , 9) * 10;
			}
			break;
	}

	// random year
	$yearCorrect = intval($catalogues[$random][note]);
	$year1 = rand (1912, 1998);
	$year2 = rand (1912, 1998);
	$yearPOS = rand (1, 3);
	switch ($yearPOS)
	{
		case 1:
			$year1 = $yearCorrect;
			$year2 = rand (1912, 1998);
			while ($year2 == $year1)
			{
				$year2 = rand (1912, 1998);
			}
			$year3 = rand (1912, 1998);
			while (($year3 == $year1) && ($year3 == $year2))
			{
				$year3 = rand (1912, 1998);
			}
			break;
		case 2:
			$year2 = $yearCorrect;
			$year1 = rand (1912, 1998);
			while ($year2 == $year1)
			{
				$year1 = rand (1912, 1998);
			}
			$year3 = rand (1912, 1998);
			while (($year3 == $year1) && ($year3 == $year2))
			{
				$year3 = rand (1912, 1998);
			}
			break;
		case 3:
			$year3 = $yearCorrect;
			$year1 = rand (1912, 1998);
			while ($year3 == $year1)
			{
				$year1 = rand (1912, 1998);
			}
			$year2 = rand (1912, 1998);
			while (($year3 == $year1) && ($year3 == $year2))
			{
				$year2 = rand (1912, 1998);
			}
			break;
	}


echo '<div id="box">';
//echo '<img src="https://www.evernote.com/shard/s5/sh/14e435de-d710-47f9-beff-0809ad7950ef/7baf3ecce90c36fccb405c8a323f30cc" />';
echo '<img src="Fashistonista.png" />';
//echo '<h1>Fas<span class="double">histo</span>nista</h1>';
echo '<p class="tagline">Hvornår var det nu det var?"</p>';
echo '<div>';
echo '<iframe allowtransparency="true" frameborder="0" scrolling="no"';
echo '        src="http://platform.twitter.com/widgets/tweet_button.html?text=Leg%20med%20Daells%20Varehus%20Kataloger.%20Et%20%23HACK4DK%20projekt"';
echo '        style="width:130px; height:35px;"></iframe>';
echo '<iframe src="http://www.facebook.com/plugins/like.php?href=http://hack4dk.kimbach.org&send=false&layout=standard&width=450&show_faces=false&action=like&colorscheme=light&font&height=35" scrolling="no" frameborder="0"'; echo 'style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>';
echo '</div>';

echo '<img src="' . $catalogues[$random][identifiers][$randomimage] . '" id="image" alt="Daells Varehus Katalog billede" align="center"  height=600px width=600px />';
//echo '<img src="' . $catalogues[$random][identifiers][$randomimage] . '" id="image" alt="Daells Varehus Katalog billede" height=600px width=600px />';
//echo '<img src="" id="image" alt="Daells Varehus Katalog billede" height=600px width=600px />';
echo '</div>';
echo '<div>';
echo '<form id="selector" action="index.php" method="post">';
echo '<fieldset name="aarti">';
echo '<input type="hidden" name="note" value="' . $catalogues[$random][note] . '">';
echo '<label id="aarti">Årti:</label><input name="aarti" id="aarti1" type="radio" class="radio" value="'  . $decade1 . '" />'. "&nbsp;" . $decade1 . 'erne <br/>';
echo '<input name="aarti" id="aarti2" class="radio"  value="' . $decade2 . '" type="radio" />' . "&nbsp;" . $decade2 . 'erne <br/>';
echo '<input name="aarti" id="aarti3" class="radio"  type="radio" value="' . $decade3 . '" />' . "&nbsp;" . $decade3 . 'erne <br/>';

/*echo '<label id="aarstal">Årstal:</label><input name="aarstal" id="aarstal1" class="radio" type="radio" value="' . $year1 . '"/>' . $year1;
echo '<input name="aarstal" id="aarstal2" class="radio"  type="radio"  value="' . $year2 . '"/>' . $year2;
echo '<input name="aarstal" id="aarstal3" class="radio" type="radio" value="' . $year3 . '" />' . $year3;

echo '<label id="saeson">Sæson:</label><input name="saeson1" id="saeson" type="radio" class="radio"  />Sæson 1';
echo '<input name="saeson" name="saeson2" type="radio" class="radio"   />Sæson 2';
echo '<input name="saeson" name="saeson3" class="radio" type="radio" />Sæson 3';*/
echo '<label name="score" value="' . $score . '">';
//echo 'Score:&nbsp;<input type="hidden" name="hiddenscore" value="0">;

echo '<input id="sendind" type="submit" value="Gæt" >';
echo '</fieldset>';
echo '</form>';
echo '</div>';
echo "</body>";
echo "</html>";

?>
