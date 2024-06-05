#!/usr/bin/perl

if($ENV{'LASLO_cli_rootPath'} eq "FOUND" ){
  print "LASLO: $BASH_SOURCE: Cannot find system.ini file.\n";
  print "LASLO: This can only ment to be called from bin/sjsCron.php.\n";
  exit
}

my $rootPath = $ENV{'LASLO_cli_rootPath'};

my $ejsReceve = $rootPath . 'webinterface/app/base_admin/bin/ejs_eMailSysReceve.php';
my $ejsSend   = $rootPath . 'webinterface/app/base_admin/bin/ejs_eMailSysSend.php';

($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();

my $logDir = $rootPath . 'var/log/ejs/';
my $logFileNameStart = 'ejs_cron_';
my $logFileName = sprintf("%s%s%s%02s%02s.log",
	$logDir,
	$logFileNameStart,
	($year+1900),
	($mon+1),
	($mday)
	);
	
my $lockFile = $rootPath . 'var/lock/ejs_cron.lck';

#print $rootPath."\n";
#print $ejsReceve."\n";
#print $ejsSend."\n";
#print $logFileName."\n";
#print $lockFile."\n";


use Fcntl ':flock';
#
# lets lock our self so only one instance of the program will run.
#
unless(-e $lockFile){
	system("touch $lockFile");
}
open LCK, $lockFile or exit;
flock LCK, LOCK_EX | LOCK_NB or exit;

#
# Get emails
#
#print "$ejsReceve >> $logFileName 2>&1\n";
#system("$ejsReceve >> $logFileName 2>&1");


#sleep(180);

#
# Send out emails 
#
#print "$ejsSend >> $logFileName 2>&1\n";
system("$ejsSend >> $logFileName 2>&1");
