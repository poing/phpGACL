<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

<div align="center">
Keep in mind the server hosting this may be overloaded. Preliminary benchmarks<br>
on my Celeron 800 running Apache, MySQL, and X11 show about 5ms per
acl_check() with caching turned off, <br>
which of course is heavily dependant on the database itself.
</div>
<Br>

<form method="post" name="acl_list" action="acl_list.php">
<table cellpadding="2" cellspacing="2" border="2" width="100%">
  <tr align="center">
	<td valign="top" colspan="10" bgcolor="#cccccc"><b>phpGACL ACL Test
		[ <a href="admin/acl_list.php">ACL List</a> ]
		</b>
		<br>
	 </td>
  </tr>

  <tr>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>#</b>
     </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section > ACO</b>
     </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Section > ARO</b>
     </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>ACL_CHECK() Code</b>
     </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Time (ms)</b>
     </td>
    <td valign="top" bgcolor="#cccccc" align="center">
        <b>Access</b>
     </td>
  </tr>

  {section name=x loop=$acls}
  <tr>
    <td valign="middle" bgcolor="#cccccc" align="center">
		{$smarty.section.x.iteration}
     </td>
    <td valign="middle" bgcolor="#cccccc" align="center">
		{$acls[x].display_aco_name}
     </td>
    <td valign="top" bgcolor="#cccccc" align="left">
        {$acls[x].aro_section_name} > {$acls[x].aro_name}
     </td>
    <td valign="top" bgcolor="#cccccc" align="left">
		acl_check('{$acls[x].aco_section_value}', '{$acls[x].aco_value}', '{$acls[x].aro_section_value}', '{$acls[x].aro_value}')	
     </td>  
    <td valign="top" bgcolor="#cccccc" align="center">
		{$acls[x].acl_check_time}
     </td>
    <td valign="middle" bgcolor="{if $acls[x].access}green{else}red{/if}" align="center">
		{if $acls[x].access}
			ALLOW
		{else}
			DENY
		{/if}
     </td>
  </tr>
  {/section}
</form>
</table>

<br>
<table align="center" cellpadding="2" cellspacing="2" border="2" width="30%">
  <tr align="center">
	<td colspan="2" valign="top" bgcolor="#cccccc">
		<b>Summary</b>
	</td>
  </tr>
  <tr align="center">
	<td valign="top" bgcolor="#cccccc">
		<b>Total ACL Check(s)</b>
	</td>
	<td valign="top" bgcolor="#cccccc">
		{$total_acl_checks}
	</td>
  </tr>
  <tr align="center">
	<td valign="top" bgcolor="#cccccc">
		<b>Average Time / Check</b>
	</td>
	<td valign="top" bgcolor="#cccccc">
		{$avg_acl_check_time}ms
	</td>
  </tr>
</table>
<br>
{include file="footer.tpl"}

