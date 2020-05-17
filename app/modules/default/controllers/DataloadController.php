<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110614
 * Desc:		This controller handle the vehicle loading process
 *********************************************************************************/
include_once('classes/AbstractController.php');
include_once('classes/DB.php');
class DataloadController extends AbstractController{
	
	public function preDispatch(){
		parent::preDispatch();	
		
		$this -> getFrontController() -> setParam('noViewRenderer', true);
		
		//$this -> view -> tmpl = $this->tmpl;
		$this -> view -> lang = $this -> lang;	
	}
	
	public function indexAction(){
		$letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$pref = '';
		$ret = '';
		$intCount = 0;
		for($i = 0; $i < 121; $i++){
			$mod = $i%26;
			if (($mod == 0) && ($i  > 0)){
				if (isset($letter[$intCount])){			
					$pref = $letter[$intCount++];
				}	
			}
			$ret .= '$ret[\''.$pref.$letter[$mod].'\'] = \'\';<br/>';
			/*
			$ret .= '//'.$i.'<br/>
					if(isset($p['.$i.']) && ($p['.$i.'] != null) && ($fIsEmpty -> filter($p['.$i.']) == false)){<br/>
					&nbsp;&nbsp;$ret[\''.$pref.$letter[$mod].'\'] = $p['.$i.'];<br>
					}else{<br/>
					&nbsp;&nbsp;$ret[\''.$pref.$letter[$mod].'\'] = null;<br>
					}<br/><br/>';
			*/
			/*
			$ret .= '//'.$i.'<br/>
					if(isset($p[\''.$pref.$letter[$mod].'\']) && ($p[\''.$pref.$letter[$mod].'\'] != null)){<br/>
					&nbsp;&nbsp;$query1 .= \', '.$pref.$letter[$mod].'\';<br/>
					&nbsp;&nbsp;$query2 .= \', "\'.$db -> escape($p[\''.$pref.$letter[$mod].'\']).\'"\';<br/>
					}<br/><br/>';
			
			
			$ret .= '//'.($i).'<br/>
					if(isset($pSet[\''.$pref.$letter[$mod].'\']) && ($pSet[\''.$pref.$letter[$mod].'\'] != null)){<br/>
					&nbsp;&nbsp;$query .= \', '.$pref.$letter[$mod].' = "\'.$db -> escape($pSet[\''.$pref.$letter[$mod].'\']).\'"\';<br/>
					}<br/><br/>';
			*/
			
			
			//$ret .= ', "'.$pref.$letter[$mod].'"<br/>';
			//$ret .= ', "\'.$db -> escape($p["'.$pref.$letter[$mod].'"]).\'"<br/>';
			//$ret .= $pref.$letter[$mod].' = '.($i+1).'<br/>';		 			
		}
		echo $ret;
	}
	
	/**
	 * Handle the vehicle loading process for mobile.de formats
	 */
	private function mobileloadAction(){
		$p = $this -> getRequest() -> getParams();
		if ($p['file']){
			$filePath = $p['file'];
			$fileName = basename($filePath);
			$fileHandler = fopen($filePath, 'r');
			while($line = fgetcsv($fileHandler) != false){
				$dataFields = explode(';', $line);
				
				//transform input file
				$dataFields = $this -> transformMobileAction($dataFields);
				if ($dataFields != false){
					
				}
			}
		}
	}
	
