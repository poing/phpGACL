<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="phpgacl/header.tpl"}
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
{include file="phpgacl/acl_admin_js.tpl"}
  </head>

{include file="phpgacl/navigation.tpl"}
<form method="get" name="acl_list" action="acl_list.php">
<table cellpadding="2" cellspacing="2" border="2" width="100%">
  <tr>
    <td colspan="12" valign="top" bgcolor="#cccccc" align="center">
        <b>Filter</b>
    </td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b><br></b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section > Object</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Group</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Access</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Enabled</b>
    </td>
  </tr>
  <tr>
    <td valign="middle" bgcolor="#c0c0c0" align="center">
        <b>ACO</b>
    </td>
    <td valign="top" bgcolor="#c0c0c0" align="center">
        <input type="text" name="filter_aco_section_name" size="15" value="{$filter_aco_section_name}">
        > <input type="text" name="filter_aco_name" size="15" value="{$filter_aco_name}">
    </td>
    <td valign="top" bgcolor="#c0c0c0" align="center">
		<br>   
    </td>
    <td rowspan="3" valign="middle" bgcolor="#cccccc" align="center">
		 <select name="filter_acl_section_name" tabindex="0">
			{html_options options=$options_filter_acl_sections selected=$filter_acl_section_name}
		</select>
    </td>
    <td rowspan="3" valign="middle" bgcolor="#cccccc" align="center">
		 <select name="filter_allow" tabindex="0">
			{html_options options=$options_filter_allow selected=$filter_allow}
		</select>
    </td>
    <td rowspan="3" valign="middle" bgcolor="#cccccc" align="center">
		 <select name="filter_enabled" tabindex="0">
			{html_options options=$options_filter_enabled selected=$filter_enabled}
		</select>
    </td>
  </tr>
  <tr>
    <td valign="middle" bgcolor="#cccccc" align="center">
        <b>ARO</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
		<input type="text" name="filter_aro_section_name" size="15" value="{$filter_aro_section_name}">
        > <input type="text" name="filter_aro_name" size="15" value="{$filter_aro_name}">
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <input type="text" name="filter_aro_group_name" size="15" value="{$filter_aro_group_name}">
    </td>
  </tr>
  <tr>
    <td valign="middle" bgcolor="#c0c0c0" align="center">
        <b>AXO</b>
    </td>
    <td valign="top" bgcolor="#c0c0c0" align="center">
        <input type="text" name="filter_axo_section_name" size="15" value="{$filter_axo_section_name}">
        > <input type="text" name="filter_axo_name" size="15" value="{$filter_axo_name}">
    </td>
    <td valign="top" bgcolor="#c0c0c0" align="center">
        <input type="text" name="filter_axo_group_name" size="15" value="{$filter_axo_group_name}">
    </td>
  </tr>
  <tr>
	<td valign="middle" colspan="6" bgcolor="#cccccc" align="left">
        <b>Return Value:</b> <input type="text" name="filter_return_value" size="30" value="{$filter_return_value}">
    </td>
  </tr>

  <tr>
    <td colspan="12" valign="top" bgcolor="#999999" align="center">
		<input type="submit" name="action" value="Filter">
    </td>
  </tr>

</table>
<br>

