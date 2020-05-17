<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20110623
 * Desc:		This function update an AS24 data set
 *********************************************************************************/
include_once('classes/DB.php');

function db_updAS24($p=null){
	$return = false;
	
	$db = DB::getInstance();
	$query1 = '';
	$query2 = '';
	
	if (isset($p[System_Properties::SQL_SET])){
		$pSet = $p[System_Properties::SQL_SET];

		//1
		if(isset($pSet['A']) && ($pSet['A'] != null)){
		  $query1 .= ', A = "'.$db -> escape($pSet['A']).'"';
		}
		
		//2
		if(isset($pSet['B']) && ($pSet['B'] != null)){
		  $query1 .= ', B = "'.$db -> escape($pSet['B']).'"';
		}
		
		//3
		if(isset($pSet['C']) && ($pSet['C'] != null)){
		  $query1 .= ', C = "'.$db -> escape($pSet['C']).'"';
		}
		
		//4
		if(isset($pSet['D']) && ($pSet['D'] != null)){
		  $query1 .= ', D = "'.$db -> escape($pSet['D']).'"';
		}
		
		//5
		if(isset($pSet['E']) && ($pSet['E'] != null)){
		  $query1 .= ', E = "'.$db -> escape($pSet['E']).'"';
		}
		
		//6
		if(isset($pSet['F']) && ($pSet['F'] != null)){
		  $query1 .= ', F = "'.$db -> escape($pSet['F']).'"';
		}
		
		//7
		if(isset($pSet['G']) && ($pSet['G'] != null)){
		  $query1 .= ', G = "'.$db -> escape($pSet['G']).'"';
		}
		
		//8
		if(isset($pSet['H']) && ($pSet['H'] != null)){
		  $query1 .= ', H = "'.$db -> escape($pSet['H']).'"';
		}
		
		//9
		if(isset($pSet['I']) && ($pSet['I'] != null)){
		  $query1 .= ', I = "'.$db -> escape($pSet['I']).'"';
		}
		
		//10
		if(isset($pSet['J']) && ($pSet['J'] != null)){
		  $query1 .= ', J = "'.$db -> escape($pSet['J']).'"';
		}
		
		//11
		if(isset($pSet['K']) && ($pSet['K'] != null)){
		  $query1 .= ', K = "'.$db -> escape($pSet['K']).'"';
		}
		
		//12
		if(isset($pSet['L']) && ($pSet['L'] != null)){
		  $query1 .= ', L = "'.$db -> escape($pSet['L']).'"';
		}
		
		//13
		if(isset($pSet['M']) && ($pSet['M'] != null)){
		  $query1 .= ', M = "'.$db -> escape($pSet['M']).'"';
		}
		
		//14
		if(isset($pSet['N']) && ($pSet['N'] != null)){
		  $query1 .= ', N = "'.$db -> escape($pSet['N']).'"';
		}
		
		//15
		if(isset($pSet['O']) && ($pSet['O'] != null)){
		  $query1 .= ', O = "'.$db -> escape($pSet['O']).'"';
		}
		
		//16
		if(isset($pSet['P']) && ($pSet['P'] != null)){
		  $query1 .= ', P = "'.$db -> escape($pSet['P']).'"';
		}
		
		//17
		if(isset($pSet['Q']) && ($pSet['Q'] != null)){
		  $query1 .= ', Q = "'.$db -> escape($pSet['Q']).'"';
		}
		
		//18
		if(isset($pSet['R']) && ($pSet['R'] != null)){
		  $query1 .= ', R = "'.$db -> escape($pSet['R']).'"';
		}
		
		//19
		if(isset($pSet['S']) && ($pSet['S'] != null)){
		  $query1 .= ', S = "'.$db -> escape($pSet['S']).'"';
		}
		
		//20
		if(isset($pSet['T']) && ($pSet['T'] != null)){
		  $query1 .= ', T = "'.$db -> escape($pSet['T']).'"';
		}
		
		//21
		if(isset($pSet['U']) && ($pSet['U'] != null)){
		  $query1 .= ', U = "'.$db -> escape($pSet['U']).'"';
		}
		
		//22
		if(isset($pSet['V']) && ($pSet['V'] != null)){
		  $query1 .= ', V = "'.$db -> escape($pSet['V']).'"';
		}
		
		//23
		if(isset($pSet['W']) && ($pSet['W'] != null)){
		  $query1 .= ', W = "'.$db -> escape($pSet['W']).'"';
		}
		
		//24
		if(isset($pSet['X']) && ($pSet['X'] != null)){
		  $query1 .= ', X = "'.$db -> escape($pSet['X']).'"';
		}
		
		//25
		if(isset($pSet['Y']) && ($pSet['Y'] != null)){
		  $query1 .= ', Y = "'.$db -> escape($pSet['Y']).'"';
		}
		
		//26
		if(isset($pSet['Z']) && ($pSet['Z'] != null)){
		  $query1 .= ', Z = "'.$db -> escape($pSet['Z']).'"';
		}
		
		//27
		if(isset($pSet['AA']) && ($pSet['AA'] != null)){
		  $query1 .= ', AA = "'.$db -> escape($pSet['AA']).'"';
		}
		
		//28
		if(isset($pSet['AB']) && ($pSet['AB'] != null)){
		  $query1 .= ', AB = "'.$db -> escape($pSet['AB']).'"';
		}
		
		//29
		if(isset($pSet['AC']) && ($pSet['AC'] != null)){
		  $query1 .= ', AC = "'.$db -> escape($pSet['AC']).'"';
		}
		
		//30
		if(isset($pSet['AD']) && ($pSet['AD'] != null)){
		  $query1 .= ', AD = "'.$db -> escape($pSet['AD']).'"';
		}
		
		//31
		if(isset($pSet['AE']) && ($pSet['AE'] != null)){
		  $query1 .= ', AE = "'.$db -> escape($pSet['AE']).'"';
		}
		
		//32
		if(isset($pSet['AF']) && ($pSet['AF'] != null)){
		  $query1 .= ', AF = "'.$db -> escape($pSet['AF']).'"';
		}
		
		//33
		if(isset($pSet['AG']) && ($pSet['AG'] != null)){
		  $query1 .= ', AG = "'.$db -> escape($pSet['AG']).'"';
		}
		
		//34
		if(isset($pSet['AH']) && ($pSet['AH'] != null)){
		  $query1 .= ', AH = "'.$db -> escape($pSet['AH']).'"';
		}
		
		//35
		if(isset($pSet['AI']) && ($pSet['AI'] != null)){
		  $query1 .= ', AI = "'.$db -> escape($pSet['AI']).'"';
		}
		
		//36
		if(isset($pSet['AJ']) && ($pSet['AJ'] != null)){
		  $query1 .= ', AJ = "'.$db -> escape($pSet['AJ']).'"';
		}
		
		//37
		if(isset($pSet['AK']) && ($pSet['AK'] != null)){
		  $query1 .= ', AK = "'.$db -> escape($pSet['AK']).'"';
		}
		
		//38
		if(isset($pSet['AL']) && ($pSet['AL'] != null)){
		  $query1 .= ', AL = "'.$db -> escape($pSet['AL']).'"';
		}
		
		//39
		if(isset($pSet['AM']) && ($pSet['AM'] != null)){
		  $query1 .= ', AM = "'.$db -> escape($pSet['AM']).'"';
		}
		
		//40
		if(isset($pSet['AN']) && ($pSet['AN'] != null)){
		  $query1 .= ', AN = "'.$db -> escape($pSet['AN']).'"';
		}
		
		//41
		if(isset($pSet['AO']) && ($pSet['AO'] != null)){
		  $query1 .= ', AO = "'.$db -> escape($pSet['AO']).'"';
		}
		
		//42
		if(isset($pSet['AP']) && ($pSet['AP'] != null)){
		  $query1 .= ', AP = "'.$db -> escape($pSet['AP']).'"';
		}
		
		//43
		if(isset($pSet['AQ']) && ($pSet['AQ'] != null)){
		  $query1 .= ', AQ = "'.$db -> escape($pSet['AQ']).'"';
		}
		
		//44
		if(isset($pSet['AR']) && ($pSet['AR'] != null)){
		  $query1 .= ', AR = "'.$db -> escape($pSet['AR']).'"';
		}
		
		//45
		if(isset($pSet['AS']) && ($pSet['AS'] != null)){
		  $query1 .= ', AS = "'.$db -> escape($pSet['AS']).'"';
		}
		
		//46
		if(isset($pSet['AT']) && ($pSet['AT'] != null)){
		  $query1 .= ', AT = "'.$db -> escape($pSet['AT']).'"';
		}
		
		//47
		if(isset($pSet['AU']) && ($pSet['AU'] != null)){
		  $query1 .= ', AU = "'.$db -> escape($pSet['AU']).'"';
		}
		
		//48
		if(isset($pSet['AV']) && ($pSet['AV'] != null)){
		  $query1 .= ', AV = "'.$db -> escape($pSet['AV']).'"';
		}
		
		//49
		if(isset($pSet['AW']) && ($pSet['AW'] != null)){
		  $query1 .= ', AW = "'.$db -> escape($pSet['AW']).'"';
		}
		
		//50
		if(isset($pSet['AX']) && ($pSet['AX'] != null)){
		  $query1 .= ', AX = "'.$db -> escape($pSet['AX']).'"';
		}
		
		//51
		if(isset($pSet['AY']) && ($pSet['AY'] != null)){
		  $query1 .= ', AY = "'.$db -> escape($pSet['AY']).'"';
		}
		
		//52
		if(isset($pSet['AZ']) && ($pSet['AZ'] != null)){
		  $query1 .= ', AZ = "'.$db -> escape($pSet['AZ']).'"';
		}
		
		//53
		if(isset($pSet['BA']) && ($pSet['BA'] != null)){
		  $query1 .= ', BA = "'.$db -> escape($pSet['BA']).'"';
		}
		
		//54
		if(isset($pSet['BB']) && ($pSet['BB'] != null)){
		  $query1 .= ', BB = "'.$db -> escape($pSet['BB']).'"';
		}
		
		//55
		if(isset($pSet['BC']) && ($pSet['BC'] != null)){
		  $query1 .= ', BC = "'.$db -> escape($pSet['BC']).'"';
		}
		
		//56
		if(isset($pSet['BD']) && ($pSet['BD'] != null)){
		  $query1 .= ', BD = "'.$db -> escape($pSet['BD']).'"';
		}
		
		//57
		if(isset($pSet['BE']) && ($pSet['BE'] != null)){
		  $query1 .= ', BE = "'.$db -> escape($pSet['BE']).'"';
		}
		
		//58
		if(isset($pSet['BF']) && ($pSet['BF'] != null)){
		  $query1 .= ', BF = "'.$db -> escape($pSet['BF']).'"';
		}
		
		//59
		if(isset($pSet['BG']) && ($pSet['BG'] != null)){
		  $query1 .= ', BG = "'.$db -> escape($pSet['BG']).'"';
		}
		
		//60
		if(isset($pSet['BH']) && ($pSet['BH'] != null)){
		  $query1 .= ', BH = "'.$db -> escape($pSet['BH']).'"';
		}
		
		//61
		if(isset($pSet['BI']) && ($pSet['BI'] != null)){
		  $query1 .= ', BI = "'.$db -> escape($pSet['BI']).'"';
		}
		
		//62
		if(isset($pSet['BJ']) && ($pSet['BJ'] != null)){
		  $query1 .= ', BJ = "'.$db -> escape($pSet['BJ']).'"';
		}
		
		//63
		if(isset($pSet['BK']) && ($pSet['BK'] != null)){
		  $query1 .= ', BK = "'.$db -> escape($pSet['BK']).'"';
		}
		
		//64
		if(isset($pSet['BL']) && ($pSet['BL'] != null)){
		  $query1 .= ', BL = "'.$db -> escape($pSet['BL']).'"';
		}
		
		//65
		if(isset($pSet['BM']) && ($pSet['BM'] != null)){
		  $query1 .= ', BM = "'.$db -> escape($pSet['BM']).'"';
		}
		
		//66
		if(isset($pSet['BN']) && ($pSet['BN'] != null)){
		  $query1 .= ', BN = "'.$db -> escape($pSet['BN']).'"';
		}
		
		//67
		if(isset($pSet['BO']) && ($pSet['BO'] != null)){
		  $query1 .= ', BO = "'.$db -> escape($pSet['BO']).'"';
		}
		
		//68
		if(isset($pSet['BP']) && ($pSet['BP'] != null)){
		  $query1 .= ', BP = "'.$db -> escape($pSet['BP']).'"';
		}
		
		//69
		if(isset($pSet['BQ']) && ($pSet['BQ'] != null)){
		  $query1 .= ', BQ = "'.$db -> escape($pSet['BQ']).'"';
		}
		
		//70
		if(isset($pSet['BR']) && ($pSet['BR'] != null)){
		  $query1 .= ', BR = "'.$db -> escape($pSet['BR']).'"';
		}
		
		//71
		if(isset($pSet['BS']) && ($pSet['BS'] != null)){
		  $query1 .= ', BS = "'.$db -> escape($pSet['BS']).'"';
		}
		
		//72
		if(isset($pSet['BT']) && ($pSet['BT'] != null)){
		  $query1 .= ', BT = "'.$db -> escape($pSet['BT']).'"';
		}
		
		//73
		if(isset($pSet['BU']) && ($pSet['BU'] != null)){
		  $query1 .= ', BU = "'.$db -> escape($pSet['BU']).'"';
		}
		
		//74
		if(isset($pSet['BV']) && ($pSet['BV'] != null)){
		  $query1 .= ', BV = "'.$db -> escape($pSet['BV']).'"';
		}
		
		//75
		if(isset($pSet['BW']) && ($pSet['BW'] != null)){
		  $query1 .= ', BW = "'.$db -> escape($pSet['BW']).'"';
		}
		
		//76
		if(isset($pSet['BX']) && ($pSet['BX'] != null)){
		  $query1 .= ', BX = "'.$db -> escape($pSet['BX']).'"';
		}
		
		//77
		if(isset($pSet['BY']) && ($pSet['BY'] != null)){
		  $query1 .= ', BY = "'.$db -> escape($pSet['BY']).'"';
		}
		
		//78
		if(isset($pSet['BZ']) && ($pSet['BZ'] != null)){
		  $query1 .= ', BZ = "'.$db -> escape($pSet['BZ']).'"';
		}
		
		//79
		if(isset($pSet['CA']) && ($pSet['CA'] != null)){
		  $query1 .= ', CA = "'.$db -> escape($pSet['CA']).'"';
		}
		
		//80
		if(isset($pSet['CB']) && ($pSet['CB'] != null)){
		  $query1 .= ', CB = "'.$db -> escape($pSet['CB']).'"';
		}
		
		//81
		if(isset($pSet['CC']) && ($pSet['CC'] != null)){
		  $query1 .= ', CC = "'.$db -> escape($pSet['CC']).'"';
		}
		
		//82
		if(isset($pSet['CD']) && ($pSet['CD'] != null)){
		  $query1 .= ', CD = "'.$db -> escape($pSet['CD']).'"';
		}
		
		//83
		if(isset($pSet['CE']) && ($pSet['CE'] != null)){
		  $query1 .= ', CE = "'.$db -> escape($pSet['CE']).'"';
		}
		
		//84
		if(isset($pSet['CF']) && ($pSet['CF'] != null)){
		  $query1 .= ', CF = "'.$db -> escape($pSet['CF']).'"';
		}
		
		//85
		if(isset($pSet['CG']) && ($pSet['CG'] != null)){
		  $query1 .= ', CG = "'.$db -> escape($pSet['CG']).'"';
		}
		
		//86
		if(isset($pSet['CH']) && ($pSet['CH'] != null)){
		  $query1 .= ', CH = "'.$db -> escape($pSet['CH']).'"';
		}
		
		//87
		if(isset($pSet['CI']) && ($pSet['CI'] != null)){
		  $query1 .= ', CI = "'.$db -> escape($pSet['CI']).'"';
		}
		
		//88
		if(isset($pSet['CJ']) && ($pSet['CJ'] != null)){
		  $query1 .= ', CJ = "'.$db -> escape($pSet['CJ']).'"';
		}
		
		//89
		if(isset($pSet['CK']) && ($pSet['CK'] != null)){
		  $query1 .= ', CK = "'.$db -> escape($pSet['CK']).'"';
		}
		
		//90
		if(isset($pSet['CL']) && ($pSet['CL'] != null)){
		  $query1 .= ', CL = "'.$db -> escape($pSet['CL']).'"';
		}
		
		//91
		if(isset($pSet['CM']) && ($pSet['CM'] != null)){
		  $query1 .= ', CM = "'.$db -> escape($pSet['CM']).'"';
		}
		
		//92
		if(isset($pSet['CN']) && ($pSet['CN'] != null)){
		  $query1 .= ', CN = "'.$db -> escape($pSet['CN']).'"';
		}
		
		//93
		if(isset($pSet['CO']) && ($pSet['CO'] != null)){
		  $query1 .= ', CO = "'.$db -> escape($pSet['CO']).'"';
		}
		
		//94
		if(isset($pSet['CP']) && ($pSet['CP'] != null)){
		  $query1 .= ', CP = "'.$db -> escape($pSet['CP']).'"';
		}
		
		//95
		if(isset($pSet['CQ']) && ($pSet['CQ'] != null)){
		  $query1 .= ', CQ = "'.$db -> escape($pSet['CQ']).'"';
		}
		
		//96
		if(isset($pSet['CR']) && ($pSet['CR'] != null)){
		  $query1 .= ', CR = "'.$db -> escape($pSet['CR']).'"';
		}
		
		//97
		if(isset($pSet['CS']) && ($pSet['CS'] != null)){
		  $query1 .= ', CS = "'.$db -> escape($pSet['CS']).'"';
		}
		
		//98
		if(isset($pSet['CT']) && ($pSet['CT'] != null)){
		  $query1 .= ', CT = "'.$db -> escape($pSet['CT']).'"';
		}
		
		//99
		if(isset($pSet['CU']) && ($pSet['CU'] != null)){
		  $query1 .= ', CU = "'.$db -> escape($pSet['CU']).'"';
		}
		
		//100
		if(isset($pSet['CV']) && ($pSet['CV'] != null)){
		  $query1 .= ', CV = "'.$db -> escape($pSet['CV']).'"';
		}
		
		//101
		if(isset($pSet['CW']) && ($pSet['CW'] != null)){
		  $query1 .= ', CW = "'.$db -> escape($pSet['CW']).'"';
		}
		
		//102
		if(isset($pSet['CX']) && ($pSet['CX'] != null)){
		  $query1 .= ', CX = "'.$db -> escape($pSet['CX']).'"';
		}
		
		//103
		if(isset($pSet['CY']) && ($pSet['CY'] != null)){
		  $query1 .= ', CY = "'.$db -> escape($pSet['CY']).'"';
		}
		
		//104
		if(isset($pSet['CZ']) && ($pSet['CZ'] != null)){
		  $query1 .= ', CZ = "'.$db -> escape($pSet['CZ']).'"';
		}
		
		//105
		if(isset($pSet['DA']) && ($pSet['DA'] != null)){
		$query1 .= ', DA = "'.$db -> escape($pSet['DA']).'"';
		}
		
		//106
		if(isset($pSet['DB']) && ($pSet['DB'] != null)){
		  $query1 .= ', DB = "'.$db -> escape($pSet['DB']).'"';
		}
		
		//107
		if(isset($pSet['DC']) && ($pSet['DC'] != null)){
		  $query1 .= ', DC = "'.$db -> escape($pSet['DC']).'"';
		}
		
		//108
		if(isset($pSet['DD']) && ($pSet['DD'] != null)){
		  $query1 .= ', DD = "'.$db -> escape($pSet['D']).'"';
		}
		
		//109
		if(isset($pSet['DE']) && ($pSet['DE'] != null)){
		  $query1 .= ', DE = "'.$db -> escape($pSet['DE']).'"';
		}
		
		//110
		if(isset($pSet['DF']) && ($pSet['DF'] != null)){
		  $query1 .= ', DF = "'.$db -> escape($pSet['DF']).'"';
		}
		
		//111
		if(isset($pSet['DG']) && ($pSet['DG'] != null)){
		  $query1 .= ', DG = "'.$db -> escape($pSet['DG']).'"';
		}
		
		//112
		if(isset($pSet['DH']) && ($pSet['DH'] != null)){
		  $query1 .= ', DH = "'.$db -> escape($pSet['DH']).'"';
		}
		
		//113
		if(isset($pSet['DI']) && ($pSet['DI'] != null)){
		  $query1 .= ', DI = "'.$db -> escape($pSet['DI']).'"';
		}
		
		//114
		if(isset($pSet['DJ']) && ($pSet['DJ'] != null)){
		  $query1 .= ', DJ = "'.$db -> escape($pSet['DJ']).'"';
		}
		
		//115
		if(isset($pSet['DK']) && ($pSet['DK'] != null)){
		  $query1 .= ', DK = "'.$db -> escape($pSet['DK']).'"';
		}
		
		//116
		if(isset($pSet['DL']) && ($pSet['DL'] != null)){
		  $query1 .= ', DL = "'.$db -> escape($pSet['DL']).'"';
		}
		
		//117
		if(isset($pSet['DM']) && ($pSet['DM'] != null)){
		  $query1 .= ', DM = "'.$db -> escape($pSet['DM']).'"';
		}
		
		//118
		if(isset($pSet['DN']) && ($pSet['DN'] != null)){
		  $query1 .= ', DN = "'.$db -> escape($pSet['DN']).'"';
		}
		
		//119
		if(isset($pSet['DO']) && ($pSet['DO'] != null)){
		  $query1 .= ', DO = "'.$db -> escape($pSet['DO']).'"';
		}
		
		//120
		if(isset($pSet['DP']) && ($pSet['DP'] != null)){
		  $query1 .= ', DP = "'.$db -> escape($pSet['DP']).'"';
		}
		
		//121
		if(isset($pSet['DQ']) && ($pSet['DQ'] != null)){
		  $query1 .= ', DQ = "'.$db -> escape($pSet['DQ']).'"';
		}
		
		//vID
		if(isset($pSet['vID']) && ($pSet['vID'] != null)){
		  $query1 .= ', vID = "'.$db -> escape($pSet['vID']).'"';
		}
		
		//vType
		if(isset($pSet['vType']) && ($pSet['vType'] != null)){
		  $query1 .= ', vType = "'.$db -> escape($pSet['vType']).'"';
		}
		
		//as24New
		if(isset($pSet['as24New']) && ($pSet['as24New'] != null)){
		  $query1 .= ', as24New = "'.$db -> escape($pSet['as24New']).'"';
		}
	}
	
	if (isset($p[System_Properties::SQL_WHERE])){
		$pWhere = $p[System_Properties::SQL_WHERE];
		
		//ADD as24ID
		if (isset($pWhere['as24ID'])){
			$query2 = ' (as24ID = "'.$db -> escape($pWhere['as24ID']).'" ) AND ';
		}
		
		//ADD A
		if (isset($pWhere['A'])){
			$query2 = ' (A = "'.$db -> escape($pWhere['A']).'" ) AND ';
		}
		
		//ADD B
		if (isset($pWhere['B'])){
			$query2 = ' (B = "'.$db -> escape($pWhere['B']).'" ) AND ';
		}
		
		//ADD as24New
		if (isset($pWhere['as24New'])){
			$query2 = ' (as24New = "'.$db -> escape($pWhere['as24New']).'" ) AND ';
		}
	}
	
	if ($query1 != null){
		
		$query = '	UPDATE as24
					SET '.substr($query1, 1);
		if ($query2 != null){
			$query .= ' WHERE '.substr($query2, 0, -4);
		}
		
		if (isset($p['print']) && ($p['print'] == true)){
			echo $query;
		}
		$return = $db->execQuery(array('q'=>$query));	
	}

	return $return;
}
?>