	private function transformMobileAction($p){
		$ret = false;		
		//contains the exact number of fields in each line
		$numFields = 159;
		if(count($p == $numFields)){			
			$ret['A'] = $p[0];
			$ret['B'] = $p[1];
			$ret['C'] = $p[2];
			$ret['D'] = $p[3];
			$ret['E'] = $p[4];
			$ret['F'] = $p[5];
			$ret['G'] = $p[6];
			$ret['H'] = $p[7];
			$ret['I'] = $p[8];
			$ret['J'] = $p[9];
			$ret['K'] = $p[10];
			$ret['L'] = $p[11];
			$ret['M'] = $p[12];
			$ret['N'] = $p[13];
			$ret['O'] = $p[14];
			$ret['P'] = $p[15];
			$ret['Q'] = $p[16];
			$ret['R'] = $p[17];
			$ret['S'] = $p[18];
			$ret['T'] = $p[19];
			$ret['U'] = $p[20];
			$ret['V'] = $p[21];
			$ret['W'] = $p[22];
			$ret['X'] = $p[23];
			$ret['Y'] = $p[24];
			$ret['Z'] = $p[25];
			$ret['AA'] = $p[26];
			$ret['AB'] = $p[27];
			$ret['AC'] = $p[28];
			$ret['AD'] = $p[29];
			$ret['AE'] = $p[30];
			$ret['AF'] = $p[31];
			$ret['AG'] = $p[32];
			$ret['AH'] = $p[33];
			$ret['AI'] = $p[34];
			$ret['AJ'] = $p[35];
			$ret['AK'] = $p[36];
			$ret['AL'] = $p[37];
			$ret['AM'] = $p[38];
			$ret['AN'] = $p[39];
			$ret['AO'] = $p[40];
			$ret['AP'] = $p[41];
			$ret['AQ'] = $p[42];
			$ret['AR'] = $p[43];
			$ret['AS'] = $p[44];
			$ret['AT'] = $p[45];
			$ret['AU'] = $p[46];
			$ret['AV'] = $p[47];
			$ret['AW'] = $p[48];
			$ret['AX'] = $p[49];
			$ret['AY'] = $p[50];
			$ret['AZ'] = $p[51];
			$ret['BA'] = $p[52];
			$ret['BB'] = $p[53];
			$ret['BC'] = $p[54];
			$ret['BD'] = $p[55];
			$ret['BE'] = $p[56];
			$ret['BF'] = $p[57];
			$ret['BG'] = $p[58];
			$ret['BH'] = $p[59];
			$ret['BI'] = $p[60];
			$ret['BJ'] = $p[61];
			$ret['BK'] = $p[62];
			$ret['BL'] = $p[63];
			$ret['BM'] = $p[64];
			$ret['BN'] = $p[65];
			$ret['BO'] = $p[66];
			$ret['BP'] = $p[67];
			$ret['BQ'] = $p[68];
			$ret['BR'] = $p[69];
			$ret['BS'] = $p[70];
			$ret['BT'] = $p[71];
			$ret['BU'] = $p[72];
			$ret['BV'] = $p[73];
			$ret['BW'] = $p[74];
			$ret['BX'] = $p[75];
			$ret['BY'] = $p[76];
			$ret['BZ'] = $p[77];
			$ret['CA'] = $p[78];
			$ret['CB'] = $p[79];
			$ret['CC'] = $p[80];
			$ret['CD'] = $p[81];
			$ret['CE'] = $p[82];
			$ret['CF'] = $p[83];
			$ret['CG'] = $p[84];
			$ret['CH'] = $p[85];
			$ret['CI'] = $p[86];
			$ret['CJ'] = $p[87];
			$ret['CK'] = $p[88];
			$ret['CL'] = $p[89];
			$ret['CM'] = $p[90];
			$ret['CN'] = $p[91];
			$ret['CO'] = $p[92];
			$ret['CP'] = $p[93];
			$ret['CQ'] = $p[94];
			$ret['CR'] = $p[95];
			$ret['CS'] = $p[96];
			$ret['CT'] = $p[97];
			$ret['CU'] = $p[98];
			$ret['CV'] = $p[99];
			$ret['CW'] = $p[100];
			$ret['CX'] = $p[101];
			$ret['CY'] = $p[102];
			$ret['CZ'] = $p[103];
			$ret['DA'] = $p[104];
			$ret['DB'] = $p[105];
			$ret['DC'] = $p[106];
			$ret['DD'] = $p[107];
			$ret['DE'] = $p[108];
			$ret['DF'] = $p[109];
			$ret['DG'] = $p[110];
			$ret['DH'] = $p[111];
			$ret['DI'] = $p[112];
			$ret['DJ'] = $p[113];
			$ret['DK'] = $p[114];
			$ret['DL'] = $p[115];
			$ret['DM'] = $p[116];
			$ret['DN'] = $p[117];
			$ret['DO'] = $p[118];
			$ret['DP'] = $p[119];
			$ret['DQ'] = $p[120];
			$ret['DR'] = $p[121];
			$ret['DS'] = $p[122];
			$ret['DT'] = $p[123];
			$ret['DU'] = $p[124];
			$ret['DV'] = $p[125];
			$ret['DW'] = $p[126];
			$ret['DX'] = $p[127];
			$ret['DY'] = $p[128];
			$ret['DZ'] = $p[129];
			$ret['EA'] = $p[130];
			$ret['EB'] = $p[131];
			$ret['EC'] = $p[132];
			$ret['ED'] = $p[133];
			$ret['EE'] = $p[134];
			$ret['EF'] = $p[135];
			$ret['EG'] = $p[136];
			$ret['EH'] = $p[137];
			$ret['EI'] = $p[138];
			$ret['EJ'] = $p[139];
			$ret['EK'] = $p[140];
			$ret['EL'] = $p[141];
			$ret['EM'] = $p[142];
			$ret['EN'] = $p[143];
			$ret['EO'] = $p[144];
			$ret['EP'] = $p[145];
			$ret['EQ'] = $p[146];
			$ret['ER'] = $p[147];
			$ret['ES'] = $p[148];
			$ret['ET'] = $p[149];
			$ret['EU'] = $p[150];
			$ret['EV'] = $p[151];
			$ret['EW'] = $p[152];
			$ret['EX'] = $p[153];
			$ret['EY'] = $p[154];
			$ret['EZ'] = $p[155];
			$ret['FA'] = $p[156];
			$ret['FB'] = $p[157];
			$ret['FC'] = $p[158];
		}
		return $ret;
	}
	
