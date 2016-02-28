<h3>Personal Campaign Pages - Contact tab</h3>

<table id="crm-pcpct-table">
	<thead>
	<tr>
		<th class="pcpct-title">{ts}Title{/ts}</th>
		<th class="pcpct-status">{ts}Status{/ts}</th>
		<th class="pcpct-contribution-event">{ts}Contribution Page / Event{/ts}</th>
		<th class="pcpct-no-of-contributions">{ts}No Of Contributions{/ts}</th>
		<th class="pcpct-amount-raised">{ts}Amount Raised{/ts}</th>
		<th class="pcpct-target-amount">{ts}Target Amount{/ts}</th>
		<th class="pcpct-edit">{ts}Edit{/ts}</th>
	</tr>
	</thead>
</table>

{literal}
<script type="text/javascript">

  CRM.$(function($) {
    getData();
    function getData() {
      var pcpctDataTable = $('#crm-pcpct-table').dataTable({
      	"sAjaxSource": {/literal}'{crmURL p="civicrm/contact/pcpct-ajax" q="cid=$contactId" h=0}'{literal},
        "bFilter": false,
        "bAutoWidth": false,
        "aaSorting": [],
        "aoColumns": [
          {sClass: 'pcpct-title', bSortable: false},
          {sClass: 'pcpct-status', bSortable: false},
          {sClass: 'pcpct-contribution-event', bSortable: false},
          {sClass: 'pcpct-no-of-contributions', bSortable: false},
          {sClass: 'pcpct-amount-raised', bSortable: false},
          {sClass: 'pcpct-target-amount', bSortable: false},
          {sClass: 'pcpct-edit' , bSortable: false}
        ],
        "bProcessing": true,
        "sPaginationType": "full_numbers",
        "sDom": '<"crm-datatable-pager-top"lfp>rt<"crm-datatable-pager-bottom"ip>',
        "bServerSide": true,
        "bJQueryUI": true,
        "iDisplayLength": 25,
        "oLanguage": {
          "sZeroRecords": {/literal}"{ts escape='js'}No record found.{/ts}"{literal},
          "sProcessing": {/literal}"{ts escape='js'}Processing...{/ts}"{literal},
          "sLengthMenu": {/literal}"{ts escape='js'}Show _MENU_ entries{/ts}"{literal},
          "sInfo": {/literal}"{ts escape='js'}Showing _START_ to _END_ of _TOTAL_ entries{/ts}"{literal},
          "sInfoEmpty": {/literal}"{ts escape='js'}Showing 0 to 0 of 0 entries{/ts}"{literal},
          "sInfoFiltered": {/literal}"{ts escape='js'}(filtered from _MAX_ total entries){/ts}"{literal},
          "sSearch": {/literal}"{ts escape='js'}Search:{/ts}"{literal},
          "oPaginate": {
            "sFirst": {/literal}"{ts escape='js'}First{/ts}"{literal},
            "sPrevious": {/literal}"{ts escape='js'}Previous{/ts}"{literal},
            "sNext": {/literal}"{ts escape='js'}Next{/ts}"{literal},
            "sLast": {/literal}"{ts escape='js'}Last{/ts}"{literal}
          }
        },
        "fnDrawCallback": function () {
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        }
      });
    }
  });
</script>
{/literal}