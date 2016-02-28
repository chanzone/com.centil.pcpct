<?php

/**
 * Pcpct.GetData API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_pcpct_GetData_spec(&$spec) {
  $spec['contactId']['api.required'] = 1;
}

/**
 * Pcpct.GetData API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_pcpct_GetData($params) {
  if (array_key_exists('contactId', $params) && isset($params['contactId'])) {

    $contactId = $params['contactId'];
    $offset = (array_key_exists('offset', $params) && isset($params['offset'])) ? $params['offset'] : 0;
    $rowCount = (array_key_exists('rowCount', $params) && isset($params['rowCount'])) ? $params['rowCount'] : 25;

    $data = CRM_Pcpct_Common_Func::getPcpRawData($contactId, $offset, $rowCount);

    // Return data
    return civicrm_api3_create_success($data['data'], $params, 'Pcpct', 'GetData');
  } else {
    throw new API_Exception(/*errorMessage*/ 'Please provide contactId in your query', /*errorCode*/ 1234);
  }
}

