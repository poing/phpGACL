<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{include file="phpgacl/header.tpl"}  
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
{include file="phpgacl/acl_admin_js.tpl"}
  </head>
  <body>
    {include file="phpgacl/navigation.tpl"}  
    <form method="post" name="edit_group" action="edit_group.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Objects</th>
            <th>Functions</th>
            <th><input type="checkbox" class="checkbox" name="select_all" onClick="checkAll(this)"/></th>
          </tr>
{section name=x loop=$groups}
          <tr valign="top" align="center">
            <td>{$groups[x].id}</td>
            <td align="left">{$groups[x].name}</td>
            <td>{$groups[x].object_count}</td>
            <td>
              [ <a href="assign_group.php?group_type={$group_type}&group_id={$groups[x].id}&return_page={$return_page}">Assign {$group_type|upper}</a> ]
              [ <a href="edit_group.php?group_type={$group_type}&parent_id={$groups[x].id}&return_page={$return_page}">Add Child</a> ]
              [ <a href="edit_group.php?group_type={$group_type}&group_id={$groups[x].id}&return_page={$return_page}">Edit</a> ]
              [ <a href="acl_list.php?action=Filter&filter_{$group_type}_group_name={$groups[x].raw_name}&return_page={$return_page}">ACLs</a> ]
            </td>
            <td><input type="checkbox" class="checkbox" name="delete_group[]" value="{$groups[x].id}"></td>
          </tr>
{/section}
          <tr class="controls" align="center">
            <td colspan="3">&nbsp;</td>
            <td colspan="2">
              <input type="submit" name="action" value="Add"> <input type="submit" name="action" value="Delete">
            </td>
          </tr>
        </tbody>
      </table>
    <input type="hidden" name="group_type" value="{$group_type}">
    <input type="hidden" name="return_page" value="{$return_page}">
  </form>
{include file="phpgacl/footer.tpl"}