	private function loadplzAction(){
		
		$dbName = 'opengedb';		
		$sourceDB = $this -> mysqli = new mysqli(
					 	DB::DB_HOST
					 	, DB::DB_USER
					 	, db::DB_PW
					 	, $dbName);

		$query = 'SELECT gl.loc_id as loc, plz.text_val as zip, name.text_val as locName, coord.lat, coord.lon
					FROM geodb_textdata plz
					LEFT JOIN geodb_textdata name ON name.loc_id = plz.loc_id
					LEFT JOIN geodb_locations gl ON gl.loc_id = plz.loc_id
					LEFT JOIN geodb_coordinates coord ON plz.loc_id = coord.loc_id
					WHERE plz.text_type =500300000/* ID für Postleitzahl */
					  AND name.text_type =500100000/* ID für name */
					  AND name.text_locale = "de" /* deutschsprachige Version*/
					  AND (
					        gl.loc_type =100600000/* ID für pol. Gliederung */
					        OR 
					        gl.loc_type =100700000/* ID für Ortschaft */
					  )
					;';
		//echo $query;
		$myDB = DB::getInstance();
		$sourceDB -> query('set character set utf8;');
		$sourceData = $sourceDB -> query($query);
		while ($row = $sourceData -> fetch_assoc()){
			$row['ok'] = '1';
			
			if ($row['loc'] == ''){
				$row['ok'] = '0';
			}
			if ($row['zip'] == ''){
				$row['ok'] = '0';
			}
			if ($row['locName'] == ''){
				$row['ok'] = '0';
			}
			if ($row['lat'] == ''){
				$row['ok'] = '0';
				$row['lat'] = 'null';
			}
			if ($row['lon'] == ''){
				$row['ok'] = '0';
				$row['lon'] = 'null';
			}
			$query2 = '	INSERT INTO plz (loc, zip, locName, lat, lon, ok) 
						VALUES("'.$row['loc'].'", "'.$row['zip'].'", "'.$row['locName'].'", '.$row['lat'].', '.$row['lon'].', '.$row['ok'].')';
			
			$myDB -> execQuery(array('q'=>$query2));
		}
	}	
	
	public function loadgeonamesAction(){

		$myDB = DB::getInstance();
		
		$query = 'SELECT *
					FROM geoname as g1
					;';
		
		$srcDat = $myDB -> execQuery($query);
		foreach($srcDat as $key => $row){
			$row['ok'] = 1;
			if (($row['latitude'] == '') || ($row['longitude'] == '')){
				$row['ok'] = 0;
			}
			$query2 = '	INSERT INTO plz (loc, zip, locName, lat, lon, ok) 
						VALUES("'.$row['geonameid'].'"
							, "'.$row['zip'].'"
							, "'.$row['name'].'"
							, '.$row['latitude'].'
							, '.$row['longitude'].'
							, '.$row['ok'].')';
			
			$myDB -> execQuery(array('q'=>$query2));
		}
	}	
	
	private function calcdistanceAction(){
		$myDB = DB::getInstance();
		
		$query = 'SELECT *
					from plz';
		$plz = $myDB -> execQuery(array('q'=>$query));
		if ($plz != false){
			foreach($plz as $key => $kVal){
				if (($kVal['lat'] != '') && ($kVal['lon'] != '')){
					/*
					$query = '	INSERT INTO plzdist (plzID1,plzID2, dist)
								SELECT 	p1.plzID
										, p2.plzID
										, ACOS(
								         SIN(RADIANS(p1.lat)) * SIN(RADIANS(p2.lat)) 
								         + COS(RADIANS(p1.lat)) * COS(RADIANS(p2.lat)) * COS(RADIANS(p1.lon) - RADIANS(p2.lon))
								         ) * 6380 AS distance
								from plz as p1 join plz as p2 on p1.plzID != p2.plzID limit 0, 100';
					
					*/

					$query = '	SELECT plzID
										, zip
										, ACOS(
								         SIN(RADIANS(lat)) * SIN(RADIANS('.$kVal['lat'].')) 
								         + COS(RADIANS(lat)) * COS(RADIANS('.$kVal['lat'].')) * COS(RADIANS(lon) - RADIANS('.$kVal['lon'].'))
								         ) * 6380 AS dist
								from plz ';
					
					//echo $query;
					$plz2 = $myDB -> execQuery(array('q' => $query));
					if (($plz2 != false) && is_array($plz2)){
						foreach($plz2 as $key2 => $kVal2){
							$query = 'SELECT *
										from plzdist where (plzID1 = "'.$kVal['plzID'].'" AND plzID2 = "'.$kVal2['plzID'].'") 
														or (plzID1 = "'.$kVal2['plzID'].'" AND plzID2 = "'.$kVal['plzID'].'")';
							if ($myDB -> execQuery(array('q' => $query)) == false){
								$query = 'INSERT INTO plzdist (plzID1, plzID2, dist) VALUES("'.$kVal['plzID'].'", "'.$kVal2['plzID'].'", '.$kVal2['dist'].')';
								$myDB -> execQuery(array('q'=>$query));
							}
						}
					}
				}
			}
		}		
	}
}
?>