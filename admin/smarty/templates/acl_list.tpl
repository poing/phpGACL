<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

<form method="post" name="acl_list" action="acl_list.php">
    <table cellpadding="2" cellspacing="2" border="2" width="100%">
  <tr align="center">
	<td valign="top" colspan="11" bgcolor="#cccccc"><b>phpGACL ACL List
		[ <a href="group_admin.php?group_type=aro">ARO Group Admin</a> ]
		[ <a href="group_admin.php?group_type=axo">AXO Group Admin</a> ]
		[ <a href="acl_admin.php?return_page=acl_list.php">ACL Admin</a> ]
		[ <a href="acl_test.php">ACL Test</a> ]
		</b>
		<br>
	</td>
  </tr>
  <tr>
	<td valign="top" colspan="11" bgcolor="#cccccc">
		{include file="pager.tpl" pager_data=$paging_data link="?"}
	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>ID</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section > ACO</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section > ARO</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>ARO Group</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section > AXO</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>AXO Group</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Return Value</b>
    </td>

    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Access</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Enabled</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Updated Date</b>
    </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Functions</b>
    </td>
     
  </tr>

    {section name=x loop=$acls}
  <tr>
    <td valign="middle" rowspan="2" bgcolor="#cccccc" align="center">
            {$acls[x].id}
    </td>

    <td valign="top" bgcolor="#cccccc" align="left" nowrap>
		{section name=y loop=$acls[x].aco}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].aco[y].aco}
			<br>
		{/section}
		<br>
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
     
    <td valign="top" bgcolor="#cccccc" align="left">
		{section name=y loop=$acls[x].axo}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].axo[y].axo}
			<br>
		{/section}
		<br>
    </td>
    <td valign="top" bgcolor="#cccccc" align="left">
		{section name=y loop=$acls[x].axo_groups}
			<b>{$smarty.section.y.iteration}.</b> {$acls[x].axo_groups[y].group}
			<br>
		{/section}
		<br>
    </td>

    <td valign="middle" bgcolor="#cccccc" align="center">
        {$acls[x].return_value}<br>
    </td>
    <td valign="middle" bgcolor="{if $acls[x].allow}green{else}red{/if}" align="center">
		{if $acls[x].allow}
			ALLOW
		{else}
			DENY
		{/if}
    </td>
    <td valign="middle" bgcolor="{if $acls[x].enabled}green{else}red{/if}" align="center">
		{if $acls[x].enabled}
			Yes
		{else}
			No
		{/if}
    </td>
    <td valign="middle" bgcolor="#cccccc" align="center">
        {$acls[x].updated_date}
     </td>
    <td valign="middle" rowspan="2" bgcolor="#cccccc" align="center">
        [ <a href="acl_admin.php?action=edit&acl_id={$acls[x].id}&return_page={$return_page}">Edit</a> ]
        <input type="checkbox" name="delete_acl[]" value="{$acls[x].id}">
    </td>
  </tr>
  <tr>
    <td valign="middle" colspan="9" bgcolor="#cccccc" align="left">
        <b>Note:</b> {$acls[x].note}<br>
    </td>
  </tr>
    {/section}
  <tr>
	<td valign="top" colspan="11" bgcolor="#cccccc">
		{include file="pager.tpl" pager_data=$paging_data link="?"}
	</td>
  </tr>
	  <tr>
		<td valign="top" bgcolor="#999999" colspan="10">
		</td>
		<td valign="top" bgcolor="#999999">
		  <div align="center">
			<input type="submit" name="action" value="Delete">
		  </div>
		</td>
	</tr>
    </table>
    <input type="hidden" name="return_page" value="{$return_page}">
</form>
{include file="footer.tpl"}

