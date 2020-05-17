<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insAS24($p=null){
	$return = false;
	$db = DB::getInstance();
	
	//perform escape
	$p = $db -> escape($p);
	
	$query1 = 'timestam';
	$query2 = 'UNIX_TIMESTAMP()';
	
	//1
	if(isset($p['A']) && ($p['A'] != null) && ($p['A'] != '')){
	  $query1 .= ', A';
	  $query2 .= ', "'.$db -> escape($p['A']).'"';
	}
	
	//2
	if(isset($p['B']) && ($p['B'] != null) && ($p['B'] != '')){
	  $query1 .= ', B';
	  $query2 .= ', "'.$db -> escape($p['B']).'"';
	}
	
	//3
	if(isset($p['C']) && ($p['C'] != null) && ($p['C'] != '')){
	  $query1 .= ', C';
	  $query2 .= ', "'.$db -> escape($p['C']).'"';
	}
	
	//4
	if(isset($p['D']) && ($p['D'] != null) && ($p['D'] != '')){
	  $query1 .= ', D';
	  $query2 .= ', "'.$db -> escape($p['D']).'"';
	}
	
	//5
	if(isset($p['E']) && ($p['E'] != null) && ($p['E'] != '')){
	  $query1 .= ', E';
	  $query2 .= ', "'.$db -> escape($p['E']).'"';
	}
	
	//6
	if(isset($p['F']) && ($p['F'] != null) && ($p['F'] != '')){
	  $query1 .= ', F';
	  $query2 .= ', "'.$db -> escape($p['F']).'"';
	}
	
	//7
	if(isset($p['G']) && ($p['G'] != null) && ($p['G'] != '')){
	  $query1 .= ', G';
	  $query2 .= ', "'.$db -> escape($p['G']).'"';
	}
	
	//8
	if(isset($p['H']) && ($p['H'] != null) && ($p['H'] != '')){
	  $query1 .= ', H';
	  $query2 .= ', "'.$db -> escape($p['H']).'"';
	}
	
	//9
	if(isset($p['I']) && ($p['I'] != null) && ($p['I'] != '')){
	  $query1 .= ', I';
	  $query2 .= ', "'.$db -> escape($p['I']).'"';
	}
	
	//10
	if(isset($p['J']) && ($p['J'] != null) && ($p['J'] != '')){
	  $query1 .= ', J';
	  $query2 .= ', "'.$db -> escape($p['J']).'"';
	}
	
	//11
	if(isset($p['K']) && ($p['K'] != null) && ($p['K'] != '')){
	  $query1 .= ', K';
	  $query2 .= ', "'.$db -> escape($p['K']).'"';
	}
	
	//12
	if(isset($p['L']) && ($p['L'] != null) && ($p['L'] != '')){
	  $query1 .= ', L';
	  $query2 .= ', "'.$db -> escape($p['L']).'"';
	}
	
	//13
	if(isset($p['M']) && ($p['M'] != null) && ($p['M'] != '')){
	  $query1 .= ', M';
	  $query2 .= ', "'.$db -> escape($p['M']).'"';
	}
	
	//14
	if(isset($p['N']) && ($p['N'] != null) && ($p['N'] != '')){
	  $query1 .= ', N';
	  $query2 .= ', "'.$db -> escape($p['N']).'"';
	}
	
	//15
	if(isset($p['O']) && ($p['O'] != null) && ($p['O'] != '')){
	  $query1 .= ', O';
	  $query2 .= ', "'.$db -> escape($p['O']).'"';
	}
	
	//16
	if(isset($p['P']) && ($p['P'] != null) && ($p['P'] != '')){
	  $query1 .= ', P';
	  $query2 .= ', "'.$db -> escape($p['P']).'"';
	}
	
	//17
	if(isset($p['Q']) && ($p['Q'] != null) && ($p['Q'] != '')){
	  $query1 .= ', Q';
	  $query2 .= ', "'.$db -> escape($p['Q']).'"';
	}
	
	//18
	if(isset($p['R']) && ($p['R'] != null) && ($p['R'] != '')){
	  $query1 .= ', R';
	  $query2 .= ', "'.$db -> escape($p['R']).'"';
	}
	
	//19
	if(isset($p['S']) && ($p['S'] != null) && ($p['S'] != '')){
	  $query1 .= ', S';
	  $query2 .= ', "'.$db -> escape($p['S']).'"';
	}
	
	//20
	if(isset($p['T']) && ($p['T'] != null) && ($p['T'] != '')){
	  $query1 .= ', T';
	  $query2 .= ', "'.$db -> escape($p['T']).'"';
	}
	
	//21
	if(isset($p['U']) && ($p['U'] != null) && ($p['U'] != '')){
	  $query1 .= ', U';
	  $query2 .= ', "'.$db -> escape($p['U']).'"';
	}
	
	//22
	if(isset($p['V']) && ($p['V'] != null) && ($p['V'] != '')){
	  $query1 .= ', V';
	  $query2 .= ', "'.$db -> escape($p['V']).'"';
	}
	
	//23
	if(isset($p['W']) && ($p['W'] != null) && ($p['W'] != '')){
	  $query1 .= ', W';
	  $query2 .= ', "'.$db -> escape($p['W']).'"';
	}
	
	//24
	if(isset($p['X']) && ($p['X'] != null) && ($p['X'] != '')){
	  $query1 .= ', X';
	  $query2 .= ', "'.$db -> escape($p['X']).'"';
	}
	
	//25
	if(isset($p['Y']) && ($p['Y'] != null) && ($p['Y'] != '')){
	  $query1 .= ', Y';
	  $query2 .= ', "'.$db -> escape($p['Y']).'"';
	}
	
	//26
	if(isset($p['Z']) && ($p['Z'] != null) && ($p['Z'] != '')){
	  $query1 .= ', Z';
	  $query2 .= ', "'.$db -> escape($p['Z']).'"';
	}
	
	//27
	if(isset($p['AA']) && ($p['AA'] != null) && ($p['AA'] != '')){
	  $query1 .= ', AA';
	  $query2 .= ', "'.$db -> escape($p['AA']).'"';
	}
	
	//28
	if(isset($p['AB']) && ($p['AB'] != null) && ($p['AB'] != '')){
	  $query1 .= ', AB';
	  $query2 .= ', "'.$db -> escape($p['AB']).'"';
	}
	
	//29
	if(isset($p['AC']) && ($p['AC'] != null) && ($p['AC'] != '')){
	  $query1 .= ', AC';
	  $query2 .= ', "'.$db -> escape($p['AC']).'"';
	}
	
	//30
	if(isset($p['AD']) && ($p['AD'] != null) && ($p['AD'] != '')){
	  $query1 .= ', AD';
	  $query2 .= ', "'.$db -> escape($p['AD']).'"';
	}
	
	//31
	if(isset($p['AE']) && ($p['AE'] != null) && ($p['AE'] != '')){
	  $query1 .= ', AE';
	  $query2 .= ', "'.$db -> escape($p['AE']).'"';
	}
	
	//32
	if(isset($p['AF']) && ($p['AF'] != null) && ($p['AF'] != '')){
	  $query1 .= ', AF';
	  $query2 .= ', "'.$db -> escape($p['AF']).'"';
	}
	
	//33
	if(isset($p['AG']) && ($p['AG'] != null) && ($p['AG'] != '')){
	  $query1 .= ', AG';
	  $query2 .= ', "'.$db -> escape($p['AG']).'"';
	}
	
	//34
	if(isset($p['AH']) && ($p['AH'] != null) && ($p['AH'] != '')){
	  $query1 .= ', AH';
	  $query2 .= ', "'.$db -> escape($p['AH']).'"';
	}
	
	//35
	if(isset($p['AI']) && ($p['AI'] != null) && ($p['AI'] != '')){
	  $query1 .= ', AI';
	  $query2 .= ', "'.$db -> escape($p['AI']).'"';
	}
	
	//36
	if(isset($p['AJ']) && ($p['AJ'] != null) && ($p['AJ'] != '')){
	  $query1 .= ', AJ';
	  $query2 .= ', "'.$db -> escape($p['AJ']).'"';
	}
	
	//37
	if(isset($p['AK']) && ($p['AK'] != null) && ($p['AK'] != '')){
	  $query1 .= ', AK';
	  $query2 .= ', "'.$db -> escape($p['AK']).'"';
	}
	
	//38
	if(isset($p['AL']) && ($p['AL'] != null) && ($p['AL'] != '')){
	  $query1 .= ', AL';
	  $query2 .= ', "'.$db -> escape($p['AL']).'"';
	}
	
	//39
	if(isset($p['AM']) && ($p['AM'] != null) && ($p['AM'] != '')){
	  $query1 .= ', AM';
	  $query2 .= ', "'.$db -> escape($p['AM']).'"';
	}
	
	//40
	if(isset($p['AN']) && ($p['AN'] != null) && ($p['AN'] != '')){
	  $query1 .= ', AN';
	  $query2 .= ', "'.$db -> escape($p['AN']).'"';
	}
	
	//41
	if(isset($p['AO']) && ($p['AO'] != null) && ($p['AO'] != '')){
	  $query1 .= ', AO';
	  $query2 .= ', "'.$db -> escape($p['AO']).'"';
	}
	
	//42
	if(isset($p['AP']) && ($p['AP'] != null) && ($p['AP'] != '')){
	  $query1 .= ', AP';
	  $query2 .= ', "'.$db -> escape($p['AP']).'"';
	}
	
	//43
	if(isset($p['AQ']) && ($p['AQ'] != null) && ($p['AQ'] != '')){
	  $query1 .= ', AQ';
	  $query2 .= ', "'.$db -> escape($p['AQ']).'"';
	}
	
	//44
	if(isset($p['AR']) && ($p['AR'] != null) && ($p['AR'] != '')){
	  $query1 .= ', AR';
	  $query2 .= ', "'.$db -> escape($p['AR']).'"';
	}
	
	//45
	if(isset($p['AS']) && ($p['AS'] != null) && ($p['AS'] != '')){
	  $query1 .= ', AS';
	  $query2 .= ', "'.$db -> escape($p['AS']).'"';
	}
	
	//46
	if(isset($p['AT']) && ($p['AT'] != null) && ($p['AT'] != '')){
	  $query1 .= ', AT';
	  $query2 .= ', "'.$db -> escape($p['AT']).'"';
	}
	
	//47
	if(isset($p['AU']) && ($p['AU'] != null) && ($p['AU'] != '')){
	  $query1 .= ', AU';
	  $query2 .= ', "'.$db -> escape($p['AU']).'"';
	}
	
	//48
	if(isset($p['AV']) && ($p['AV'] != null) && ($p['AV'] != '')){
	  $query1 .= ', AV';
	  $query2 .= ', "'.$db -> escape($p['AV']).'"';
	}
	
	//49
	if(isset($p['AW']) && ($p['AW'] != null) && ($p['AW'] != '')){
	  $query1 .= ', AW';
	  $query2 .= ', "'.$db -> escape($p['AW']).'"';
	}
	
	//50
	if(isset($p['AX']) && ($p['AX'] != null) && ($p['AX'] != '')){
	  $query1 .= ', AX';
	  $query2 .= ', "'.$db -> escape($p['AX']).'"';
	}
	
	//51
	if(isset($p['AY']) && ($p['AY'] != null) && ($p['AY'] != '')){
	  $query1 .= ', AY';
	  $query2 .= ', "'.$db -> escape($p['AY']).'"';
	}
	
	//52
	if(isset($p['AZ']) && ($p['AZ'] != null) && ($p['AZ'] != '')){
	  $query1 .= ', AZ';
	  $query2 .= ', "'.$db -> escape($p['AZ']).'"';
	}
	
	//53
	if(isset($p['BA']) && ($p['BA'] != null) && ($p['BA'] != '')){
	  $query1 .= ', BA';
	  $query2 .= ', "'.$db -> escape($p['BA']).'"';
	}
	
	//54
	if(isset($p['BB']) && ($p['BB'] != null) && ($p['BB'] != '')){
	  $query1 .= ', BB';
	  $query2 .= ', "'.$db -> escape($p['BB']).'"';
	}
	
	//55
	if(isset($p['BC']) && ($p['BC'] != null) && ($p['BC'] != '')){
	  $query1 .= ', BC';
	  $query2 .= ', "'.$db -> escape($p['BC']).'"';
	}
	
	//56
	if(isset($p['BD']) && ($p['BD'] != null) && ($p['BD'] != '')){
	  $query1 .= ', BD';
	  $query2 .= ', "'.$db -> escape($p['BD']).'"';
	}
	
	//57
	if(isset($p['BE']) && ($p['BE'] != null) && ($p['BE'] != '')){
	  $query1 .= ', BE';
	  $query2 .= ', "'.$db -> escape($p['BE']).'"';
	}
	
	//58
	if(isset($p['BF']) && ($p['BF'] != null) && ($p['BF'] != '')){
	  $query1 .= ', BF';
	  $query2 .= ', "'.$db -> escape($p['BF']).'"';
	}
	
	//59
	if(isset($p['BG']) && ($p['BG'] != null) && ($p['BG'] != '')){
	  $query1 .= ', BG';
	  $query2 .= ', "'.$db -> escape($p['BG']).'"';
	}
	
	//60
	if(isset($p['BH']) && ($p['BH'] != null) && ($p['BH'] != '')){
	  $query1 .= ', BH';
	  $query2 .= ', "'.$db -> escape($p['BH']).'"';
	}
	
	//61
	if(isset($p['BI']) && ($p['BI'] != null) && ($p['BI'] != '')){
	  $query1 .= ', BI';
	  $query2 .= ', "'.$db -> escape($p['BI']).'"';
	}
	
	//62
	if(isset($p['BJ']) && ($p['BJ'] != null) && ($p['BJ'] != '')){
	  $query1 .= ', BJ';
	  $query2 .= ', "'.$db -> escape($p['BJ']).'"';
	}
	
	//63
	if(isset($p['BK']) && ($p['BK'] != null) && ($p['BK'] != '')){
	  $query1 .= ', BK';
	  $query2 .= ', "'.$db -> escape($p['BK']).'"';
	}
	
	//64
	if(isset($p['BL']) && ($p['BL'] != null) && ($p['BL'] != '')){
	  $query1 .= ', BL';
	  $query2 .= ', "'.$db -> escape($p['BL']).'"';
	}
	
	//65
	if(isset($p['BM']) && ($p['BM'] != null) && ($p['BM'] != '')){
	  $query1 .= ', BM';
	  $query2 .= ', "'.$db -> escape($p['BM']).'"';
	}
	
	//66
	if(isset($p['BN']) && ($p['BN'] != null) && ($p['BN'] != '')){
	  $query1 .= ', BN';
	  $query2 .= ', "'.$db -> escape($p['BN']).'"';
	}
	
	//67
	if(isset($p['BO']) && ($p['BO'] != null) && ($p['BO'] != '')){
	  $query1 .= ', BO';
	  $query2 .= ', "'.$db -> escape($p['BO']).'"';
	}
	
	//68
	if(isset($p['BP']) && ($p['BP'] != null) && ($p['BP'] != '')){
	  $query1 .= ', BP';
	  $query2 .= ', "'.$db -> escape($p['BP']).'"';
	}
	
	//69
	if(isset($p['BQ']) && ($p['BQ'] != null) && ($p['BQ'] != '')){
	  $query1 .= ', BQ';
	  $query2 .= ', "'.$db -> escape($p['BQ']).'"';
	}
	
	//70
	if(isset($p['BR']) && ($p['BR'] != null) && ($p['BR'] != '')){
	  $query1 .= ', BR';
	  $query2 .= ', "'.$db -> escape($p['BR']).'"';
	}
	
	//71
	if(isset($p['BS']) && ($p['BS'] != null) && ($p['BS'] != '')){
	  $query1 .= ', BS';
	  $query2 .= ', "'.$db -> escape($p['BS']).'"';
	}
	
	//72
	if(isset($p['BT']) && ($p['BT'] != null) && ($p['BT'] != '')){
	  $query1 .= ', BT';
	  $query2 .= ', "'.$db -> escape($p['BT']).'"';
	}
	
	//73
	if(isset($p['BU']) && ($p['BU'] != null) && ($p['BU'] != '')){
	  $query1 .= ', BU';
	  $query2 .= ', "'.$db -> escape($p['BU']).'"';
	}
	
	//74
	if(isset($p['BV']) && ($p['BV'] != null) && ($p['BV'] != '')){
	  $query1 .= ', BV';
	  $query2 .= ', "'.$db -> escape($p['BV']).'"';
	}
	
	//75
	if(isset($p['BW']) && ($p['BW'] != null) && ($p['BW'] != '')){
	  $query1 .= ', BW';
	  $query2 .= ', "'.$db -> escape($p['BW']).'"';
	}
	
	//76
	if(isset($p['BX']) && ($p['BX'] != null) && ($p['BX'] != '')){
	  $query1 .= ', BX';
	  $query2 .= ', "'.$db -> escape($p['BX']).'"';
	}
	
	//77
	if(isset($p['BY']) && ($p['BY'] != null) && ($p['BY'] != '')){
	  $query1 .= ', BY';
	  $query2 .= ', "'.$db -> escape($p['BY']).'"';
	}
	
	//78
	if(isset($p['BZ']) && ($p['BZ'] != null) && ($p['BZ'] != '')){
	  $query1 .= ', BZ';
	  $query2 .= ', "'.$db -> escape($p['BZ']).'"';
	}
	
	//79
	if(isset($p['CA']) && ($p['CA'] != null) && ($p['CA'] != '')){
	  $query1 .= ', CA';
	  $query2 .= ', "'.$db -> escape($p['CA']).'"';
	}
	
	//80
	if(isset($p['CB']) && ($p['CB'] != null) && ($p['CB'] != '')){
	  $query1 .= ', CB';
	  $query2 .= ', "'.$db -> escape($p['CB']).'"';
	}
	
	//81
	if(isset($p['CC']) && ($p['CC'] != null) && ($p['CC'] != '')){
	  $query1 .= ', CC';
	  $query2 .= ', "'.$db -> escape($p['CC']).'"';
	}
	
	//82
	if(isset($p['CD']) && ($p['CD'] != null) && ($p['CD'] != '')){
	  $query1 .= ', CD';
	  $query2 .= ', "'.$db -> escape($p['CD']).'"';
	}
	
	//83
	if(isset($p['CE']) && ($p['CE'] != null) && ($p['CE'] != '')){
	  $query1 .= ', CE';
	  $query2 .= ', "'.$db -> escape($p['CE']).'"';
	}
	
	//84
	if(isset($p['CF']) && ($p['CF'] != null) && ($p['CF'] != '')){
	  $query1 .= ', CF';
	  $query2 .= ', "'.$db -> escape($p['CF']).'"';
	}
	
	//85
	if(isset($p['CG']) && ($p['CG'] != null) && ($p['CG'] != '')){
	  $query1 .= ', CG';
	  $query2 .= ', "'.$db -> escape($p['CG']).'"';
	}
	
	//86
	if(isset($p['CH']) && ($p['CH'] != null) && ($p['CH'] != '')){
	  $query1 .= ', CH';
	  $query2 .= ', "'.$db -> escape($p['CH']).'"';
	}
	
	//87
	if(isset($p['CI']) && ($p['CI'] != null) && ($p['CI'] != '')){
	  $query1 .= ', CI';
	  $query2 .= ', "'.$db -> escape($p['CI']).'"';
	}
	
	//88
	if(isset($p['CJ']) && ($p['CJ'] != null) && ($p['CJ'] != '')){
	  $query1 .= ', CJ';
	  $query2 .= ', "'.$db -> escape($p['CJ']).'"';
	}
	
	//89
	if(isset($p['CK']) && ($p['CK'] != null) && ($p['CK'] != '')){
	  $query1 .= ', CK';
	  $query2 .= ', "'.$db -> escape($p['CK']).'"';
	}
	
	//90
	if(isset($p['CL']) && ($p['CL'] != null) && ($p['CL'] != '')){
	  $query1 .= ', CL';
	  $query2 .= ', "'.$db -> escape($p['CL']).'"';
	}
	
	//91
	if(isset($p['CM']) && ($p['CM'] != null) && ($p['CM'] != '')){
	  $query1 .= ', CM';
	  $query2 .= ', "'.$db -> escape($p['CM']).'"';
	}
	
	//92
	if(isset($p['CN']) && ($p['CN'] != null) && ($p['CN'] != '')){
	  $query1 .= ', CN';
	  $query2 .= ', "'.$db -> escape($p['CN']).'"';
	}
	
	//93
	if(isset($p['CO']) && ($p['CO'] != null) && ($p['CO'] != '')){
	  $query1 .= ', CO';
	  $query2 .= ', "'.$db -> escape($p['CO']).'"';
	}
	
	//94
	if(isset($p['CP']) && ($p['CP'] != null) && ($p['CP'] != '')){
	  $query1 .= ', CP';
	  $query2 .= ', "'.$db -> escape($p['CP']).'"';
	}
	
	//95
	if(isset($p['CQ']) && ($p['CQ'] != null) && ($p['CQ'] != '')){
	  $query1 .= ', CQ';
	  $query2 .= ', "'.$db -> escape($p['CQ']).'"';
	}
	
	//96
	if(isset($p['CR']) && ($p['CR'] != null) && ($p['CR'] != '')){
	  $query1 .= ', CR';
	  $query2 .= ', "'.$db -> escape($p['CR']).'"';
	}
	
	//97
	if(isset($p['CS']) && ($p['CS'] != null) && ($p['CS'] != '')){
	  $query1 .= ', CS';
	  $query2 .= ', "'.$db -> escape($p['CS']).'"';
	}
	
	//98
	if(isset($p['CT']) && ($p['CT'] != null) && ($p['CT'] != '')){
	  $query1 .= ', CT';
	  $query2 .= ', "'.$db -> escape($p['CT']).'"';
	}
	
	//99
	if(isset($p['CU']) && ($p['CU'] != null) && ($p['CU'] != '')){
	  $query1 .= ', CU';
	  $query2 .= ', "'.$db -> escape($p['CU']).'"';
	}
	
	//100
	if(isset($p['CV']) && ($p['CV'] != null) && ($p['CV'] != '')){
	  $query1 .= ', CV';
	  $query2 .= ', "'.$db -> escape($p['CV']).'"';
	}
	
	//101
	if(isset($p['CW']) && ($p['CW'] != null) && ($p['CW'] != '')){
	  $query1 .= ', CW';
	  $query2 .= ', "'.$db -> escape($p['CW']).'"';
	}
	
	//102
	if(isset($p['CX']) && ($p['CX'] != null) && ($p['CX'] != '')){
	  $query1 .= ', CX';
	  $query2 .= ', "'.$db -> escape($p['CX']).'"';
	}
	
	//103
	if(isset($p['CY']) && ($p['CY'] != null) && ($p['CY'] != '')){
	  $query1 .= ', CY';
	  $query2 .= ', "'.$db -> escape($p['CY']).'"';
	}
	
	//104
	if(isset($p['CZ']) && ($p['CZ'] != null) && ($p['CZ'] != '')){
	  $query1 .= ', CZ';
	  $query2 .= ', "'.$db -> escape($p['CZ']).'"';
	}
	
	//105
	if(isset($p['DA']) && ($p['DA'] != null) && ($p['DA'] != '')){
	$query1 .= ', DA';
	$query2 .= ', "'.$db -> escape($p['DA']).'"';
	}
	
	//106
	if(isset($p['DB']) && ($p['DB'] != null) && ($p['DB'] != '')){
	  $query1 .= ', DB';
	  $query2 .= ', "'.$db -> escape($p['DB']).'"';
	}
	
	//107
	if(isset($p['DC']) && ($p['DC'] != null) && ($p['DC'] != '')){
	  $query1 .= ', DC';
	  $query2 .= ', "'.$db -> escape($p['DC']).'"';
	}
	
	//108
	if(isset($p['DD']) && ($p['DD'] != null) && ($p['DD'] != '')){
	  $query1 .= ', DD';
	  $query2 .= ', "'.$db -> escape($p['DD']).'"';
	}
	
	//109
	if(isset($p['DE']) && ($p['DE'] != null) && ($p['DE'] != '')){
	  $query1 .= ', DE';
	  $query2 .= ', "'.$db -> escape($p['DE']).'"';
	}
	
	//110
	if(isset($p['DF']) && ($p['DF'] != null) && ($p['DF'] != '')){
	  $query1 .= ', DF';
	  $query2 .= ', "'.$db -> escape($p['DF']).'"';
	}
	
	//111
	if(isset($p['DG']) && ($p['DG'] != null) && ($p['DG'] != '')){
	  $query1 .= ', DG';
	  $query2 .= ', "'.$db -> escape($p['DG']).'"';
	}
	
	//112
	if(isset($p['DH']) && ($p['DH'] != null) && ($p['DH'] != '')){
	  $query1 .= ', DH';
	  $query2 .= ', "'.$db -> escape($p['DH']).'"';
	}
	
	//113
	if(isset($p['DI']) && ($p['DI'] != null) && ($p['DI'] != '')){
	  $query1 .= ', DI';
	  $query2 .= ', "'.$db -> escape($p['DI']).'"';
	}
	
	//114
	if(isset($p['DJ']) && ($p['DJ'] != null) && ($p['DJ'] != '')){
	  $query1 .= ', DJ';
	  $query2 .= ', "'.$db -> escape($p['DJ']).'"';
	}
	
	//115
	if(isset($p['DK']) && ($p['DK'] != null) && ($p['DK'] != '')){
	  $query1 .= ', DK';
	  $query2 .= ', "'.$db -> escape($p['DK']).'"';
	}
	
	//116
	if(isset($p['DL']) && ($p['DL'] != null) && ($p['DL'] != '')){
	  $query1 .= ', DL';
	  $query2 .= ', "'.$db -> escape($p['DL']).'"';
	}
	
	//117
	if(isset($p['DM']) && ($p['DM'] != null) && ($p['DM'] != '')){
	  $query1 .= ', DM';
	  $query2 .= ', "'.$db -> escape($p['DM']).'"';
	}
	
	//118
	if(isset($p['DN']) && ($p['DN'] != null) && ($p['DN'] != '')){
	  $query1 .= ', DN';
	  $query2 .= ', "'.$db -> escape($p['DN']).'"';
	}
	
	//119
	if(isset($p['DO']) && ($p['DO'] != null) && ($p['DO'] != '')){
	  $query1 .= ', DO';
	  $query2 .= ', "'.$db -> escape($p['DO']).'"';
	}
	
	//120
	if(isset($p['DP']) && ($p['DP'] != null) && ($p['DP'] != '')){
	  $query1 .= ', DP';
	  $query2 .= ', "'.$db -> escape($p['DP']).'"';
	}
	
	//121
	if(isset($p['DQ']) && ($p['DQ'] != null) && ($p['DQ'] != '')){
	  $query1 .= ', DQ';
	  $query2 .= ', "'.$db -> escape($p['DQ']).'"';
	}
	
	//as24New
	if(isset($p['as24New']) && ($p['as24New'] != null) && ($p['as24New'] != '')){
	  $query1 .= ', as24New';
	  $query2 .= ', "'.$db -> escape($p['as24New']).'"';
	}
	
	//userID
	if(isset($p['userID']) && ($p['userID'] != null) && ($p['userID'] != '')){
	  $query1 .= ', userID';
	  $query2 .= ', "'.$db -> escape($p['userID']).'"';
	}
	
	//vType
	if(isset($p['vType']) && ($p['vType'] != null) && ($p['vType'] != '')){
	  $query1 .= ', vType';
	  $query2 .= ', "'.$db -> escape($p['vType']).'"';
	}
	
	//Build complete query
	$query = '	INSERT INTO as24( '.$query1.' ) VALUES ( '.$query2.' )';

	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
