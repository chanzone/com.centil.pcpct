<?php

require_once 'CiviTest/CiviUnitTestCase.php';

/**
 * FIXME
 */
class CRM_Pcpct_Test extends CiviUnitTestCase {
  function setUp() {
    // If your test manipulates any SQL tables, then you should truncate
    // them to ensure a consisting starting point for all tests
    // $this->quickCleanup(array('example_table_name'));
    parent::setUp();
  }

  function tearDown() {
    parent::tearDown();
  }

  /*
   * Test the API if working
   */
  function testPcpctAPI() {
    $this->callAPISuccess("Pcpct", "GetData", array(
      'contactId' => '202',
      'offset' => 0,
      'rowCount' => 25
    ));
  }
}