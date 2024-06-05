#!/bin/bash
: <<'COMMENT'
		This scipt is to set enviomental varables. 
		
		Call this script before your program.
		. <Path>/<laslo Base>/bin/env.sh;<Path/Your Script>	
		^
		| This matters. 
		|
		
		Creates Enviorments variables.
		LASLO_cli_rootPath - The root path ex. /opt/laslo_dev/ if LASLO_system_ini set to FOUND
		LASLO_system_ini   - Set too FOUND if the script was able to find its location
		
COMMENT

# Hopefully makes sure that this script was called correctly.
(return 0 2>/dev/null) && sourced='t' || sourced='f'

if [ $sourced = 'f' ]; then
	logger "LASLO $BASH_SOURCE: put . in front of command. ex: . /<path>/env.sh"
	(exit)
fi

# Find the Path to the real script
SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do # resolve $SOURCE until the file is no longer a symlink
  DIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null && pwd )"
  SOURCE="$(readlink "$SOURCE")"
  [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE" # if $SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done
SCRIPTLOCATION="$( cd -P "$( dirname "$SOURCE" )" >/dev/null && pwd )"

# Remove the file name.
SCRIPTFILENAME=`basename -- $0`

# Remove the bin from the end.
SCRIPTLOCATION=${SCRIPTLOCATION::-3}

if [ ! -f $SCRIPTLOCATION/etc/system.ini ]; then
	logger "LASLO $BASH_SOURCE: Cannot find system.ini file."
	logger "LASLO $BASH_SOURCE: LASLO $SOURCE did not find a system.ini file."
	LASLO_system_ini=NOTFOUND; export LASLO_system_ini;
	(exit)
else
	LASLO_system_ini=FOUND; export LASLO_system_ini;
fi

LASLO_cli_rootPath=$SCRIPTLOCATION; export LASLO_cli_rootPath;