<table cellpadding="2" cellspacing="2" border="2" width="100%">
  <tr>
	<td valign="top" colspan="10" bgcolor="#cccccc">
		{include file="phpgacl/pager.tpl" pager_data=$paging_data link="?action=$action&filter_aco_section_name=$filter_aco_section_name&filter_aco_name=$filter_aco_name&filter_aro_section_name=$filter_aro_section_name&filter_aro_name=$filter_aro_name&filter_axo_section_name=$filter_axo_section_name&filter_axo_name=$filter_axo_name&filter_aro_group_name=$filter_aro_group_name&filter_axo_group_name=$filter_axo_group_name&filter_return_value=$filter_return_value&filter_allow=$filter_allow&filter_enabled=$filter_enabled&"}
	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>ID</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Type</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section > Object</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Group</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Access</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Enabled</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Functions</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <input type="checkbox" name="select_all" onClick="checkAll(this)"/>
    </td>
  </tr>

    {section name=x loop=$acls}
  <tr>
    <td valign="middle" rowspan="6" bgcolor="#cccccc" align="center">
            {$acls[x].id}
    </td>
    <td valign="middle" bgcolor="#c0c0c0" align="center">
		<b>ACO</b>
	</td>
    <td valign="top" bgcolor="#c0c0c0" align="left" nowrap>
		{section name=y loop=$acls[x].aco}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].aco[y].aco}
			<br>
		{/section}
		<br>
    </td>
    <td valign="top" bgcolor="#c0c0c0" align="left" nowrap>
		<br>
    </td>
    
    <td valign="middle" rowspan="3" bgcolor="#cccccc" align="center">
        {$acls[x].section_name}
    </td>
    <td valign="middle" rowspan="3" bgcolor="{if $acls[x].allow}green{else}red{/if}" align="center">
		{if $acls[x].allow}
			ALLOW
		{else}
			DENY
		{/if}
    </td>
    <td valign="middle" rowspan="3" bgcolor="{if $acls[x].enabled}green{else}red{/if}" align="center">
		{if $acls[x].enabled}
			Yes
		{else}
			No
		{/if}
    </td>
    <td valign="middle" rowspan="6" bgcolor="#cccccc" align="center">
        [ <a href="acl_admin.php?action=edit&acl_id={$acls[x].id}&return_page={$return_page}">Edit</a> ]
    </td>
    <td valign="middle" rowspan="6" bgcolor="#cccccc" align="center">
        <input type="checkbox" name="delete_acl[]" value="{$acls[x].id}">
    </td>
  </tr>

  <tr>
    <td valign="middle" bgcolor="#cccccc" align="center">
		<b>ARO</b>
	</td>
    <td valign="top" bgcolor="#cccccc" align="left" nowrap>
		{section name=y loop=$acls[x].aro}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].aro[y].aro}
			<br>
		{/section}
		<br>
    </td>
    <td valign="top" bgcolor="#cccccc" align="left" nowrap>
		{section name=y loop=$acls[x].aro_groups}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].aro_groups[y].group}
			<br>
		{/section}
		<br>
    </td>  
  </tr>
  <tr>
    <td valign="middle" bgcolor="#c0c0c0" align="center">
		<b>AXO</b>
	</td>
    <td valign="top" bgcolor="#c0c0c0" align="left">
		{section name=y loop=$acls[x].axo}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].axo[y].axo}
			<br>
		{/section}
		<br>
    </td>
    <td valign="top" bgcolor="#c0c0c0" align="left">
		{section name=y loop=$acls[x].axo_groups}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].axo_groups[y].group}
			<br>
		{/section}
		<br>
    </td>
  </tr>
  <tr>
    <td valign="middle" colspan="6" bgcolor="#cccccc" align="left">
        <b>Return Value:</b> {$acls[x].return_value}<br>
    </td>
  </tr>
  <tr>
    <td valign="middle" colspan="6" bgcolor="#cccccc" align="left">
        <b>Note:</b> {$acls[x].note}<br>
    </td>
  </tr>
  <tr>
    <td valign="middle" colspan="6" bgcolor="#cccccc" align="left">
        <b>Updated Date:</b> {$acls[x].updated_date}<br>
    </td>
  </tr>
    {/section}
  <tr>
	<td valign="top" colspan="10" bgcolor="#cccccc">
		{include file="phpgacl/pager.tpl" pager_data=$paging_data link="?action=$action&filter_aco_section_name=$filter_aco_section_name&filter_aco_name=$filter_aco_name&filter_aro_section_name=$filter_aro_section_name&filter_aro_name=$filter_aro_name&filter_axo_section_name=$filter_axo_section_name&filter_axo_name=$filter_axo_name&filter_aro_group_name=$filter_aro_group_name&filter_axo_group_name=$filter_axo_group_name&filter_return_value=$filter_return_value&filter_allow=$filter_allow&filter_enabled=$filter_enabled&"}
	</td>
  </tr>
	  <tr>
		<td valign="top" bgcolor="#999999" colspan="8">
		</td>
		<td valign="top" bgcolor="#999999" colspan="2">
		  <div align="center">
			<input type="submit" name="action" value="Delete">
		  </div>
		</td>
	</tr>
    </table>
    <input type="hidden" name="return_page" value="{$return_page}">
</form>
{include file="phpgacl/footer.tpl"}