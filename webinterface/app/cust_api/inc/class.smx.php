<?php

class smx {
  
  private $server = [
    'basePath'    => 'rest',
    'versionPath' => 'v1',
    'scheme'      => 'https',
    'host'        => 'ip',
    'port'        => 'port',
    'user'        => 'usewr',
    'pass'        => 'pass',
  ];  
#-----------------------------------------------------------------------------
#
# $is_deviceName
#   G020
# $is_ont_id
#   12345
  function getOntAlarms($is_deviceName, $is_ont_id){
#    echo $this->smx_getFaultAlarm("device-name=$is_deviceName and ont-id=$is_ont_id");
    return json_decode($this->smx_getFaultAlarm("device-name=$is_deviceName and ont-id=$is_ont_id"), true);
  }
#-----------------------------------------------------------------------------
#
# /fault/alarm Get all standing alarms
#
# options
# filter string (query)
#   device-name,deviceType and severity are allowed parameters for filter. Multiple filter can applied using “and” operator. 
#   The allowed value for severity are CRITICAL,MAJOR and MINOR. 
#   Example could be device-name = 10.245.13.XXX and deviceType like E9 and severity=MINOR
#   device-name=G013 and ont-id=11520
# offset integer($int32)
# limit integer($int32)
# sort string   Camma list
#   sort fileds
# fields string Camma list
#   query fileds
# standing boolean
#   Leave blank to ignore or send bool true/false
  function smx_getFaultAlarm($is_filter, $ii_offset=-1, $ii_limit=20, $is_sort='', $is_fields='',$ib_standing=''){
    $ws_path = 'fault/alarm';
    $wa_parms = [];
    
    $wa_parms['filter'] = $is_filter;

    if(!empty($ii_offset) and $ii_offset>-1 ){
      $wa_parms['offset'] = $ii_offset;
      if(!empty($ii_limit) and $ii_limit>0){
        $wa_parms['limit'] = $ii_limit;
      }
    }
    if(!empty($is_sort)){
      $wa_parms['sort'] = $is_sort;
    }
    if(!empty($is_fields)){
      $wa_parms['fields'] = $is_fields;
    }
    if(!empty($is_standing)){
      $wa_parms['standing'] = $is_fields ? 'true':'false';
    }
    return $this->smx_sendCmd($ws_path, $wa_parms);
  }
#-----------------------------------------------------------------------------
  function smx_sendCmd($is_apiPath, $ia_parms){

#    $cmd = 'curl \
#   --silent \
#   --user \''.self::$server['user'].':'.self::$server['pass'].'\' \
#   --insecure \
#   --request GET "'.self::$serverScheme.'://'.self::$serverHost.':'.self::$serverPort.'/'.self::$serverBasePath.'/'.self::$serverVersionPath.'/config/device/'.$iDeviceID.'/ont?ont-id='.$iOntID.'" \
#  ';
  
    $url = 
      $this->server['scheme'] .'://'. $this->server['host'] .':'. $this->server['port'] .
      '/'. $this->server['basePath'] .'/'. $this->server['versionPath'] .'/'. $is_apiPath .'?'. http_build_query($ia_parms);
#    echo '<br>url<br>';
#    var_dump($url);
#    var_dump($ia_parms);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, $this->server['user'].':'.$this->server['pass']);
#    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($ia_parms));
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_PUT, 0);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Skip SSL Verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS, 0); // Skip SSL Verification
    
#    curl_setopt($ch, CURLOPT_VERBOSE, true);
    
    $data = curl_exec($ch);
    curl_close($ch);
#    echo '<br>data<br>';
#    print_r($data);
#    echo '<br>data<br>';
    return $data;
    
#    print_r(curl_getinfo($ch, CURLINFO_HTTP_CODE));   //get status code
#    echo '<br>error<br>';
#    $curl_error = curl_error($ch);
#    echo $curl_error;
  }  
}