<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="phpgacl/header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>
<body>
{include file="phpgacl/navigation.tpl"}
<form method="get" name="acl_debug" action="acl_debug.php">
<table cellpadding="2" cellspacing="2" border="2" width="100%">
  <tr>
    <th>&nbsp;</th>
    <th>ACO Section</th>
    <th>ACO Value</th>
    <th>ARO Section</th>
    <th>ARO Value</th>
    <th>AXO Section</th>
    <th>AXO Value</th>
    <th>Root ARO Group ID</th>
    <th>Root AXO Group ID</th>
    <th>&nbsp;</th>
  </tr>
  <tr valign="middle" align="center">
    <td><b>acl_query(</b></td>
    <td><input type="text" name="aco_section_value" size="15" value="{$aco_section_value}"></td>
    <td><input type="text" name="aco_value" size="15" value="{$aco_value}"></td>
    <td><input type="text" name="aro_section_value" size="15" value="{$aro_section_value}"></td>
    <td><input type="text" name="aro_value" size="15" value="{$aro_value}"></td>
    <td><input type="text" name="axo_section_value" size="15" value="{$axo_section_value}"></td>
    <td><input type="text" name="axo_value" size="15" value="{$axo_value}"></td>
    <td><input type="text" name="root_aro_group_id" size="15" value="{$root_aro_group_id}"></td>
    <td><input type="text" name="root_axo_group_id" size="15" value="{$root_axo_group_id}"></td>
    <td><b>)</b></td>
  </tr>
  <tr class="controls" align="center">
    <td colspan="10">
    	<input type="submit" name="action" value="Submit">
    </td>
  </tr>
</table>
{if count($acls) gt 0}
<table cellpadding="2" cellspacing="2" border="2" width="100%">
  <tr>
    <th>ACL ID</th>
    <th>ACO Section</th>
    <th>ACO Value</th>
    <th>ARO Section</th>
    <th>ARO Value</th>
    <th>AXO Section</th>
    <th>AXO Value</th>
    <th>ARO Group ID</th>
    <th>ARO Group Tree Level</th>
    <th>AXO Group ID</th>
    <th>AXO Group Tree Level</th>
    <th>Return Value</th>
    <th>Access</th>
    <th>Updated Date</th>
  </tr>
{foreach from=$acls item=acl}
  <tr valign="top" align="left">
    <td valign="middle" rowspan="2" align="center">
        {$acl.id}
    </td>
    <td nowrap>
		{$acl.aco_section_value}
    </td>
    <td nowrap>
		{$acl.aco_value}
    </td>

    <td nowrap>
		{$acl.aro_section_value}<br>
    </td>
    <td nowrap>
		{$acl.aro_value}<br>
    </td>

    <td nowrap>
		{$acl.axo_section_value}<br>
    </td>
    <td nowrap>
		{$acl.axo_value}<br>
    </td>

    <td nowrap>
		{$acl.aro_group_id}<br>
    </td>
    <td nowrap>
		{$acl.aro_tree_level}<br>
    </td>

    <td nowrap>
		{$acl.axo_group_id}<br>
    </td>
    <td nowrap>
		{$acl.axo_tree_level}<br>
    </td>

    <td valign="middle" align="center">
        {$acl.return_value}<br>
    </td>
    <td valign="middle" class="{if $acl.allow}green{else}red{/if}" align="center">
		{if $acl.allow}
			ALLOW
		{else}
			DENY
		{/if}
    </td>
    <td valign="middle" align="center">
        {$acl.updated_date}
     </td>
  </tr>
  <tr valign="middle" align="left">
    <td colspan="13">
        <b>Note:</b> {$acl.note}
    </td>
  </tr>
{/foreach}
</table>
{/if}
<input type="hidden" name="return_page" value="{$return_page}">
</form>
{include file="phpgacl/footer.tpl"}