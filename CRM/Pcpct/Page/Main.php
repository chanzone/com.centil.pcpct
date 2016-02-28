<?php

require_once 'CRM/Core/Page.php';

class CRM_Pcpct_Page_Main extends CRM_Core_Page {
  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('Personal Campaign'));

    // Retrieve cid value from HTTP request and pass over to template view
    $contactId = CRM_Utils_Request::retrieve('cid', 'Positive', $this, true);
    $this->assign( 'contactId', $contactId );

    parent::run();
  }
}
