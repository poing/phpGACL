<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
  <head>
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

  <body>
    <br>
     

    <form method="post" name="edit_aco" action="edit_aco.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" colspan="6" bgcolor="#cccccc"><b>phpGACL</b> <b>ACO Administrator</b><br>
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
            {section name=x loop=$aco}
          <tr>
            <td valign="top" bgcolor="#cccccc" align="center">
                    {$aco[x].id}
                    <input type="hidden" name="aco[{$aco[x].id}][]" value="{$aco[x].id}">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                {$section_name}
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="aco[{$aco[x].id}][]" value="{$aco[x].value}">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="aco[{$aco[x].id}][]" value="{$aco[x].order}">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="aco[{$aco[x].id}][]" value="{$aco[x].name}">                
             </td>
            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="checkbox" name="delete_aco[]" value="{$aco[x].id}">                
             </td>

          </tr>
            {/section}
            {section name=y loop=$new_aco}
          <tr>
            <td valign="top" bgcolor="#cccccc" align="center">
                    N/A
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                {$section_name}
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="new_aco[{$new_aco[y].id}][]" value="">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="new_aco[{$new_aco[y].id}][]" value="">
             </td>

            <td valign="top" bgcolor="#cccccc" align="center">
                <input type="text" size="10" name="new_aco[{$new_aco[y].id}][]" value="">                
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
    <input type="hidden" name="section_id" value="{$section_id}">
    <input type="hidden" name="return_page" value="{$return_page}">
    
    </form>
  </body>
</html>

