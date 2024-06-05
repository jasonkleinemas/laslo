#!/bin/bash
#
# This is ment to be called from cron. expects env.sh to be run infront of it.
#
# Will have 2 parms. 
# 	Parm 1: The UUID from the job scheduler
# 	Parm 2: The NameId from the job scheduler

if [ -z ${LASLO_system_ini} ]; then
	logger "LASLO:${LASLO_cli_rootPath}/bin/sjs_Cron.sh:Missing UUID parm."
	echo "Missing LASLO_system_ini."
	exit
fi

if [ -z ${1} ]; then
	logger "LASLO:${LASLO_cli_rootPath}/bin/sjs_Cron.sh:Missing UUID parm."
	echo "Missing UUID parm 1."
	exit
fi

if [ -z ${2} ]; then
	logger "LASLO:${LASLO_cli_rootPath}/bin/sjs_Cron.sh:missing NameId."
	echo "Missing NameId parm 2."
	exit
fi

date=`date '+%Y-%m-%dT%H_%M_%SZ%z'`
#LogFileName="${2}.${1}.$date.log"
LogFileName="${2}.$date.log"
Log=$LASLO_cli_rootPath"var/log/sjs/"$LogFileName

$LASLO_cli_rootPath"/bin/sjs_Cron.php" ${1} $LogFileName >$Log 2>&1

