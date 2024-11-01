<?php
/*****************************************************************

	File name: browser.php
	Author: Gary White
	Last modified: November 10, 2003
	
	Author: Reza Moallemi
	Last modified: April 27, 2011

	**************************************************************

	Copyright (C) 2003  Gary White
	Copyright (C) 2011  Reza Moallemi

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details at:
	http://www.gnu.org/copyleft/gpl.html

	**************************************************************

	Browzer class

	Identifies the user's Operating system, browser and version
	by parsing the HTTP_USER_AGENT string sent to the server

	Typical Usage:

		require_once($_SERVER['DOCUMENT_ROOT'].'/include/browser.php');
		$br = new Browzer;
		echo "$br->Platform, $br->Name version $br->Version";

	For operating systems, it will correctly identify:
		Microsoft Windows
		MacIntosh
		Linux

	Anything not determined to be one of the above is considered to by Unix
	because most Unix based browsers seem to not report the operating system.
	The only known problem here is that, if a HTTP_USER_AGENT string does not
	contain the operating system, it will be identified as Unix. For unknown
	browsers, this may not be correct.

	For browsers, it should correctly identify all versions of:
		Amaya
		Galeon
		iCab
		Internet Explorer
			For AOL versions it will identify as Internet Explorer (AOL) and the version
			will be the AOL version instead of the IE version.
		Konqueror
		Lynx
		Mozilla
		Netscape Navigator/Communicator
		OmniWeb
		Opera
		Pocket Internet Explorer for handhelds
		Safari
		WebTV
*****************************************************************/

	class Browser{

		public $Name = "Unknown";
		public  $Version = "";
		public $Platform = "Unknown";
		public $Pver = "";
		public $Agent = "Not reported";
		public $AOL = false;
		public $Image = "";
		public $Architecture = "";

		public function Browser($agent){
		

			// initialize properties
			$bd['platform'] = "Unknown";
			$bd['pver'] = "";
			$bd['browser'] = "Unknown";
			$bd['version'] = "";
			$this->Agent = $agent;

			// find operating system
			if (stripos($agent,'win'))
			{
				$bd['platform'] = "Windows";
				if(stripos($agent,'NT 6.1'))
					$val = '7';
				elseif(stripos($agent,'NT 6.0'))
					$val = 'Vista';
				elseif(stripos($agent,'NT 5.2'))
					$val = 'XP 64-bit/Server 2003';
				elseif(stripos($agent,'NT 5.1'))
					$val = 'XP';
				elseif(stripos($agent,'NT 5.01'))
					$val = '2000 SP1';
				elseif(stripos($agent,'NT 5.0'))
					$val = '2000';
				$bd['pver'] = $val;
			}
			
			elseif(preg_match('/iPad/i', $agent)){
				$bd['browser']= 'Safari';
				$bd['platform']="iPad";
				if(preg_match('/CPU\ OS\ ([._0-9a-zA-Z]+)/i', $agent, $regmatch))
					$bd['pver']=" iOS ".str_replace("_", ".", $regmatch[1]);
			}elseif(preg_match('/iPod/i', $agent)){
				$bd['browser']= 'Safari';
				$bd['platform']="iPod";
				if(preg_match('/iPhone\ OS\ ([._0-9a-zA-Z]+)/i', $agent, $regmatch))
					$bd['pver']=" iOS ".str_replace("_", ".", $regmatch[1]);
			}elseif(preg_match('/iPhone/i', $agent)){
				$bd['browser']= 'Safari';
				$bd['platform']="iPhone";
				if(preg_match('/iPhone\ OS\ ([._0-9a-zA-Z]+)/i', $agent, $regmatch))
					$bd['pver']=" iOS ".str_replace("_", ".", $regmatch[1]);
			}
			
			elseif (stripos($agent,'mac'))
				$bd['platform'] = "MacIntosh";
			elseif (stripos($agent,'linux'))
				$bd['platform'] = "Linux";
			elseif (stripos($agent,'OS/2'))
				$bd['platform'] = "OS2";
			elseif (stripos($agent,'BeOS'))
				$bd['platform'] = "BeOS";
			elseif (stripos($agent,'j2me'))
				$bd['platform'] = 'Java';
			elseif (stripos($agent,'wordpress'))
				$bd['platform'] = 'XML-RPC';
			elseif (stripos($agent,'Snoopy') === 0)
				$bd['platform'] = 'XML-RPC';
			elseif (stripos($agent,'Incutio'))
				$bd['platform'] = 'XML-RPC';
			// test for Opera        
			if (stripos($agent,'opera') === 0){
				if(stristr($agent,'opera mini')){ // test for Opera Mini
					$bd['browser'] = "Opera Mini";
					$val = explode('Mini',$agent);
					$val = explode('.',$val[1]);
					$bd['version'] = $val[0].'.'.$val[1];
					}else{
					if(stripos($agent,'version/1')){ // test for Opera > 9
					$val = stristr($agent, "version/1");
					$val = explode("/",$val);
					$bd['browser'] = 'Opera';
					$bd['version'] = $val[1];
					}else{
				$val = stristr($agent, "opera");
					$val = explode("/",$val);
					$bd['browser'] = $val[0];
					$val = explode(" ",$val[1]);
					$bd['version'] = $val[0];
					}
				}
			}elseif(stripos($agent,'k-meleon')){// test for K-Meleon
				$bd['browser'] = 'K-Meleon';
				$val = explode('K-Meleon',$agent);
				$bd['version'] = $val[1];
			}elseif(stripos($agent,'shiira')){// test for Shiira
				$bd['browser'] = 'Shiira';
				$val = explode('Shiira',$agent);
				$val = explode(" ",$val[1]);
				$bd['version'] = $val[0];
			}elseif(stripos($agent,'galeon')){// test for Galoen
				$bd['browser'] = "Galeon";
				$val = explode('Galeon',$agent);
				$val = explode(" ",$val[1]);
				$bd['version'] = $val[0];
			}elseif(stripos($agent,'epiphany')){// test for Epiphany
				$bd['browser'] = 'Epiphany';
				$val = explode('Epiphany',$agent);
				$val = explode(" ",$val[1]);
				$bd['version'] = $val[0];
			}elseif(stripos($agent,'camino')){// test for Camino
				$bd['browser'] = 'Camino';
				$val = explode('Camino',$agent);
				$val = explode(' ',$val[1]);
				$bd['version'] = $val[0];
			}elseif(stripos($agent,'avant')){// test for Avant Browser
				$bd['browser'] = 'Avant';
				$bd['version'] = 'Browser';
			}elseif(stripos($agent,'maxthon')){// test for Maxthon
			$bd['browser'] = 'Maxthon';
			$val = explode('MAXTHON',$agent);
			$val = explode(";",$val[1]);
			$bd['version'] = $val[0];
		}elseif(stripos($agent,'Flock')){// test  for Flock
				$bd['browser'] = 'Flock';
				$val = explode('Flock',$agent);
				$bd['version'] = $val[1];
			}
			elseif(stripos($agent,'lunascape')){ //test for Lunascape
				$bd['browser'] = 'Lunascape';
				$val = explode("lunascape",strtolower($agent));
				$bd['version'] = $val[1];
			}elseif(stripos($agent,'konqueror')){ // test for Konqueror
				$bd['browser'] = "Konqueror";
				$val = explode('Konqueror',$agent);
				$val = explode(";",$val[1]);
				$bd['version'] = $val[0];
			}elseif(stripos($agent,'orca')){ // test for Orca
				$bd['browser'] = 'Orca';
				$val = explode('Orca',$agent);
				$bd['version'] = $val[1];
			}elseif(stripos($agent,'webtv')){// test for WebTV
				$val = explode("/",stristr($agent,"webtv"));
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
			
			// test for MS Internet Explorer version 1
			}elseif(stripos($agent,'microsoft internet explorer')){
				$bd['browser'] = "IE";
				$bd['version'] = "1.0";
				$var = stristr($agent, "/");
				if (ereg("308|425|426|474|0b1", $var)){
					$bd['version'] = "1.5";
				}

			// test for NetPositive
			}elseif(stripos($agent,'NetPositive')){
				$val = explode("/",stristr($agent,"NetPositive"));
				$bd['platform'] = "BeOS";
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];

			// test for MS Internet Explorer
			}elseif(stripos($agent,'msie') && !stripos($agent,'opera')){
				$val = explode(" ",stristr($agent,"msie"));
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
			
			// test for MS Pocket Internet Explorer
			}elseif(stripos($agent,'mspie') || stripos($agent,'pocket')){
				$val = explode(" ",stristr($agent,"mspie"));
				$bd['browser'] = "MSPIE";
				$bd['platform'] = "WindowsCE";
				if (stripos($agent,'mspie'))
					$bd['version'] = $val[1];
				else {
					$val = explode('/',$agent);
					$bd['version'] = $val[1];
				}
				
			// test for Galeon
			}elseif(stripos($agent,'galeon')){
				$val = explode(" ",stristr($agent,"galeon"));
				$val = explode("/",$val[0]);
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
				
			// test for Konqueror
			}elseif(stripos($agent,'Konqueror')){
				$val = explode(" ",stristr($agent,"Konqueror"));
				$val = explode("/",$val[0]);
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
				
			// test for iCab
			}elseif(stripos($agent,'icab')){
				$val = explode(" ",stristr($agent,"icab"));
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];

			// test for OmniWeb
			}elseif(stripos($agent,'omniweb')){
				$val = explode("/",stristr($agent,"omniweb"));
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
			}elseif(stripos($agent,'chrome')){// test for Google Chrome and Chromium
				if(stripos($agent,'linux')){
				$bd['browser'] = 'Chromium';
				}else{
				$bd['browser'] = "Chrome";
				}
				$val = explode('Chrome',$agent);
				$val = explode(" ",$val[1]);
				$bd['version'] = $val[0];
			}elseif(stripos($agent,'Phoenix')){// test for Phoenix
				$bd['browser'] = "Phoenix";
				$val = explode("/", stristr($agent,"Phoenix/"));
				$bd['version'] = $val[1];
			
			// test for Firebird
			}elseif(stripos($agent,'firebird')){
				$bd['browser']="Firebird";
				$val = stristr($agent, "Firebird");
				$val = explode("/",$val);
				$bd['version'] = $val[1];
				
			// test for Firefox
			}elseif(stripos($agent,'Firefox')){
				$bd['browser']="Firefox";
				$val = stristr($agent, "Firefox");
				$val = explode("/",$val);
				$bd['version'] = $val[1];
				
		  // test for Mozilla Alpha/Beta Versions
			}elseif(stripos($agent,'mozilla') && 
				stripos($agent,'rv:[0-9].[0-9][a-b]') && !stripos($agent,'netscape')){
				$bd['browser'] = "Mozilla";
				$val = explode(" ",stristr($agent,"rv:"));
				stripos($agent,'rv:[0-9].[0-9][a-b]',$val);
				$bd['version'] = str_replace("rv:","",$val[0]);
				
			// test for Mozilla Stable Versions
			}elseif(stripos($agent,'mozilla') &&
				stripos($agent,'rv:[0-9]\.[0-9]') && !stripos($agent,'netscape')){
				$bd['browser'] = "Mozilla";
				$val = explode(" ",stristr($agent,"rv:"));
				stripos($agent,'rv:[0-9]\.[0-9]\.[0-9]',$val);
				$bd['version'] = str_replace("rv:","",$val[0]);
			
			// test for Lynx & Amaya
			}elseif(stripos($agent,'libwww')){
				if (stripos($agent,'amaya')){
					$val = explode("/",stristr($agent,"amaya"));
					$bd['browser'] = "Amaya";
					$val = explode(" ", $val[1]);
					$bd['version'] = $val[0];
				} else {
					$val = explode('/',$agent);
					$bd['browser'] = "Lynx";
					$bd['version'] = $val[1];
				}
			
			// test for Safari
			}elseif(stripos($agent,'safari')){
				$bd['browser'] = "Safari";
				$bd['version'] = "";

			// remaining two tests are for Netscape
			}elseif(stripos($agent,'netscape')){
				$val = explode(" ",stristr($agent,"netscape"));
				$val = explode("/",$val[0]);
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
			}elseif(stripos($agent,'mozilla') && !stripos($agent,'rv:[0-9]\.[0-9]\.[0-9]')){
				$val = explode(" ",stristr($agent,"mozilla"));
				$val = explode("/",$val[0]);
				$bd['browser'] = "Netscape";
				$bd['version'] = $val[1];
			}
			
			// clean up extraneous garbage that may be in the name
			$bd['browser'] = ereg_replace("[^a-z,A-Z,-]", "", $bd['browser']);
			// clean up extraneous garbage that may be in the version        
			$bd['version'] = ereg_replace("[^0-9,.,a-z,A-Z]", "", $bd['version']);
			
			// check for AOL
			if (stripos($agent,'AOL')){
				$var = stristr($agent, "AOL");
				$var = explode(" ", $var);
				$bd['aol'] = ereg_replace("[^0-9,.,a-z,A-Z]", "", $var[1]);
			}
			
			if (stripos($agent,'wordpress'))
			{
				$val = stristr($agent, "wordpress");
				$val = explode("/",$val);
				$var = explode(" ", $var);
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
			}
			
			if (stripos($agent,'Snoopy') === 0)
			{
				$val = stristr($agent, "Snoopy");
				$val = explode("v",$val);
				$var = explode(" ", $var);
				$bd['browser'] = $val[0];
				$bd['version'] = $val[1];
			}
			
			
			if(stripos($agent,'fedora'))
			{
				$val = explode(" ",stristr($agent,"fc"));
				$val = explode("fc",$val[0]);
				$bd['platform'] = 'Fedora '.$val[0];
				$bd['pver'] = $val[1];
			}
				
			if(stripos($agent,'ubuntu'))
			{
				$val = explode(" ",stristr($agent,"Ubuntu"));
				$val = explode("/",$val[0]);
				$bd['platform'] = $val[0];
				$bd['pver'] = $val[1];
			}
			
			if(stripos($agent,'gentoo'))
				$bd['platform'] = 'Gentoo';
				
			if(stripos($agent,'mint')) {
				$val = explode(" ",stristr($agent,"mint"));
				$val = explode("/",$val[0]);
				$bd['platform'] = 'Linux '.$val[0];
				$bd['pver'] = $val[1];
			}
			
			if(stripos($agent,'x86_64')) {
				$bd['architecture'] = "x86_64";
			}
			
			if(stripos($agent,'Android')) {
				$val = explode(" ",stristr($agent,"android"));
				$bd['platform'] = $val[0];
				$bd['pver'] = $val[1];
			}
			
			// finally assign our properties
			$this->Name = $bd['browser'];
			$this->Version = $bd['version'];
			$this->Platform = $bd['platform'];
			$this->Pver = $bd['pver'];
			$this->AOL = $bd['aol'];
			$this->Architecture = $bd['architecture'];

			
			$this->BrowserImage = strtolower($this->Name);
			if($this->BrowserImage == "msie")
				$this->BrowserImage .=  '-'.$this->Version;
			elseif(stripos($this->BrowserImage, "snoopy") === 0)
				$this->BrowserImage = 'other';
				
			$this->PlatformImage = strtolower($this->Platform);
			
			if($this->PlatformImage == "linux mint")
				$this->PlatformImage = "linux-mint";
			if($this->PlatformImage == "fedora ")
				$this->PlatformImage = "fedora";	
			if($this->PlatformImage == "windows" and ($this->Pver == 'Vista' or $this->Pver == '7'))
				$this->PlatformImage .=  '-'.strtolower($this->Pver);
			
		}
	}
?>
