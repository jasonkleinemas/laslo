#!/bin/bash
: <<'COMMENT'

COMMENT

if [ "$LASLO_system_ini" != "FOUND" ]; then
	echo "LASLO $BASH_SOURCE: Cannot find system.ini file."
	exit
fi

sjs_UUID="e7ea02be-da56-11e9-85c1-7446a0b52568"
subject="Test Create"
description="test_ejs_ec_cli"
cmd="bin/ejs_eMailCreate.php"

command="$LASLO_cli_rootPath$cmd " # --debugP=1"
echo $command
fullPathFileBase=$(${command} --step=createAdhoc --description="${description}" --subject="${subject}")
echo $?
if [ $? == 1 ]; then
	echo error
	exit
fi
ff="${fullPathFileBase}".emd.ini
echo ${ff}
#cat ${ff} |grep sub

command="$LASLO_cli_rootPath$cmd --step=addAttachment --basePathFileName=$fullPathFileBase --fileName=TEST01"
echo $command
fullPathFileAttach=$(${command})
echo $fullPathFileAttach
echo $?
if [ $? == 1 ]; then
	echo error
	exit
fi

command="$LASLO_cli_rootPath$cmd --step=send --basePathFileName=$fullPathFileBase "
echo $command
wrk=$(${command})
echo $wrk
echo $?
if [ $? == 1 ]; then
	echo error
	exit
fi



#return $ret_code