<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

<script LANGUAGE="JavaScript">
</script>

{include file="acl_admin_js.tpl"}

<body onload="populate(document.acl_admin.aco_section,document.acl_admin.elements['aco[]'], '{$js_aco_array_name}');populate(document.acl_admin.aro_section,document.acl_admin.elements['aro[]'], '{$js_aro_array_name}')">
    <form method="get" name="object_search" action="obj_search.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" rowspan="1" colspan="5" bgcolor="#cccccc"><b>phpGACL</b> <b>Object Search</b><br>
             </td>
          </tr>

          <tr>
            <td valign="top" align="center" bgcolor="#d3dce3">&nbsp;<b>{$object_type} {$section_value} Search</b><br>
             </td>
          </tr>

          <tr>
            <td valign="middle" bgcolor="#cccccc" align="center">
				Value: <input type="text" name="value_search_str" value="">
				<br>
				Name: <input type="text" name="name_search_str" value="">
				<br>
				<input type="submit" name="action" value="Search"><br>
             </td>
          </tr>

          <tr>
            <td valign="top" bgcolor="#999999" rowspan="1" colspan="5">
              <div align="center">
                <input type="submit" name="action" value="Search">
              </div>
            </td>
          </tr>
        </tbody>
      </table>
	<input type="hidden" name="object_type" value="{$object_type}">
	<input type="hidden" name="section_value" value="{$section_value}">
	
    </form>
{include file="footer.tpl"} 

