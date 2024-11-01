<?php
	//this simply grabs the HTML from the site and brings it back to be parsed.
	// can't do cross-domains easily in JavaScript...
	
	require_once('simple_html_dom.php');
	
	function do_reg($text, $regex)
	{
		if (preg_match($regex, $text, $regs)) {
			$result = $regs[0];
		} 
		else {
			$result = "";
		}
		return $result;
	}
	
	class wowChar
	{
		
		var $name		= '';
		var $title		= '';
		var $guild		= '';
		var $level		= 0;
		var $race		= '';
		var $class		= '';
		var $spec		= '';
		var $apoints	= 0;
		var $card		= '';
		var $realm		= '';
		var $side		= 'A';
		var $color		= 'color-c1';
		
		function grabData($url)
		{
			$html = file_get_html($url);
			if (preg_match("/\/static-render\/us\/([A-z0-9-\/\.\?\=]+)\"/", $html->find('style',0)->innertext, $matches))
			{
				$this->card = "http://us.battle.net" . preg_replace("/\/race/","",preg_replace("/(profilemain)/", "avatar", $matches[0]));
			}
			
			if(isset($html->find('div.profile-wrapper-horde',0)->outertext))
			{
				$this->side = "H";
			}
			else if(isset($html->find('div.profile-wrapper-alliance',0)->outertext))
			{
				$this->side = "A";
			}
			
			$cBlock = $html->find('div.profile-info',0);
			$this->name = $cBlock->find('div.name',0)->plaintext;
			$this->title = $cBlock->find('div.title',0)->plaintext;
			if(isset($cBlock->find('div.guild',0)->outertext))
				$this->guild = $cBlock->find('div.guild',0)->plaintext;
			else
				$this->guild = '';
			$this->level = $cBlock->find('span.level',0)->plaintext;
			$this->race = $cBlock->find('a.race',0)->plaintext;
			if(isset($cBlock->find('div.spec',0)->outertext))
				$this->spec = $cBlock->find('a.spec',0)->plaintext;
			else
				$this->spec = '';			
			$this->class = $cBlock->find('a.class',0)->plaintext;
			$this->apoints = $cBlock->find('div.achievements',0)->plaintext;
			$this->realm = $cBlock->find('span.realm',0)->plaintext;
			$this->color = $cBlock->find("div[class=under-name]",0)->class;
			
			return array(
				'name' 		=> trim($this->name),
				'title' 	=> trim($this->title),
				'guild' 	=> trim($this->guild),
				'level' 	=> trim($this->level),
				'race' 		=> trim($this->race),
				'spec' 		=> trim($this->spec),
				'class' 	=> trim($this->class),
				'apoints' 	=> trim($this->apoints),
				'card' 		=> trim($this->card),
				'realm'		=> trim($this->realm),
				'side'		=> trim($this->side),
				'color'		=> trim($this->color)
				
			);
				
		}
		
	}
	
	function customError($errno, $errstr, $errfile, $errline)
	{
		echo json_encode(array('error'=>"<b>Error:</b> [$errno] $errstr on $errfile line $errline"));
	}
	set_error_handler("customError");
		
	
	if((!isset($_POST['url']) || $_POST['url'] == '') && !isset($_GET['debug'])) 
	{
		echo "Must supply an URL";
	}
	else
	{		
		if(isset($_GET['debug']))
		{
			$url = $_GET['url'];
		}
		else
		{
			$url = $_POST['url'];
		}
			
		$wc = new wowChar;
		echo json_encode($wc->grabData($url));
		
	}
?>