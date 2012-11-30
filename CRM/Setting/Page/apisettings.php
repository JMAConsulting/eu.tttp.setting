<?php

require_once 'CRM/Core/Page.php';
require_once('api/class.api.php');

class CRM_Setting_Page_apisettings extends CRM_Core_Page {
  function run() {
    $api= new civicrm_api3();
    //we allow filters and domain ids to be passed in
    $domain_ids = explode(',',CRM_Utils_Request::retrieve('domain','String'));
    $filters = explode(',',CRM_Utils_Request::retrieve('filters', 'String'));
    $profile = CRM_Utils_Request::retrieve('profile', 'String');
    $extraParams = array();
    $availableProfiles = array('nz', 'ca', 'dev', 'server');
    if(!empty($profile)){
      $extraParams['profile'] = $profile;
    }
    foreach ($filters as $filter){
      if(!empty($filter)){
        $vals = explode(":", $filter);
        $extraParams['filters'][$vals[0]] = $vals[1];
      }
    }
    //using the php form rather than class so I can do the array intersect - X probably has
    // a better way
    if(empty($domain_ids[0])){
      $domains = civicrm_api('domain', 'get', array('version' => 3, 'current_domain' => 1));
      $domains = $domains['values'];
    }
    else{
      $domains = civicrm_api('domain', 'get', array('version' => 3));
      $domains = $domains['values'];

      if(is_array($domain_ids) && !empty($domain_ids) && !empty($domain_ids[0])){
        $domains = array_intersect_key($domains, array_flip($domain_ids));
        $extraParams['domain_id'] = $domain_ids;
      }
    }

    $api->Setting->getfields (array('sequential'=> 0) + $extraParams );
    $fields =  $api->values;
    $api->Setting->get (array('sequential'=> 0) + $extraParams );
    $settings = $api->values;
    $this->assign_by_ref('fields', $fields);
    $this->assign_by_ref('settings', $settings);
    $this->assign_by_ref('domains',$domains);
    $this->assign_by_ref('profile', $profile);
    $this->assign_by_ref('availableProfiles', $availableProfiles);
    // this is just for constructing the url - smary may have a better way
    $this->assign_by_ref('domainstring', implode(",", $domain_ids));
    parent::run();
  }
}

