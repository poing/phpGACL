<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

<script LANGUAGE="JavaScript">
{$js_aro_array}
</script>
{include file="acl_admin_js.tpl"}

  <body onload="populate(document.acl_admin.aro_section,document.acl_admin.elements['aro[]'], '{$js_aro_array_name}')">
    <br>

    <form method="post" name="acl_admin" action="assign_aro_group.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" rowspan="1" colspan="4" bgcolor="#cccccc"><b>phpGACL</b> <b>Group Assign ARO's [ <a href="group_admin.php">Group Admin</a> ] </b><br>
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
          </tr>

          <tr>
            <td valign="middle" bgcolor="#cccccc" align="center">[ <a href="edit_aro_sections.php?return_page={$return_page}">Edit</a> ]<br>
             <br>
             <select name="aro_section" tabindex="0" size="10" width="200" onclick="populate(document.acl_admin.aro_section,document.acl_admin.elements['aro[]'],'{$js_aro_array_name}')">
                {html_options options=$options_aro_sections selected=$aro_section_value}              
            </select> <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
            [ <a href="javascript: location.href = 'edit_aro.php?section_value=' + document.acl_admin.aro_section.options[document.acl_admin.aro_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]<br>
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

          <tr>
            <td valign="top" bgcolor="#999999" rowspan="1" colspan="4">
              <div align="center">
                <input type="submit" name="action" value="Submit"> <input type="reset" value="Reset"><br>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    <br>
    <table cellpadding="2" cellspacing="2" border="2" width="100%">
  <tr align="center">
	<td valign="top" colspan="4" bgcolor="#cccccc"><b>phpGACL</b> <b>Assigned ARO's</b><br>
	 </td>
  </tr>
  <tr>
	<td valign="top" align="center" bgcolor="#d3dce3"><b>ID</b><br>
	 </td>

	<td valign="top" align="center" bgcolor="#d3dce3"><b>Sections</b><br>
	 </td>

	<td valign="top" align="center" bgcolor="#d3dce3"><b>Access Request Objects</b><br>
	 </td>

	<td valign="top" align="center" bgcolor="#d3dce3"><b>Functions</b><br>
	 </td>

  </tr>

    {section name=x loop=$aros}
  <tr>
    <td valign="top" bgcolor="#cccccc" align="center">
            {$aros[x].value}
     </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        {$aros[x].section}
     </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        {$aros[x].name}
     </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <input type="checkbox" name="delete_assigned_aro[]" value="{$aros[x].section_value}^{$aros[x].value}">
     </td>

  </tr>
    {/section}
	  <tr>
		<td valign="top" bgcolor="#999999" colspan="3">
		</td>
		<td valign="top" bgcolor="#999999">
		  <div align="center">
			<input type="submit" name="action" value="Delete">
		  </div>
		</td>
	</tr>

    </table>
<input type="hidden" name="group_id" value="{$group_id}">
<input type="hidden" name="return_page" value="{$return_page}">    
</form>
{include file="footer.tpl"} 
