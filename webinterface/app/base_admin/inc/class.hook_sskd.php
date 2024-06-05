<?PHP

class hook_sskd(){
#-----------------------------------------------------------------------------
  function sysAppPreferences_appBarDisplayType(){
    return[
      'Icon and Text',
      'Icon Only',
      'Text Only',
    ];
  }
#-----------------------------------------------------------------------------
  function sysAppPreferences_dateFormat(){
    return[
      'CCYY/DD/MM',
      'MM/DD/CCYY',
    ];
  }
#-----------------------------------------------------------------------------
  function sysAppPreferences_defaultApp(){
    return[# list of apps. If called when editing user. Need to only list user apps.
    ];
  }
#-----------------------------------------------------------------------------
  function sysSiteConfig_defaultSendingEmailSystem(){
    return[
      'CCYY/DD/MM',
      'MM/DD/CCYY',
    ];
  }
#-----------------------------------------------------------------------------
  function sysAppPreferences_timeZoneOffset(){
    return[
      '0',
      '+1','+2','+3','+4','+5','+6','+7','+8','+9','+10','+11','+12',
      '-1','-2','-3','-4','-5','-6','-7','-8','-9','-10','-11','-12',
    ];
  }
}
