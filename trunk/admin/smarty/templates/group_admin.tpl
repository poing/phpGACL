<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
  <head>
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

  <body>
    <br>
     

    <form method="post" name="edit_group" action="edit_group.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" colspan="3" bgcolor="#cccccc"><b>phpGACL</b> <b>Group Administrator [ <a href="acl_list.php">ACL List</a> ]</b><br>
             </td>
          </tr>

          <tr>
            <td valign="top" bgcolor="#d3dce3" align="center"><b>ID</b> </td>
            <td valign="top" bgcolor="#d3dce3" align="center"><b>Name</b> </td>
            <td valign="top" bgcolor="#d3dce3" align="center"><b>Functions</b> </td>
          </tr>
            {section name=x loop=$groups}
          <tr>
            <td valign="top" bgcolor="#cccccc" align="center">
                    {$groups[x].id}
             </td>
            <td valign="top" bgcolor="#cccccc" align="left">
                    {$groups[x].name}
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                Don't forget, to click here --> 
                [ <a href="assign_aro_group.php?group_id={$groups[x].id}&return_page={$return_page}">Assign ARO</a> ]
                [ <a href="edit_group.php?parent_id={$groups[x].id}&return_page={$return_page}">Add Child</a> ]
                [ <a href="edit_group.php?group_id={$groups[x].id}&return_page={$return_page}">Edit</a> ]
                <input type="checkbox" name="delete_group[]" value="{$groups[x].id}">
             </td>

          </tr>
            {/section}

          <tr>
            <td valign="top" bgcolor="#999999" colspan="2">
                &nbsp;
            </td>
            <td valign="top" bgcolor="#999999">
              <div align="center">
                <input type="submit" name="action" value="Add">
                <input type="submit" name="action" value="Delete">
              </div>
            </td>

          </tr>
        </tbody>
      </table>
    <input type="hidden" name="return_page" value="{$return_page}">    
    </form>
  </body>
</html>

