<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="phpgacl/header.tpl"}
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
{include file="phpgacl/acl_admin_js.tpl"}
    <style type="text/css">
	ul {literal}{{/literal}
		padding: 0px 0px 0px 0px;
		margin: 0px 0px 0px 0px;
		list-style-type: none;
	}
	ul li {literal}{{/literal}
		padding: 0px;
		margin: 0px;
		font-weight: bold;
	}
	ol {literal}{{/literal}
		padding: 0px 0px 0px 22px;
		margin: 0px;
	}
	ol li {literal}{{/literal}
		padding: 0px;
		margin: 0px;
		font-weight: normal;
	}
	div.divider {literal}{{/literal}
		margin: 2px 0px;
		padding: 0px;
		border-bottom: 1px solid grey;
	}
   </style>
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
	<td valign="top" colspan="8" bgcolor="#cccccc">
		{include file="phpgacl/pager.tpl" pager_data=$paging_data link="?action=$action&filter_aco_section_name=$filter_aco_section_name&filter_aco_name=$filter_aco_name&filter_aro_section_name=$filter_aro_section_name&filter_aro_name=$filter_aro_name&filter_axo_section_name=$filter_axo_section_name&filter_axo_name=$filter_axo_name&filter_aro_group_name=$filter_aro_group_name&filter_axo_group_name=$filter_axo_group_name&filter_return_value=$filter_return_value&filter_allow=$filter_allow&filter_enabled=$filter_enabled&"}
	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#cccccc" align="center" width="2%">
        <b>ID</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center" width="24%">
        <b>ACO</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center" width="24%">
        <b>ARO</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center" width="24%">
        <b>AXO</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center" width="10%">
        <b>Access</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center" width="10%">
        <b>Enabled</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center" width="4%">
        <b>Functions</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center" width="2%">
        <input type="checkbox" name="select_all" onClick="checkAll(this)"/>
    </td>
  </tr>

{foreach from=$acls item=acl}
  {cycle name=bg1 assign=bg1 values="#c0c0c0,#d0d0d0"}
  <tr bgcolor="{$bg1}">
    <td valign="middle" rowspan="3" align="center">
            {$acl.id}
    </td>
    <td valign="top" align="left">
	{if count($acl.aco) gt 0}
		<ul>
		{foreach from=$acl.aco key=section item=objects}
			<li>{$section}<ol>
			{foreach from=$objects item=obj}
				<li>{$obj}</li>
			{/foreach}
			</ol></li>
		{/foreach}
		</ul>
	{else}
		&nbsp;
	{/if}
    </td>

    <td valign="top" align="left">
	{if count($acl.aro) gt 0}
		<ul>
		{foreach from=$acl.aro key=section item=objects}
			<li>{$section}<ol>
			{foreach from=$objects item=obj}
				<li>{$obj}</li>
			{/foreach}
			</ol></li>
		{/foreach}
		</ul>
		{if count($acl.aro_groups) gt 0}
		<div class="divider"></div>
		{/if}
	{/if}
	{if count($acl.aro_groups) gt 0}
		<b>Groups</b><ol>
		{foreach from=$acl.aro_groups item=group}
			<li>{$group}</li>
		{/foreach}
		</ol>
	{/if}
    </td>

    <td valign="top" align="left">
	{if count($acl.axo) gt 0}
		<ul>
		{foreach from=$acl.axo key=section item=objects}
			<li>{$section}<ol>
			{foreach from=$objects item=obj}
				<li>{$obj}</li>
			{/foreach}
			</ol></li>
		{/foreach}
		</ul>
		{if count($acl.axo_groups) gt 0}
		<div class="divider"></div>
		{/if}
	{/if}
	{if count($acl.axo_groups) gt 0}
		<b>Groups</b><ol>
		{foreach from=$acl.axo_groups item=group}
			<li>{$group}</li>
		{/foreach}
		</ol>
	{/if}
    </td>

    <td valign="middle" bgcolor="{if $acl.allow}green{else}red{/if}" align="center">
		{if $acl.allow}
			ALLOW
		{else}
			DENY
		{/if}
    </td>
    <td valign="middle" bgcolor="{if $acl.enabled}green{else}red{/if}" align="center">
		{if $acl.enabled}
			Yes
		{else}
			No
		{/if}
    </td>
    <td valign="middle" rowspan="3" align="center">
        [ <a href="acl_admin.php?action=edit&acl_id={$acl.id}&return_page={$return_page}">Edit</a> ]
    </td>
    <td valign="middle" rowspan="3" align="center">
        <input type="checkbox" name="delete_acl[]" value="{$acl.id}">
    </td>
  </tr>

  <tr bgcolor="{$bg1}">
    <td valign="top" colspan="3" align="left">
        <b>Return Value:</b> {$acl.return_value}
    </td>
    <td valign="middle" colspan="2" align="middle">
        {$acl.section_name}
    </td>
  </tr>
  <tr bgcolor="{$bg1}">
    <td valign="top" colspan="3" align="left">
        <b>Note:</b> {$acl.note}
    </td>
    <td valign="middle" colspan="2" align="middle">
        {$acl.updated_date|date_format:"%d-%b-%Y&nbsp;%H:%M:%S"}
    </td>
  </tr>
{/foreach}
  <tr>
	<td valign="top" colspan="8" bgcolor="#cccccc">
		{include file="phpgacl/pager.tpl" pager_data=$paging_data link="?action=$action&filter_aco_section_name=$filter_aco_section_name&filter_aco_name=$filter_aco_name&filter_aro_section_name=$filter_aro_section_name&filter_aro_name=$filter_aro_name&filter_axo_section_name=$filter_axo_section_name&filter_axo_name=$filter_axo_name&filter_aro_group_name=$filter_aro_group_name&filter_axo_group_name=$filter_axo_group_name&filter_return_value=$filter_return_value&filter_allow=$filter_allow&filter_enabled=$filter_enabled&"}
	</td>
  </tr>
	  <tr>
		<td valign="top" bgcolor="#999999" colspan="6">
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