<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="phpgacl/header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>
{literal}
<style type="text/css">
	tr.hide {
		display: none;
	}
	tr.show {
	}
	td.tabon {
		background: #438EC5;
	}
	td.taboff {
		background: #ABC3D4;
	}
	select {
		width: 99%;
	}
	textarea, input#return_value {
		width: 100%;
	}
</style>
{/literal}

<script LANGUAGE="JavaScript">
{$js_array}
</script>
{include file="phpgacl/acl_admin_js.tpl"}
<body onload="populate(document.acl_admin.aco_section,document.acl_admin.elements['aco[]'], '{$js_aco_array_name}');populate(document.acl_admin.aro_section,document.acl_admin.elements['aro[]'], '{$js_aro_array_name}')">
{include file="phpgacl/navigation.tpl"}
  <form method="post" name="acl_admin" action="acl_admin.php" onsubmit="select_all(document.acl_admin.elements['selected_aco[]']);select_all(document.acl_admin.elements['selected_aro[]']);select_all(document.acl_admin.elements['selected_aro[]']);return true;">
    <div align="center">
      <table cellpadding="2" cellspacing="2" border="2">
        <tbody>
          <tr>
            <th width="24%">Sections</th>
            <th width="24%">Access Control Objects</th>
            <th width="4%">&nbsp;</th>
            <th width="24%">Selected</th>
            <th width="24%">Access</th>
          </tr>
          <tr valign="middle" align="center">
            <td>
              [ <a href="edit_object_sections.php?object_type=aco&return_page={$return_page}">Edit</a> ]
              <br /><br />
              <select name="aco_section" tabindex="0" size="10" onclick="populate(document.acl_admin.aco_section,document.acl_admin.elements['aco[]'], '{$js_aco_array_name}')">
                {html_options options=$options_aco_sections selected=$aco_section_value}
              </select>
            </td>
            <td>
              [ <a href="javascript: location.href = 'edit_objects.php?object_type=aco&section_value=' + document.acl_admin.aco_section.options[document.acl_admin.aco_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]
              <br /><br />
              <select name="aco[]" tabindex="0" size="10" width="200" multiple>
              </select>
            </td>
            <td>
                <input type="button" name="select" value="&nbsp;&gt;&gt;&nbsp;" onClick="select_item(document.acl_admin.aco_section, document.acl_admin.elements['aco[]'], document.acl_admin.elements['selected_aco[]'])">
                <br /><br />
                <input type="button" name="deselect" value="&nbsp;&lt;&lt;&nbsp;" onClick="deselect_item(document.acl_admin.elements['selected_aco[]'])">
             </td>
            <td>
              <br /><br />
              <select name="selected_aco[]" tabindex="0" size="10" multiple>
				{html_options options=$options_selected_aco selected=$selected_aco}
              </select>
            </td>
            <td>
              <input type="radio" class="radio" name="allow" value="1" {if $allow==1}checked{/if}>Allow<br />
              <input type="radio" class="radio" name="allow" value="0" {if $allow==0}checked{/if}>Deny<br />
              <br /><br />
              <input type="checkbox" class="checkbox" name="enabled" value="1" {if $enabled==1}checked{/if}>Enabled
            </td>
          </tr>

          <tr>
            <th>Sections</th>
            <th>Access Request Objects</th>
            <th>&nbsp;</th>
            <th>Selected</th>
            <th>Groups</th>
          </tr>
          <tr valign="middle" align="center">
            <td>
              [ <a href="edit_object_sections.php?object_type=aro&return_page={$return_page}">Edit</a> ]
              <br /><br />
              <select name="aro_section" tabindex="0" size="10" onclick="populate(document.acl_admin.aro_section,document.acl_admin.elements['aro[]'],'{$js_aro_array_name}')">
                {html_options options=$options_aro_sections selected=$aro_section_value}
              </select>
            </td>
            <td>
              [ <a href="javascript: location.href = 'edit_objects.php?object_type=aro&section_value=' + document.acl_admin.aro_section.options[document.acl_admin.aro_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]
              [ <a href="#" onClick="window.open('object_search.php?src_form=acl_admin&object_type=aro&section_value=' + document.acl_admin.aro_section.options[document.acl_admin.aro_section.selectedIndex].value + '&return_page={$return_page}','','status=yes,width=400,height=400','','status=yes,width=400,height=400');">Search</a> ]
              <br /><br />
              <select name="aro[]" tabindex="0" size="10" width="200" multiple>
              </select>
            </td>
            <td>
                <input type="button" name="select" value="&nbsp;&gt;&gt;&nbsp;" onClick="select_item(document.acl_admin.aro_section, document.acl_admin.elements['aro[]'], document.acl_admin.elements['selected_aro[]'])">
                <br /><br />
                <input type="button" name="deselect" value="&nbsp;&lt;&lt;&nbsp;" onClick="deselect_item(document.acl_admin.elements['selected_aro[]'])">
            </td>
            <td>
             <br /><br />
             <select name="selected_aro[]" tabindex="0" size="10" multiple>
			   {html_options options=$options_selected_aro selected=$selected_aro}
             </select>
            </td>
            <td>
              [ <a href="group_admin.php?group_type=aro&return_page={$SCRIPT_NAME}?action={$action}&acl_id={$acl_id}">Edit</a> ]
              <br /><br />
			  <select name="aro_groups[]" tabindex="0" multiple>
			    {html_options options=$options_aro_groups selected=$selected_aro_groups}
			  </select>
		      <br /><br />
			  <input type="button" name="Un-Select" value="Un-Select" onClick="unselect_all(document.acl_admin.elements['aro_groups[]'])">
            </td>
          </tr>

          <tr>
            <th colspan="5">
              [ <a href="javascript: showObject('axo_row1');showObject('axo_row2');setCookie('show_axo',1);">Show</a> / <a href="javascript: hideObject('axo_row1');hideObject('axo_row2');deleteCookie('show_axo');">Hide</a> ] Access eXtension Objects (Optional)
            </th>
          </tr>

          <tr id="axo_row1" {if $show_axo!=TRUE}class="hide"{/if}>
            <th>Sections</th>
            <th>Access eXtension Objects</th>
            <th>&nbsp;</th>
            <th>Selected</th>
            <th>Groups</th>
          </tr>
          <tr valign="middle" align="center" id="axo_row2" {if $show_axo!=TRUE}class="hide"{/if}>
            <td>
              [ <a href="edit_object_sections.php?object_type=axo&return_page={$return_page}">Edit</a> ]
              <br /><br />
              <select name="axo_section" tabindex="0" size="10" onclick="populate(document.acl_admin.axo_section,document.acl_admin.elements['axo[]'],'{$js_axo_array_name}')">
                {html_options options=$options_axo_sections selected=$axo_section_value}
              </select>
            </td>
            <td>
              [ <a href="javascript: location.href = 'edit_objects.php?object_type=axo&section_value=' + document.acl_admin.axo_section.options[document.acl_admin.axo_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]
              [ <a href="#" onClick="window.open('object_search.php?src_form=acl_admin&object_type=axo&section_value=' + document.acl_admin.axo_section.options[document.acl_admin.axo_section.selectedIndex].value + '&return_page={$return_page}','','status=yes,width=400,height=400','','status=yes,width=400,height=400');">Search</a> ]
              <br /><br />
              <select name="axo[]" tabindex="0" size="10" width="200" multiple>
              </select>
            </td>
            <td>
                <input type="button" name="select" value="&nbsp;&gt;&gt;&nbsp;" onClick="select_item(document.acl_admin.axo_section, document.acl_admin.elements['axo[]'], document.acl_admin.elements['selected_axo[]'])">
                <br /><br />
                <input type="button" name="deselect" value="&nbsp;&lt;&lt;&nbsp;" onClick="deselect_item(document.acl_admin.elements['selected_axo[]'])">
            </td>
            <td>
              <br /><br />
              <select name="selected_axo[]" tabindex="0" size="10" multiple>
                {html_options options=$options_selected_axo selected=$selected_axo}
              </select>
            </td>
            <td>
              [ <a href="group_admin.php?group_type=axo&return_page={$SCRIPT_NAME}?action={$action}&acl_id={$acl_id}">Edit</a> ]
              <br /><br />
              <select name="axo_groups[]" tabindex="0" multiple>
                {html_options options=$options_axo_groups selected=$selected_axo_groups}
              </select>
              <br /><br />
              <input type="button" name="Un-Select" value="Un-Select" onClick="unselect_all(document.acl_admin.elements['axo_groups[]'])">
            </td>
        </tr>

        <tr valign="middle" align="center">
			<th colspan="5">Miscellaneous Attributes</th>
		</tr>
        <tr>
			<td align="center">
                <b>ACL Section</b>
            </td>
			<td align="left">
                <b>Extended Return Value:</b>
            </td>
            <td align="left" colspan="4">
                <input type="text" name="return_value" size="50" value="{$return_value}" id="return_value">
            </td>
		</tr>
		<tr valign="top" align="left">
			<td valign="middle" align="center">
			[ <a href="edit_object_sections.php?object_type=acl&return_page={$return_page}">Edit</a> ]
			<br /><br />
			<select name="acl_section" tabindex="0" size="2">
			  {html_options options=$options_acl_sections selected=$acl_section_value}
			</select>
		  </td>
          <td><b>Note:</b></td>
          <td colspan="4"><textarea name="note" rows="4" cols="50">{$note}</textarea></td>
		</tr>
        <tr class="controls" align="center">
          <td colspan="5">
            <input type="submit" name="action" value="Submit"> <input type="reset" value="Reset">
          </td>
        </tr>
      </tbody>
    </table>
	<input type="hidden" name="acl_id" value="{$acl_id}">
	<input type="hidden" name="return_page" value="{$return_page}">
  </div>
</form>
{include file="phpgacl/footer.tpl"}