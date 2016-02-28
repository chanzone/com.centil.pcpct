<?php

class CRM_Pcpct_Common_Func{

	/*
	** Extract PCP count for Pcpct tab count info
	*/
	public static function getPcpCount($contactId) {

		// Query pcp count based on contact id
		$params = array(1 => array($contactId, 'Integer'));
		$query = "SELECT count(*) AS count FROM civicrm_pcp WHERE contact_id=%1";
		$dao = CRM_Core_DAO::executeQuery($query, $params);
		$dao->fetch();

		// Return count
		return $dao->count;
	}

	/*
	** Ajax load PCP data
	*/
	public static function getPcpAjax() {

		// Retrieve request variables
		$contactId = CRM_Utils_Type::escape($_REQUEST['cid'], 'Integer');
    $sEcho = CRM_Utils_Type::escape($_REQUEST['sEcho'], 'Integer');
    $offset = isset($_REQUEST['iDisplayStart']) ? CRM_Utils_Type::escape($_REQUEST['iDisplayStart'], 'Integer') : 0;
    $rowCount = isset($_REQUEST['iDisplayLength']) ? CRM_Utils_Type::escape($_REQUEST['iDisplayLength'], 'Integer') : 25;

		// Get data
		$data = CRM_Pcpct_Common_Func::getPcpRawData($contactId, $offset, $rowCount);

		// Post process data
		$iTotal = $data['totalCount'];
		$iFilteredTotal = $data['totalCount'];
		$selectorElements = array(
			'title',
			'status',
			'contribution_event',
			'no_of_contributions',
			'amount_raised',
			'target_amount',
			'edit'
		);

		// Dump out data
    header('Content-Type: application/json');
    echo CRM_Utils_JSON::encodeDataTableSelector($data['data'], $sEcho, $iTotal, $iFilteredTotal, $selectorElements);
    CRM_Utils_System::civiExit();
	}

	/*
	** Get PCP raw data
	*/
	public static function getPcpRawData($contactId, $offset, $rowCount) {

		$allPages = array();

		// Extract all contribution pages data
		$cQuery = "SELECT id, title FROM civicrm_contribution_page";
		$cDao = CRM_Core_DAO::executeQuery($cQuery);
		while ($cDao->fetch()) {
			$allPages['contribute'][$cDao->id]['title'] = $cDao->title;
		}

		// Extract all event pages data
		$eQuery = "SELECT id, title FROM civicrm_event WHERE is_template=0";
		$eDao = CRM_Core_DAO::executeQuery($eQuery);
		while ($eDao->fetch()) {
			$allPages['event'][$eDao->id]['title'] = $eDao->title;
		}

		// Extract contact's pcp data
		$params = array(
			1 => array($contactId, 'Integer'),
			2 => array($rowCount, 'Integer'),
			3 => array($offset, 'Integer')
		);
		$query = "SELECT id, title, status_id, page_type, page_id, goal_amount, currency
        FROM civicrm_pcp
        WHERE contact_id=%1 ORDER BY id DESC LIMIT %2 OFFSET %3";
    $dao = CRM_Core_DAO::executeQuery($query, $params);

    // Process data
    $status = CRM_PCP_BAO_PCP::buildOptions('status_id', 'create');
    $data = array();
    while ($dao->fetch()) {
    	// Result id
    	$data[$dao->id]['id'] =  $dao->id;

    	// Title with URL
			$title_url =  CRM_Utils_System::url('civicrm/pcp/info', 'reset=1&id=' . $dao->id);
			$data[$dao->id]['title'] =  '<a target="_blank" href="'.$title_url.'">'.$dao->title.'</a>';

			// Status
			$data[$dao->id]['status'] =  $status[$dao->status_id];

			// Contribution or Event page
	  	$pageId = (int) $dao->page_id;
	  	$pageType = $dao->page_type;
      $pageTitle = $allPages[$pageType][$pageId]['title'];
      if ($pageType == 'contribute') {
        $pageUrl = CRM_Utils_System::url('civicrm/' . $pageType . '/transact', 'reset=1&id=' . $pageId);
      }
      else {
        $pageUrl = CRM_Utils_System::url('civicrm/' . $pageType . '/register', 'reset=1&id=' . $pageId);
      }		
   		$data[$dao->id]['contribution_event'] =  '<a target="_blank" href="'.$pageUrl.'">'.$pageTitle.'</a>';

   		// Number of contributions
   		$contParams = array(1 => array($dao->id, 'Integer'));
   		$contQuery = "SELECT COUNT(*) AS no_of_contributions, SUM(cc.total_amount) AS amount_raised
   			FROM civicrm_pcp cp
   			LEFT JOIN civicrm_contribution_soft cs ON (cp.id=cs.pcp_id)
   			LEFT JOIN civicrm_contribution cc ON (cs.contribution_id = cc.id)
   			WHERE cp.id=%1 AND cc.is_test=0 AND cc.contribution_status_id=1";
   		$contDao = CRM_Core_DAO::executeQuery($contQuery, $contParams);
   		$contDao->fetch();
   		$data[$dao->id]['no_of_contributions'] = ($contDao->no_of_contributions=='') ? '0' : $contDao->no_of_contributions;

   		// Amount raised
   		$amountRaised = ($contDao->amount_raised=='') ? '0' : $contDao->amount_raised;
   		$data[$dao->id]['amount_raised'] = CRM_Utils_Money::format($amountRaised, $dao->pcp_currency);

   		// Target amount
   		$data[$dao->id]['target_amount'] =  CRM_Utils_Money::format($dao->goal_amount, $dao->pcp_currency);

   		// Edit
   		$editUrl = CRM_Utils_System::url('civicrm/pcp/info', 'action=update&reset=1&id=' . $dao->id);
   		$data[$dao->id]['edit'] = '<a href="'.$editUrl.'">Edit</a>';
    }
    $result['data'] = $data;

    // Process total count
    $totalCountParams = array(1 => array($contactId, 'Integer'));
    $totalCountQuery = "SELECT COUNT(*) AS count FROM civicrm_pcp WHERE contact_id=%1";
    $totalCountDao = CRM_Core_DAO::executeQuery($totalCountQuery, $totalCountParams);
    $totalCountDao->fetch();
    $result['totalCount'] = $totalCountDao->count;

    // Return result
    return $result;
	}

}