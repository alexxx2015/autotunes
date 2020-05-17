<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100921
 * Desc:		This function insert picture informations
 *********************************************************************************/
include_once('classes/DB.php');

function db_insMobile($p=null){
	$return = false;
	$db = DB::getInstance();
	
	//perform escape
	$p = $db -> escape($p);
	
	$query1 = 'timestam';
	$query2 = 'UNIX_TIMESTAMP()';
	
	//0
	if(isset($p['A']) && ($p['A'] != null)){
	  $query1 .= ', A';
	  $query2 .= ', "'.$db -> escape($p['A']).'"';
	}
	
	//1
	if(isset($p['B']) && ($p['B'] != null)){
	  $query1 .= ', B';
	  $query2 .= ', "'.$db -> escape($p['B']).'"';
	}
	
	//2
	if(isset($p['C']) && ($p['C'] != null)){
	  $query1 .= ', C';
	  $query2 .= ', "'.$db -> escape($p['C']).'"';
	}
	
	//3
	if(isset($p['D']) && ($p['D'] != null)){
	  $query1 .= ', D';
	  $query2 .= ', "'.$db -> escape($p['D']).'"';
	}
	
	//4
	if(isset($p['E']) && ($p['E'] != null)){
	  $query1 .= ', E';
	  $query2 .= ', "'.$db -> escape($p['E']).'"';
	}
	
	//5
	if(isset($p['F']) && ($p['F'] != null)){
	  $query1 .= ', F';
	  $query2 .= ', "'.$db -> escape($p['F']).'"';
	}
	
	//6
	if(isset($p['G']) && ($p['G'] != null)){
	  $query1 .= ', G';
	  $query2 .= ', "'.$db -> escape($p['G']).'"';
	}
	
	//7
	if(isset($p['H']) && ($p['H'] != null)){
	  $query1 .= ', H';
	  $query2 .= ', "'.$db -> escape($p['H']).'"';
	}
	
	//8
	if(isset($p['I']) && ($p['I'] != null)){
	  $query1 .= ', I';
	  $query2 .= ', "'.$db -> escape($p['I']).'"';
	}
	
	//9
	if(isset($p['J']) && ($p['J'] != null)){
	  $query1 .= ', J';
	  $query2 .= ', "'.$db -> escape($p['J']).'"';
	}
	
	//10
	if(isset($p['K']) && ($p['K'] != null)){
	  $query1 .= ', K';
	  $query2 .= ', "'.$db -> escape($p['K']).'"';
	}
	
	//11
	if(isset($p['L']) && ($p['L'] != null)){
	  $query1 .= ', L';
	  $query2 .= ', "'.$db -> escape($p['L']).'"';
	}
	
	//12
	if(isset($p['M']) && ($p['M'] != null)){
	  $query1 .= ', M';
	  $query2 .= ', "'.$db -> escape($p['M']).'"';
	}
	
	//13
	if(isset($p['N']) && ($p['N'] != null)){
	  $query1 .= ', N';
	  $query2 .= ', "'.$db -> escape($p['N']).'"';
	}
	
	//14
	if(isset($p['O']) && ($p['O'] != null)){
	  $query1 .= ', O';
	  $query2 .= ', "'.$db -> escape($p['O']).'"';
	}
	
	//15
	if(isset($p['P']) && ($p['P'] != null)){
	  $query1 .= ', P';
	  $query2 .= ', "'.$db -> escape($p['P']).'"';
	}
	
	//16
	if(isset($p['Q']) && ($p['Q'] != null)){
	  $query1 .= ', Q';
	  $query2 .= ', "'.$db -> escape($p['Q']).'"';
	}
	
	//17
	if(isset($p['R']) && ($p['R'] != null)){
	  $query1 .= ', R';
	  $query2 .= ', "'.$db -> escape($p['R']).'"';
	}
	
	//18
	if(isset($p['S']) && ($p['S'] != null)){
	  $query1 .= ', S';
	  $query2 .= ', "'.$db -> escape($p['S']).'"';
	}
	
	//19
	if(isset($p['T']) && ($p['T'] != null)){
	  $query1 .= ', T';
	  $query2 .= ', "'.$db -> escape($p['T']).'"';
	}
	
	//20
	if(isset($p['U']) && ($p['U'] != null)){
	  $query1 .= ', U';
	  $query2 .= ', "'.$db -> escape($p['U']).'"';
	}
	
	//21
	if(isset($p['V']) && ($p['V'] != null)){
	  $query1 .= ', V';
	  $query2 .= ', "'.$db -> escape($p['V']).'"';
	}
	
	//22
	if(isset($p['W']) && ($p['W'] != null)){
	  $query1 .= ', W';
	  $query2 .= ', "'.$db -> escape($p['W']).'"';
	}
	
	//23
	if(isset($p['X']) && ($p['X'] != null)){
	  $query1 .= ', X';
	  $query2 .= ', "'.$db -> escape($p['X']).'"';
	}
	
	//24
	if(isset($p['Y']) && ($p['Y'] != null)){
	  $query1 .= ', Y';
	  $query2 .= ', "'.$db -> escape($p['Y']).'"';
	}
	
	//25
	if(isset($p['Z']) && ($p['Z'] != null)){
	  $query1 .= ', Z';
	  $query2 .= ', "'.$db -> escape($p['Z']).'"';
	}
	
	//26
	if(isset($p['AA']) && ($p['AA'] != null)){
	  $query1 .= ', AA';
	  $query2 .= ', "'.$db -> escape($p['AA']).'"';
	}
	
	//27
	if(isset($p['AB']) && ($p['AB'] != null)){
	  $query1 .= ', AB';
	  $query2 .= ', "'.$db -> escape($p['AB']).'"';
	}
	
	//28
	if(isset($p['AC']) && ($p['AC'] != null)){
	  $query1 .= ', AC';
	  $query2 .= ', "'.$db -> escape($p['AC']).'"';
	}
	
	//29
	if(isset($p['AD']) && ($p['AD'] != null)){
	  $query1 .= ', AD';
	  $query2 .= ', "'.$db -> escape($p['AD']).'"';
	}
	
	//30
	if(isset($p['AE']) && ($p['AE'] != null)){
	  $query1 .= ', AE';
	  $query2 .= ', "'.$db -> escape($p['AE']).'"';
	}
	
	//31
	if(isset($p['AF']) && ($p['AF'] != null)){
	  $query1 .= ', AF';
	  $query2 .= ', "'.$db -> escape($p['AF']).'"';
	}
	
	//32
	if(isset($p['AG']) && ($p['AG'] != null)){
	  $query1 .= ', AG';
	  $query2 .= ', "'.$db -> escape($p['AG']).'"';
	}
	
	//33
	if(isset($p['AH']) && ($p['AH'] != null)){
	  $query1 .= ', AH';
	  $query2 .= ', "'.$db -> escape($p['AH']).'"';
	}
	
	//34
	if(isset($p['AI']) && ($p['AI'] != null)){
	  $query1 .= ', AI';
	  $query2 .= ', "'.$db -> escape($p['AI']).'"';
	}
	
	//35
	if(isset($p['AJ']) && ($p['AJ'] != null)){
	  $query1 .= ', AJ';
	  $query2 .= ', "'.$db -> escape($p['AJ']).'"';
	}
	
	//36
	if(isset($p['AK']) && ($p['AK'] != null)){
	  $query1 .= ', AK';
	  $query2 .= ', "'.$db -> escape($p['AK']).'"';
	}
	
	//37
	if(isset($p['AL']) && ($p['AL'] != null)){
	  $query1 .= ', AL';
	  $query2 .= ', "'.$db -> escape($p['AL']).'"';
	}
	
	//38
	if(isset($p['AM']) && ($p['AM'] != null)){
	  $query1 .= ', AM';
	  $query2 .= ', "'.$db -> escape($p['AM']).'"';
	}
	
	//39
	if(isset($p['AN']) && ($p['AN'] != null)){
	  $query1 .= ', AN';
	  $query2 .= ', "'.$db -> escape($p['AN']).'"';
	}
	
	//40
	if(isset($p['AO']) && ($p['AO'] != null)){
	  $query1 .= ', AO';
	  $query2 .= ', "'.$db -> escape($p['AO']).'"';
	}
	
	//41
	if(isset($p['AP']) && ($p['AP'] != null)){
	  $query1 .= ', AP';
	  $query2 .= ', "'.$db -> escape($p['AP']).'"';
	}
	
	//42
	if(isset($p['AQ']) && ($p['AQ'] != null)){
	  $query1 .= ', AQ';
	  $query2 .= ', "'.$db -> escape($p['AQ']).'"';
	}
	
	//43
	if(isset($p['AR']) && ($p['AR'] != null)){
	  $query1 .= ', AR';
	  $query2 .= ', "'.$db -> escape($p['AR']).'"';
	}
	
	//44
	if(isset($p['AS1']) && ($p['AS1'] != null)){
	  $query1 .= ', AS1';
	  $query2 .= ', "'.$db -> escape($p['AS1']).'"';
	}
	
	//45
	if(isset($p['AT']) && ($p['AT'] != null)){
	  $query1 .= ', AT';
	  $query2 .= ', "'.$db -> escape($p['AT']).'"';
	}
	
	//46
	if(isset($p['AU']) && ($p['AU'] != null)){
	  $query1 .= ', AU';
	  $query2 .= ', "'.$db -> escape($p['AU']).'"';
	}
	
	//47
	if(isset($p['AV']) && ($p['AV'] != null)){
	  $query1 .= ', AV';
	  $query2 .= ', "'.$db -> escape($p['AV']).'"';
	}
	
	//48
	if(isset($p['AW']) && ($p['AW'] != null)){
	  $query1 .= ', AW';
	  $query2 .= ', "'.$db -> escape($p['AW']).'"';
	}
	
	//49
	if(isset($p['AX']) && ($p['AX'] != null)){
	  $query1 .= ', AX';
	  $query2 .= ', "'.$db -> escape($p['AX']).'"';
	}
	
	//50
	if(isset($p['AY']) && ($p['AY'] != null)){
	  $query1 .= ', AY';
	  $query2 .= ', "'.$db -> escape($p['AY']).'"';
	}
	
	//51
	if(isset($p['AZ']) && ($p['AZ'] != null)){
	  $query1 .= ', AZ';
	  $query2 .= ', "'.$db -> escape($p['AZ']).'"';
	}
	
	//52
	if(isset($p['BA']) && ($p['BA'] != null)){
	  $query1 .= ', BA';
	  $query2 .= ', "'.$db -> escape($p['BA']).'"';
	}
	
	//53
	if(isset($p['BB']) && ($p['BB'] != null)){
	  $query1 .= ', BB';
	  $query2 .= ', "'.$db -> escape($p['BB']).'"';
	}
	
	//54
	if(isset($p['BC']) && ($p['BC'] != null)){
	  $query1 .= ', BC';
	  $query2 .= ', "'.$db -> escape($p['BC']).'"';
	}
	
	//55
	if(isset($p['BD']) && ($p['BD'] != null)){
	  $query1 .= ', BD';
	  $query2 .= ', "'.$db -> escape($p['BD']).'"';
	}
	
	//56
	if(isset($p['BE']) && ($p['BE'] != null)){
	  $query1 .= ', BE';
	  $query2 .= ', "'.$db -> escape($p['BE']).'"';
	}
	
	//57
	if(isset($p['BF']) && ($p['BF'] != null)){
	  $query1 .= ', BF';
	  $query2 .= ', "'.$db -> escape($p['BF']).'"';
	}
	
	//58
	if(isset($p['BG']) && ($p['BG'] != null)){
	  $query1 .= ', BG';
	  $query2 .= ', "'.$db -> escape($p['BG']).'"';
	}
	
	//59
	if(isset($p['BH']) && ($p['BH'] != null)){
	  $query1 .= ', BH';
	  $query2 .= ', "'.$db -> escape($p['BH']).'"';
	}
	
	//60
	if(isset($p['BI']) && ($p['BI'] != null)){
	  $query1 .= ', BI';
	  $query2 .= ', "'.$db -> escape($p['BI']).'"';
	}
	
	//61
	if(isset($p['BJ']) && ($p['BJ'] != null)){
	  $query1 .= ', BJ';
	  $query2 .= ', "'.$db -> escape($p['BJ']).'"';
	}
	
	//62
	if(isset($p['BK']) && ($p['BK'] != null)){
	  $query1 .= ', BK';
	  $query2 .= ', "'.$db -> escape($p['BK']).'"';
	}
	
	//63
	if(isset($p['BL']) && ($p['BL'] != null)){
	  $query1 .= ', BL';
	  $query2 .= ', "'.$db -> escape($p['BL']).'"';
	}
	
	//64
	if(isset($p['BM']) && ($p['BM'] != null)){
	  $query1 .= ', BM';
	  $query2 .= ', "'.$db -> escape($p['BM']).'"';
	}
	
	//65
	if(isset($p['BN']) && ($p['BN'] != null)){
	  $query1 .= ', BN';
	  $query2 .= ', "'.$db -> escape($p['BN']).'"';
	}
	
	//66
	if(isset($p['BO']) && ($p['BO'] != null)){
	  $query1 .= ', BO';
	  $query2 .= ', "'.$db -> escape($p['BO']).'"';
	}
	
	//67
	if(isset($p['BP']) && ($p['BP'] != null)){
	  $query1 .= ', BP';
	  $query2 .= ', "'.$db -> escape($p['BP']).'"';
	}
	
	//68
	if(isset($p['BQ']) && ($p['BQ'] != null)){
	  $query1 .= ', BQ';
	  $query2 .= ', "'.$db -> escape($p['BQ']).'"';
	}
	
	//69
	if(isset($p['BR']) && ($p['BR'] != null)){
	  $query1 .= ', BR';
	  $query2 .= ', "'.$db -> escape($p['BR']).'"';
	}
	
	//70
	if(isset($p['BS']) && ($p['BS'] != null)){
	  $query1 .= ', BS';
	  $query2 .= ', "'.$db -> escape($p['BS']).'"';
	}
	
	//71
	if(isset($p['BT']) && ($p['BT'] != null)){
	  $query1 .= ', BT';
	  $query2 .= ', "'.$db -> escape($p['BT']).'"';
	}
	
	//72
	if(isset($p['BU']) && ($p['BU'] != null)){
	  $query1 .= ', BU';
	  $query2 .= ', "'.$db -> escape($p['BU']).'"';
	}
	
	//73
	if(isset($p['BV']) && ($p['BV'] != null)){
	  $query1 .= ', BV';
	  $query2 .= ', "'.$db -> escape($p['BV']).'"';
	}
	
	//74
	if(isset($p['BW']) && ($p['BW'] != null)){
	  $query1 .= ', BW';
	  $query2 .= ', "'.$db -> escape($p['BW']).'"';
	}
	
	//75
	if(isset($p['BX']) && ($p['BX'] != null)){
	  $query1 .= ', BX';
	  $query2 .= ', "'.$db -> escape($p['BX']).'"';
	}
	
	//76
	if(isset($p['BY1']) && ($p['BY1'] != null)){
	  $query1 .= ', BY1';
	  $query2 .= ', "'.$db -> escape($p['BY1']).'"';
	}
	
	//77
	if(isset($p['BZ']) && ($p['BZ'] != null)){
	  $query1 .= ', BZ';
	  $query2 .= ', "'.$db -> escape($p['BZ']).'"';
	}
	
	//78
	if(isset($p['CA']) && ($p['CA'] != null)){
	  $query1 .= ', CA';
	  $query2 .= ', "'.$db -> escape($p['CA']).'"';
	}
	
	//79
	if(isset($p['CB']) && ($p['CB'] != null)){
	  $query1 .= ', CB';
	  $query2 .= ', "'.$db -> escape($p['CB']).'"';
	}
	
	//80
	if(isset($p['CC']) && ($p['CC'] != null)){
	  $query1 .= ', CC';
	  $query2 .= ', "'.$db -> escape($p['CC']).'"';
	}
	
	//81
	if(isset($p['CD']) && ($p['CD'] != null)){
	  $query1 .= ', CD';
	  $query2 .= ', "'.$db -> escape($p['CD']).'"';
	}
	
	//82
	if(isset($p['CE']) && ($p['CE'] != null)){
	  $query1 .= ', CE';
	  $query2 .= ', "'.$db -> escape($p['CE']).'"';
	}
	
	//83
	if(isset($p['CF']) && ($p['CF'] != null)){
	  $query1 .= ', CF';
	  $query2 .= ', "'.$db -> escape($p['CF']).'"';
	}
	
	//84
	if(isset($p['CG']) && ($p['CG'] != null)){
	  $query1 .= ', CG';
	  $query2 .= ', "'.$db -> escape($p['CG']).'"';
	}
	
	//85
	if(isset($p['CH']) && ($p['CH'] != null)){
	  $query1 .= ', CH';
	  $query2 .= ', "'.$db -> escape($p['CH']).'"';
	}
	
	//86
	if(isset($p['CI']) && ($p['CI'] != null)){
	  $query1 .= ', CI';
	  $query2 .= ', "'.$db -> escape($p['CI']).'"';
	}
	
	//87
	if(isset($p['CJ']) && ($p['CJ'] != null)){
	  $query1 .= ', CJ';
	  $query2 .= ', "'.$db -> escape($p['CJ']).'"';
	}
	
	//88
	if(isset($p['CK']) && ($p['CK'] != null)){
	  $query1 .= ', CK';
	  $query2 .= ', "'.$db -> escape($p['CK']).'"';
	}
	
	//89
	if(isset($p['CL']) && ($p['CL'] != null)){
	  $query1 .= ', CL';
	  $query2 .= ', "'.$db -> escape($p['CL']).'"';
	}
	
	//90
	if(isset($p['CM']) && ($p['CM'] != null)){
	  $query1 .= ', CM';
	  $query2 .= ', "'.$db -> escape($p['CM']).'"';
	}
	
	//91
	if(isset($p['CN']) && ($p['CN'] != null)){
	  $query1 .= ', CN';
	  $query2 .= ', "'.$db -> escape($p['CN']).'"';
	}
	
	//92
	if(isset($p['CO']) && ($p['CO'] != null)){
	  $query1 .= ', CO';
	  $query2 .= ', "'.$db -> escape($p['CO']).'"';
	}
	
	//93
	if(isset($p['CP']) && ($p['CP'] != null)){
	  $query1 .= ', CP';
	  $query2 .= ', "'.$db -> escape($p['CP']).'"';
	}
	
	//94
	if(isset($p['CQ']) && ($p['CQ'] != null)){
	  $query1 .= ', CQ';
	  $query2 .= ', "'.$db -> escape($p['CQ']).'"';
	}
	
	//95
	if(isset($p['CR']) && ($p['CR'] != null)){
	  $query1 .= ', CR';
	  $query2 .= ', "'.$db -> escape($p['CR']).'"';
	}
	
	//96
	if(isset($p['CS']) && ($p['CS'] != null)){
	  $query1 .= ', CS';
	  $query2 .= ', "'.$db -> escape($p['CS']).'"';
	}
	
	//97
	if(isset($p['CT']) && ($p['CT'] != null)){
	  $query1 .= ', CT';
	  $query2 .= ', "'.$db -> escape($p['CT']).'"';
	}
	
	//98
	if(isset($p['CU']) && ($p['CU'] != null)){
	  $query1 .= ', CU';
	  $query2 .= ', "'.$db -> escape($p['CU']).'"';
	}
	
	//99
	if(isset($p['CV']) && ($p['CV'] != null)){
	  $query1 .= ', CV';
	  $query2 .= ', "'.$db -> escape($p['CV']).'"';
	}
	
	//100
	if(isset($p['CW']) && ($p['CW'] != null)){
	  $query1 .= ', CW';
	  $query2 .= ', "'.$db -> escape($p['CW']).'"';
	}
	
	//101
	if(isset($p['CX']) && ($p['CX'] != null)){
	  $query1 .= ', CX';
	  $query2 .= ', "'.$db -> escape($p['CX']).'"';
	}
	
	//102
	if(isset($p['CY']) && ($p['CY'] != null)){
	  $query1 .= ', CY';
	  $query2 .= ', "'.$db -> escape($p['CY']).'"';
	}
	
	//103
	if(isset($p['CZ']) && ($p['CZ'] != null)){
	  $query1 .= ', CZ';
	  $query2 .= ', "'.$db -> escape($p['CZ']).'"';
	}
	
	//104
	if(isset($p['DA']) && ($p['DA'] != null)){
	  $query1 .= ', DA';
	  $query2 .= ', "'.$db -> escape($p['DA']).'"';
	}
	
	//105
	if(isset($p['DB']) && ($p['DB'] != null)){
	  $query1 .= ', DB';
	  $query2 .= ', "'.$db -> escape($p['DB']).'"';
	}
	
	//106
	if(isset($p['DC']) && ($p['DC'] != null)){
	  $query1 .= ', DC';
	  $query2 .= ', "'.$db -> escape($p['DC']).'"';
	}
	
	//107
	if(isset($p['DD']) && ($p['DD'] != null)){
	  $query1 .= ', DD';
	  $query2 .= ', "'.$db -> escape($p['DD']).'"';
	}
	
	//108
	if(isset($p['DE']) && ($p['DE'] != null)){
	  $query1 .= ', DE';
	  $query2 .= ', "'.$db -> escape($p['DE']).'"';
	}
	
	//109
	if(isset($p['DF']) && ($p['DF'] != null)){
	  $query1 .= ', DF';
	  $query2 .= ', "'.$db -> escape($p['DF']).'"';
	}
	
	//110
	if(isset($p['DG']) && ($p['DG'] != null)){
	  $query1 .= ', DG';
	  $query2 .= ', "'.$db -> escape($p['DG']).'"';
	}
	
	//111
	if(isset($p['DH']) && ($p['DH'] != null)){
	  $query1 .= ', DH';
	  $query2 .= ', "'.$db -> escape($p['DH']).'"';
	}
	
	//112
	if(isset($p['DI']) && ($p['DI'] != null)){
	  $query1 .= ', DI';
	  $query2 .= ', "'.$db -> escape($p['DI']).'"';
	}
	
	//113
	if(isset($p['DJ']) && ($p['DJ'] != null)){
	  $query1 .= ', DJ';
	  $query2 .= ', "'.$db -> escape($p['DJ']).'"';
	}
	
	//114
	if(isset($p['DK']) && ($p['DK'] != null)){
	  $query1 .= ', DK';
	  $query2 .= ', "'.$db -> escape($p['DK']).'"';
	}
	
	//115
	if(isset($p['DL']) && ($p['DL'] != null)){
	  $query1 .= ', DL';
	  $query2 .= ', "'.$db -> escape($p['DL']).'"';
	}
	
	//116
	if(isset($p['DM']) && ($p['DM'] != null)){
	  $query1 .= ', DM';
	  $query2 .= ', "'.$db -> escape($p['DM']).'"';
	}
	
	//117
	if(isset($p['DN']) && ($p['DN'] != null)){
	  $query1 .= ', DN';
	  $query2 .= ', "'.$db -> escape($p['DN']).'"';
	}
	
	//118
	if(isset($p['DO']) && ($p['DO'] != null)){
	  $query1 .= ', DO';
	  $query2 .= ', "'.$db -> escape($p['DO']).'"';
	}
	
	//119
	if(isset($p['DP']) && ($p['DP'] != null)){
	  $query1 .= ', DP';
	  $query2 .= ', "'.$db -> escape($p['DP']).'"';
	}
	
	//120
	if(isset($p['DQ']) && ($p['DQ'] != null)){
	  $query1 .= ', DQ';
	  $query2 .= ', "'.$db -> escape($p['DQ']).'"';
	}
	
	//121
	if(isset($p['DR']) && ($p['DR'] != null)){
	  $query1 .= ', DR';
	  $query2 .= ', "'.$db -> escape($p['DR']).'"';
	}
	
	//122
	if(isset($p['DS']) && ($p['DS'] != null)){
	  $query1 .= ', DS';
	  $query2 .= ', "'.$db -> escape($p['DS']).'"';
	}
	
	//123
	if(isset($p['DT']) && ($p['DT'] != null)){
	  $query1 .= ', DT';
	  $query2 .= ', "'.$db -> escape($p['DT']).'"';
	}
	
	//124
	if(isset($p['DU']) && ($p['DU'] != null)){
	  $query1 .= ', DU';
	  $query2 .= ', "'.$db -> escape($p['DU']).'"';
	}
	
	//125
	if(isset($p['DV']) && ($p['DV'] != null)){
	  $query1 .= ', DV';
	  $query2 .= ', "'.$db -> escape($p['DV']).'"';
	}
	
	//126
	if(isset($p['DW']) && ($p['DW'] != null)){
	  $query1 .= ', DW';
	  $query2 .= ', "'.$db -> escape($p['DW']).'"';
	}
	
	//127
	if(isset($p['DX']) && ($p['DX'] != null)){
	  $query1 .= ', DX';
	  $query2 .= ', "'.$db -> escape($p['DX']).'"';
	}
	
	//128
	if(isset($p['DY']) && ($p['DY'] != null)){
	  $query1 .= ', DY';
	  $query2 .= ', "'.$db -> escape($p['DY']).'"';
	}
	
	//129
	if(isset($p['DZ']) && ($p['DZ'] != null)){
	  $query1 .= ', DZ';
	  $query2 .= ', "'.$db -> escape($p['DZ']).'"';
	}
	
	//130
	if(isset($p['EA']) && ($p['EA'] != null)){
	  $query1 .= ', EA';
	  $query2 .= ', "'.$db -> escape($p['EA']).'"';
	}
	
	//131
	if(isset($p['EB']) && ($p['EB'] != null)){
	  $query1 .= ', EB';
	  $query2 .= ', "'.$db -> escape($p['EB']).'"';
	}
	
	//132
	if(isset($p['EC']) && ($p['EC'] != null)){
	  $query1 .= ', EC';
	  $query2 .= ', "'.$db -> escape($p['EC']).'"';
	}
	
	//133
	if(isset($p['ED']) && ($p['ED'] != null)){
	  $query1 .= ', ED';
	  $query2 .= ', "'.$db -> escape($p['ED']).'"';
	}
	
	//134
	if(isset($p['EE']) && ($p['EE'] != null)){
	  $query1 .= ', EE';
	  $query2 .= ', "'.$db -> escape($p['EE']).'"';
	}
	
	//135
	if(isset($p['EF']) && ($p['EF'] != null)){
	  $query1 .= ', EF';
	  $query2 .= ', "'.$db -> escape($p['EF']).'"';
	}
	
	//136
	if(isset($p['EG']) && ($p['EG'] != null)){
	  $query1 .= ', EG';
	  $query2 .= ', "'.$db -> escape($p['EG']).'"';
	}
	
	//137
	if(isset($p['EH']) && ($p['EH'] != null)){
	  $query1 .= ', EH';
	  $query2 .= ', "'.$db -> escape($p['EH']).'"';
	}
	
	//138
	if(isset($p['EI']) && ($p['EI'] != null)){
	  $query1 .= ', EI';
	  $query2 .= ', "'.$db -> escape($p['EI']).'"';
	}
	
	//139
	if(isset($p['EJ']) && ($p['EJ'] != null)){
	  $query1 .= ', EJ';
	  $query2 .= ', "'.$db -> escape($p['EJ']).'"';
	}
	
	//140
	if(isset($p['EK']) && ($p['EK'] != null)){
	  $query1 .= ', EK';
	  $query2 .= ', "'.$db -> escape($p['EK']).'"';
	}
	
	//141
	if(isset($p['EL']) && ($p['EL'] != null)){
	  $query1 .= ', EL';
	  $query2 .= ', "'.$db -> escape($p['EL']).'"';
	}
	
	//142
	if(isset($p['EM']) && ($p['EM'] != null)){
	  $query1 .= ', EM';
	  $query2 .= ', "'.$db -> escape($p['EM']).'"';
	}
	
	//143
	if(isset($p['EN']) && ($p['EN'] != null)){
	  $query1 .= ', EN';
	  $query2 .= ', "'.$db -> escape($p['EN']).'"';
	}
	
	//144
	if(isset($p['EO']) && ($p['EO'] != null)){
	  $query1 .= ', EO';
	  $query2 .= ', "'.$db -> escape($p['EO']).'"';
	}
	
	//145
	if(isset($p['EP']) && ($p['EP'] != null)){
	  $query1 .= ', EP';
	  $query2 .= ', "'.$db -> escape($p['EP']).'"';
	}
	
	//146
	if(isset($p['EQ']) && ($p['EQ'] != null)){
	  $query1 .= ', EQ';
	  $query2 .= ', "'.$db -> escape($p['EQ']).'"';
	}
	
	//147
	if(isset($p['ER']) && ($p['ER'] != null)){
	  $query1 .= ', ER';
	  $query2 .= ', "'.$db -> escape($p['ER']).'"';
	}
	
	//148
	if(isset($p['ES']) && ($p['ES'] != null)){
	  $query1 .= ', ES';
	  $query2 .= ', "'.$db -> escape($p['ES']).'"';
	}
	
	//149
	if(isset($p['ET']) && ($p['ET'] != null)){
	  $query1 .= ', ET';
	  $query2 .= ', "'.$db -> escape($p['ET']).'"';
	}
	
	//150
	if(isset($p['EU']) && ($p['EU'] != null)){
	  $query1 .= ', EU';
	  $query2 .= ', "'.$db -> escape($p['EU']).'"';
	}
	
	//151
	if(isset($p['EV']) && ($p['EV'] != null)){
	  $query1 .= ', EV';
	  $query2 .= ', "'.$db -> escape($p['EV']).'"';
	}
	
	//152
	if(isset($p['EW']) && ($p['EW'] != null)){
	  $query1 .= ', EW';
	  $query2 .= ', "'.$db -> escape($p['EW']).'"';
	}
	
	//153
	if(isset($p['EX']) && ($p['EX'] != null)){
	  $query1 .= ', EX';
	  $query2 .= ', "'.$db -> escape($p['EX']).'"';
	}
	
	//154
	if(isset($p['EY']) && ($p['EY'] != null)){
	  $query1 .= ', EY';
	  $query2 .= ', "'.$db -> escape($p['EY']).'"';
	}
	
	//155
	if(isset($p['EZ']) && ($p['EZ'] != null)){
	  $query1 .= ', EZ';
	  $query2 .= ', "'.$db -> escape($p['EZ']).'"';
	}
	
	//156
	if(isset($p['FA']) && ($p['FA'] != null)){
	  $query1 .= ', FA';
	  $query2 .= ', "'.$db -> escape($p['FA']).'"';
	}
	
	//157
	if(isset($p['FB']) && ($p['FB'] != null)){
	  $query1 .= ', FB';
	  $query2 .= ', "'.$db -> escape($p['FB']).'"';
	}
	
	//158
	if(isset($p['FC']) && ($p['FC'] != null)){
	  $query1 .= ', FC';
	  $query2 .= ', "'.$db -> escape($p['FC']).'"';
	}	
	
	//159
	if(isset($p['FD']) && ($p['FD'] != null)){
	  $query1 .= ', FD';
	  $query2 .= ', "'.$db -> escape($p['FD']).'"';
	}	
	
	//160
	if(isset($p['FE']) && ($p['FE'] != null)){
	  $query1 .= ', FE';
	  $query2 .= ', "'.$db -> escape($p['FE']).'"';
	}	
	
	//161
	if(isset($p['FF']) && ($p['FF'] != null)){
	  $query1 .= ', FF';
	  $query2 .= ', "'.$db -> escape($p['FF']).'"';
	}	
	
	//162
	if(isset($p['FG']) && ($p['FG'] != null)){
	  $query1 .= ', FG';
	  $query2 .= ', "'.$db -> escape($p['FG']).'"';
	}	
	
	//163
	if(isset($p['FH']) && ($p['FH'] != null)){
	  $query1 .= ', FH';
	  $query2 .= ', "'.$db -> escape($p['FH']).'"';
	}	
	
	//164
	if(isset($p['FI']) && ($p['FI'] != null)){
	  $query1 .= ', FI';
	  $query2 .= ', "'.$db -> escape($p['FI']).'"';
	}	
	
	//165
	if(isset($p['FJ']) && ($p['FJ'] != null)){
	  $query1 .= ', FJ';
	  $query2 .= ', "'.$db -> escape($p['FJ']).'"';
	}	
	
	//166
	if(isset($p['FK']) && ($p['FK'] != null)){
	  $query1 .= ', FK';
	  $query2 .= ', "'.$db -> escape($p['FK']).'"';
	}	
	
	//167
	if(isset($p['FL']) && ($p['FL'] != null)){
	  $query1 .= ', FL';
	  $query2 .= ', "'.$db -> escape($p['FL']).'"';
	}	
	
	//168
	if(isset($p['FM']) && ($p['FM'] != null)){
	  $query1 .= ', FM';
	  $query2 .= ', "'.$db -> escape($p['FM']).'"';
	}	
	
	//169
	if(isset($p['FN']) && ($p['FN'] != null)){
	  $query1 .= ', FN';
	  $query2 .= ', "'.$db -> escape($p['FN']).'"';
	}	
	
	//170
	if(isset($p['FO']) && ($p['FO'] != null)){
	  $query1 .= ', FO';
	  $query2 .= ', "'.$db -> escape($p['FO']).'"';
	}	
	
	//mobileNew
	if(isset($p['mobileNew']) && ($p['mobileNew'] != null)){
	  $query1 .= ', mobileNew';
	  $query2 .= ', "'.$db -> escape($p['mobileNew']).'"';
	}
	
	//userID
	if(isset($p['userID']) && ($p['userID'] != null)){
	  $query1 .= ', userID';
	  $query2 .= ', "'.$db -> escape($p['userID']).'"';
	}
	
	//vType
	if(isset($p['vType']) && ($p['vType'] != null)){
	  $query1 .= ', vType';
	  $query2 .= ', "'.$db -> escape($p['vType']).'"';
	}
	
	//Build complete query
	$query = '	INSERT INTO mobile( '.$query1.' ) VALUES ( '.$query2.' )';

	if (isset($p['print']) && ($p['print'] == true)){
		echo $query;
	}
	$return = $db->execQuery(array('q'=>$query));

	return $return;
}
?>
