<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

<script LANGUAGE="JavaScript">
{$js_aco_array}

{$js_aro_array}

{$js_axo_array}
</script>

{include file="acl_admin_js.tpl"}

<body onload="populate(document.acl_admin.aco_section,document.acl_admin.elements['aco[]'], '{$js_aco_array_name}');populate(document.acl_admin.aro_section,document.acl_admin.elements['aro[]'], '{$js_aro_array_name}')">
    <form method="post" name="acl_admin" action="acl_admin.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" rowspan="1" colspan="5" bgcolor="#cccccc"><b>phpGACL</b> <b>Administrator [ <a href="acl_list.php?return_page={$return_page}">ACL List</a> ] </b><br>
             </td>
          </tr>

          <tr>
            <td valign="top" align="center" bgcolor="#d3dce3">&nbsp;<b>Sections</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Access Control Objects</b> <br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3">&nbsp;<br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Selected</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Access</b><br>
             </td>
          </tr>

          <tr>
            <td valign="middle" bgcolor="#cccccc" align="center">[ <a href="edit_object_sections.php?object_type=aco&return_page={$return_page}">Edit</a> ]<br>
             <br>
             &nbsp; <select name="aco_section" tabindex="0" size="10" width="200" onclick="populate(document.acl_admin.aco_section,document.acl_admin.elements['aco[]'], '{$js_aco_array_name}')">
                {html_options options=$options_aco_sections selected=$aco_section_value}
            </select> <br>
             </td>
            <td valign="middle" bgcolor="#cccccc" align="center">
            [ <a href="javascript: location.href = 'edit_objects.php?object_type=aco&section_value=' + document.acl_admin.aco_section.options[document.acl_admin.aco_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]<br>
             <br>
             <select name="aco[]" tabindex="0" size="10" width="200" multiple>
            </select>
            <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
                <input type="BUTTON" name="select" value="&nbsp;>>&nbsp;" onClick="select_item(document.acl_admin.aco_section, document.acl_admin.elements['aco[]'], document.acl_admin.elements['selected_aco[]'])">
                <br>
                <br>
                <input type="BUTTON" name="deselect" value="&nbsp;<<&nbsp;" onClick="deselect_item(document.acl_admin.elements['selected_aco[]'])">
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
             <br>
             <select name="selected_aco[]" tabindex="0" size="10" width="200" multiple>
				{html_options options=$options_selected_aco selected=$selected_aco}             
            </select>
            <br>
             </td>

            <td valign="middle" bgcolor="#cccccc">
              <div align="center">
                <input type="radio" name="allow" value="1" {if $allow==1}checked{/if}>Allow<br>
                 <input type="radio" name="allow" value="0" {if $allow==0}checked{/if}>Deny<br>
                <br>
                <br>
                 <input type="checkbox" name="enabled" value="1" {if $enabled==1}checked{/if}>Enabled
              </div>
            </td>
          </tr>

          <tr>
            <td valign="top" align="center" bgcolor="#d3dce3"><b>Sections</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Access Request Objects</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3">&nbsp;<br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Selected</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Groups</b><br>
             </td>
          </tr>

          <tr>
            <td valign="middle" bgcolor="#cccccc" align="center">[ <a href="edit_object_sections.php?object_type=aro&return_page={$return_page}">Edit</a> ]<br>
             <br>
             <select name="aro_section" tabindex="0" size="10" width="200" onclick="populate(document.acl_admin.aro_section,document.acl_admin.elements['aro[]'],'{$js_aro_array_name}')">
                {html_options options=$options_aro_sections selected=$aro_section_value}              
            </select> <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
            [ <a href="javascript: location.href = 'edit_objects.php?object_type=aro&section_value=' + document.acl_admin.aro_section.options[document.acl_admin.aro_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]<br>
             <br>
             <select name="aro[]" tabindex="0" size="10" width="200" multiple>
            </select> <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
                <input type="BUTTON" name="select" value="&nbsp;>>&nbsp;" onClick="select_item(document.acl_admin.aro_section, document.acl_admin.elements['aro[]'], document.acl_admin.elements['selected_aro[]'])">
                <br>
                <br>
                <input type="BUTTON" name="deselect" value="&nbsp;<<&nbsp;" onClick="deselect_item(document.acl_admin.elements['selected_aro[]'])">
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
             <br>
             <select name="selected_aro[]" tabindex="0" size="10" width="200" multiple>
				{html_options options=$options_selected_aro selected=$selected_aro}
            </select>
            <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">[ <a href="group_admin.php?group_type=aro&return_page={$SCRIPT_NAME}?action={$action}&acl_id={$acl_id}">Edit</a> ]<br>
				 <br>
				 <select name="aro_groups[]" tabindex="0" multiple>
					{html_options options=$options_aro_groups selected=$selected_aro_groups}
				</select>
				<br>
				<br>
				<input type="BUTTON" name="Un-Select" value="Un-Select" onClick="unselect_all(document.acl_admin.elements['aro_groups[]'])">
            </td>
          </tr>

          <tr>
            <td colspan="5" valign="top" align="center" bgcolor="#d3dce3">
                <b>(Optional)</b>
             </td>
          </tr>
          <tr>
            <td valign="top" align="center" bgcolor="#d3dce3"><b>Sections</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Access eXtension Objects</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3">&nbsp;<br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Selected</b><br>
             </td>

            <td valign="top" align="center" bgcolor="#d3dce3"><b>Groups</b><br>
             </td>
          </tr>

          <tr>
            <td valign="middle" bgcolor="#cccccc" align="center">[ <a href="edit_object_sections.php?object_type=axo&return_page={$return_page}">Edit</a> ]<br>
             <br>
             <select name="axo_section" tabindex="0" size="10" width="200" onclick="populate(document.acl_admin.axo_section,document.acl_admin.elements['axo[]'],'{$js_axo_array_name}')">
                {html_options options=$options_axo_sections selected=$axo_section_value}              
            </select> <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
            [ <a href="javascript: location.href = 'edit_objects.php?object_type=axo&section_value=' + document.acl_admin.axo_section.options[document.acl_admin.axo_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]<br>
             <br>
             <select name="axo[]" tabindex="0" size="10" width="200" multiple>
            </select> <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
                <input type="BUTTON" name="select" value="&nbsp;>>&nbsp;" onClick="select_item(document.acl_admin.axo_section, document.acl_admin.elements['axo[]'], document.acl_admin.elements['selected_axo[]'])">
                <br>
                <br>
                <input type="BUTTON" name="deselect" value="&nbsp;<<&nbsp;" onClick="deselect_item(document.acl_admin.elements['selected_axo[]'])">
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
             <br>
             <select name="selected_axo[]" tabindex="0" size="10" width="200" multiple>
				{html_options options=$options_selected_axo selected=$selected_axo}
            </select>
            <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">[ <a href="group_admin.php?group_type=axo&return_page={$SCRIPT_NAME}?action={$action}&acl_id={$acl_id}">Edit</a> ]<br>
				 <br>
				 <select name="axo_groups[]" tabindex="0" multiple>
					{html_options options=$options_axo_groups selected=$selected_axo_groups}                          
				</select>
				<br>
				<br>
				<input type="BUTTON" name="Un-Select" value="Un-Select" onClick="unselect_all(document.acl_admin.elements['groups[]'])">
            </td>
          </tr>
          <tr>
            <td valign="top" align="right" bgcolor="#d3dce3" rowspan="1" colspan="1">
                <b>Extended Return Value:</b>
            </td>
            <td valign="top" align="left" bgcolor="#cccccc" rowspan="1" colspan="4">
                <input type="text" name="return_value" size="50" value="{$return_value}">
            </td>
          </tr>
          <tr>
            <td valign="top" align="right" bgcolor="#d3dce3" rowspan="1" colspan="1">
                <b>Note:</b>
            </td>
            <td valign="top" align="left" bgcolor="#cccccc" rowspan="1" colspan="4">
                <textarea name="note" rows="2" cols="50">{$note}</textarea>
            </td>
          </tr>
          <tr>
            <td valign="top" bgcolor="#999999" rowspan="1" colspan="5">
              <div align="center">
                <input type="submit" name="action" value="Submit"> <input type="reset" value="Reset"><br>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
	<input type="hidden" name="acl_id" value="{$acl_id}">
	<input type="hidden" name="return_page" value="{$return_page}">
    </form>
{include file="footer.tpl"} 

