<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

  <body>
    <br>
     

    <form method="post" name="edit_objects" action="edit_objects.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" colspan="6" bgcolor="#cccccc"><b>phpGACL</b> <b>{$object_type|upper} Administrator</b>
             <b>[ <a href="acl_admin.php?return_page={$return_page}">ACL Admin</a> ] </b>
            <br>
             </td>
          </tr>

          <tr>
            <td valign="top" bgcolor="#d3dce3" align="center"><b>ID</b> </td>
            <td valign="top" bgcolor="#d3dce3" align="center"><b>Section</b> </td>

            <td valign="top" bgcolor="#d3dce3" align="center"><b>Value</b> </td>

            <td valign="top" bgcolor="#d3dce3" align="center"><b>Order</b> </td>

            <td valign="top" bgcolor="#d3dce3" align="center"><b>Name</b> </td>
            <td valign="top" bgcolor="#d3dce3" align="center"><b>Functions</b> </td>
          </tr>
            {section name=x loop=$objects}
          <tr>
            <td valign="top" bgcolor="#cccccc" align="center">
                    {$objects[x].id}
                    <input type="hidden" name="objects[{$objects[x].id}][]" value="{$objects[x].id}">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                {$section_name}
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="objects[{$objects[x].id}][]" value="{$objects[x].value}">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="objects[{$objects[x].id}][]" value="{$objects[x].order}">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="40" name="objects[{$objects[x].id}][]" value="{$objects[x].name}">                
             </td>
            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="checkbox" name="delete_object[]" value="{$objects[x].id}">                
             </td>

          </tr>
            {/section}
            {section name=y loop=$new_objects}
          <tr>
            <td valign="top" bgcolor="#cccccc" align="center">
                    N/A
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                {$section_name}
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="new_objects[{$new_objects[y].id}][]" value="">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="new_objects[{$new_objects[y].id}][]" value="">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="40" name="new_objects[{$new_objects[y].id}][]" value="">                
             </td>
            <td valign="top" bgcolor="#cccccc" align="center">
                &nbsp;
             </td>

          </tr>
            {/section}

          <tr>
            <td valign="top" bgcolor="#999999" colspan="5">
              <div align="center">
                <input type="submit" name="action" value="Submit"> <input type="reset" value="Reset"><br>
              </div>
            </td>
            <td valign="top" bgcolor="#999999">
              <div align="center">
                <input type="submit" name="action" value="Delete">
              </div>
            </td>

          </tr>
        </tbody>
      </table>
    <input type="hidden" name="section_value" value="{$section_value}">
    <input type="hidden" name="object_type" value="{$object_type}">
    <input type="hidden" name="return_page" value="{$return_page}">
    
    </form>
{include file="footer.tpl"} 
