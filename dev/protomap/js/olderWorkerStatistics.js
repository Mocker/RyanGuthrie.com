//
//

var olderWorkerStatistics = {
'AL': {percentage:[-0.59,-0.55]},
'AK': {percentage:[-1.96,-1.91]},
'AZ': {percentage:[-1.13,-1.04]},
'AR': {percentage:[-0.11,-0.20] },
'CA': {percentage:[-1.18,-1.22]},
'CO': {percentage:[-0.93,-0.67]},
'CT': {percentage:[ 2.31, 2.27]},
'DE': {percentage:[ 0.80, 0.22]},
'DC': {percentage:[ 0.00, 0.00]},
'FL': {percentage:[ 1.34, 1.20]},
'GA': {percentage:[-1.72,-1.79]},
'HI': {percentage:[ 1.30, 1.42]},
'ID': {percentage:[-0.79,-0.75]},
'IL': {percentage:[ 0.21, 0.20]},
'IN': {percentage:[ 0.62, 0.72]},
'IA': {percentage:[ 1.30, 1.27]},
'KS': {percentage:[ 0.58, 0.72]},
'KY': {percentage:[-1.34,-1.41]},
'LA': {percentage:[-0.99,-1.12]},
'ME': {percentage:[ 2.89, 3.02]},
'MD': {percentage:[ 0.67, 0.68]},
'MA': {percentage:[ 0.00, 0.00]},
'MI': {percentage:[-0.41,-0.46]},
'MN': {percentage:[-0.61,-0.65]},
'MS': {percentage:[-0.63,-0.52]},
'MO': {percentage:[-0.22,-0.35]},
'MT': {percentage:[ 1.88, 2.21]},
'NE': {percentage:[ 0.74, 0.91]},
'NV': {percentage:[ 0.32, 0.15]},
'NH': {percentage:[ 0.00, 0.00]},
'NJ': {percentage:[ 2.07, 1.98]},
'NM': {percentage:[ 0.54, 0.45]},
'NY': {percentage:[ 0.62, 0.00]},
'NC': {percentage:[-0.07,-0.15]},
'ND': {percentage:[ 0.22, 0.30]},
'OH': {percentage:[ 0.83, 0.86]},
'OK': {percentage:[ 0.21, 0.28]},
'OR': {percentage:[ 1.25, 1.42]},
'PA': {percentage:[ 1.85, 3.04]},
'RI': {percentage:[ 1.60, 1.51]},
'SC': {percentage:[ 0.65, 0.49]},
'SD': {percentage:[ 1.02, 1.11]},
'TN': {percentage:[ 0.15, 0.19]},
'TX': {percentage:[-1.62,-1.65]},
'UT': {percentage:[-3.70,-3.68]},
'VT': {percentage:[ 3.28, 3.54]},
'VA': {percentage:[ 0.20, 0.19]},
'WA': {percentage:[-0.28, 0.14]},
'WV': {percentage:[ 1.01, 1.19]},
'WI': {percentage:[ 0.35, 0.39]},
'WY': {percentage:[ 0.47, 0.92]}
};

olderWorkerStatistics.startYear = 2008;
olderWorkerStatistics.endYear = 2009;
olderWorkerStatistics.yearIdx = function(year) { return olderWorkerStatistics.endYear-year; };
olderWorkerStatistics.stratum = function(percentage) { 
	  if (percentage < -3.5) return -4;
	  if (percentage < -2.5) return -3;
	  if (percentage < -1.5) return -2;
	  if (percentage < -0.5) return -1;
	  if (percentage <  0.5) return 0;
	  if (percentage <  1.5) return 1;
	  if (percentage <  2.5) return 2;
	  if (percentage <  3.5) return 3;
	  return 4;
 };
 olderWorkerStatistics.stratumIdx = function(stratum) { 
	  return stratum+4;
};
