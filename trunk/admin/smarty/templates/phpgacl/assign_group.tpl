<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

<script LANGUAGE="JavaScript">
{$js_array}
</script>
{include file="acl_admin_js.tpl"}

  <body onload="populate(document.assign_group.{$group_type}_section,document.assign_group.elements['objects[]'], '{$js_array_name}')">
    <br>

    <form method="post" name="assign_group" action="assign_group.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" rowspan="1" colspan="4" bgcolor="#cccccc"><b>phpGACL</b> <b>Group Assign {$group_type|upper}'s [ <a href="group_admin.php?group_type={$group_type}">{$group_type|upper} Group Admin</a> ] </b><br>
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
            <td valign="middle" bgcolor="#cccccc" align="center">[ <a href="edit_{$group_type}_sections.php?return_page={$return_page}">Edit</a> ]<br>
             <br>
             <select name="{$group_type}_section" tabindex="0" size="10" width="200" onclick="populate(document.assign_group.{$group_type}_section,document.assign_group.elements['objects[]'],'{$js_array_name}')">
                {html_options options=$options_sections selected=$section_value}              
            </select> <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
            [ <a href="javascript: location.href = 'edit_aro.php?section_value=' + document.assign_group.aro_section.options[document.assign_group.aro_section.selectedIndex].value + '&return_page={$return_page}';">Edit</a> ]
            [ <a href="#" onClick="window.open('object_search.php?src_form=assign_group&object_type={$group_type}&section_value=' + document.assign_group.{$group_type}_section.options[document.assign_group.{$group_type}_section.selectedIndex].value,'','status=yes,width=400,height=400','','status=yes,width=400,height=400');">Search</a> ]<br>
             <br>
             <select name="objects[]" tabindex="0" size="10" width="200" multiple>
            </select> <br>
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
                <input type="BUTTON" name="select" value="&nbsp;>>&nbsp;" onClick="select_item(document.assign_group.{$group_type}_section, document.assign_group.elements['objects[]'], document.assign_group.elements['selected_{$group_type}[]'])">
                <br>
                <br>
                <input type="BUTTON" name="deselect" value="&nbsp;<<&nbsp;" onClick="deselect_item(document.assign_group.elements['selected_{$group_type}[]'])">
             </td>

            <td valign="middle" bgcolor="#cccccc" align="center">
             <br>
             <select name="selected_{$group_type}[]" tabindex="0" size="10" width="200" multiple>
				{html_options options=$options_selected_objects selected=$selected_object}
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
	<td valign="top" colspan="4" bgcolor="#cccccc"><b>Assigned {$group_type|upper}'s to Group: {$group_name}</b><br>
	 </td>
  </tr>
  <tr>
    <td valign="top" colspan="11" bgcolor="#cccccc">
        {include file="pager.tpl" pager_data=$paging_data link="?group_type=$group_type&group_id=$group_id&"}
    </td>
  </tr>
  <tr>
	<td valign="top" align="center" bgcolor="#d3dce3"><b>Value</b><br>
	 </td>

	<td valign="top" align="center" bgcolor="#d3dce3"><b>Sections</b><br>
	 </td>

	<td valign="top" align="center" bgcolor="#d3dce3"><b>Access Request Objects</b><br>
	 </td>

	<td valign="top" align="center" bgcolor="#d3dce3"><b>Functions</b><br>
	 </td>

  </tr>

    {section name=x loop=$rows}
  <tr>
    <td valign="top" bgcolor="#cccccc" align="center">
            {$rows[x].value}
     </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        {$rows[x].section}
     </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        {$rows[x].name}
     </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <input type="checkbox" name="delete_assigned_object[]" value="{$rows[x].section_value}^{$rows[x].value}">
     </td>

  </tr>
    {/section}
    <tr>
        <td valign="top" colspan="11" bgcolor="#cccccc">
            {include file="pager.tpl" pager_data=$paging_data link="?"}
        </td>
    </tr>
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
<input type="hidden" name="group_type" value="{$group_type}">
<input type="hidden" name="return_page" value="{$return_page}">    
</form>
{include file="footer.tpl"} 
