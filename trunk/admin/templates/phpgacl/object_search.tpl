<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="phpgacl/header.tpl"} 
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <style type="text/css">
    {literal}
    input.search {
    	width: 100%;
    }
    select.search {
    	width: 99%;
    }
    {/literal}
    </style>
  </head>
{include file="phpgacl/acl_admin_js.tpl"}
<body onload="document.object_search.name_search_str.focus();">
    <form method="get" name="object_search" action="object_search.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td colspan="2"><b>phpGACL Object Search</b></td>
          </tr>
          <tr>
            <th colspan="2">{$object_type_name} > {$section_value_name}</th>
          </tr>
          <tr>
            <td width="25%"><b>Name:</b></td>
            <td width="75%"><input type="text" class="search" name="name_search_str" value="{$name_search_str}" /></td>
          </tr>
          <tr>
			<td><b>Value:</b></td>
			<td><input type="text" class="search" name="value_search_str" value="{$value_search_str}" /></td>
		  </tr>
		  <tr class="controls" align="center">
		  	<td colspan="2"><input type="submit" name="action" value="Search" /></td>
          </tr>
        </tbody>
      </table>
{if (strlen($total_rows) != 0)}
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr class="spacer">
          	<td colspan="2"></td>
          </tr>
          <tr>
            <th colspan="2">{$total_rows} Objects Found</th>
          </tr>
		{if ($total_rows > 0)}
          <tr valign="middle" align="center">
            <td>
			  <select name="objects" class="search" tabindex="0" size="10" multiple>
			    {html_options options=$options_objects}
			  </select>
            </td>
            <td width="50">
				<input type="button" name="select" value="&nbsp;>>&nbsp;" onClick="select_item(opener.document.{$src_form}.{$object_type}_section, document.object_search.elements['objects'], opener.document.{$src_form}.elements['selected_{$object_type}[]'])">
             </td>
          </tr>
		{/if}
        </tbody>
      </table>
{/if}
	<input type="hidden" name="src_form" value="{$src_form}">
	<input type="hidden" name="object_type" value="{$object_type}">	
	<input type="hidden" name="section_value" value="{$section_value}">
  </form>
{include file="phpgacl/footer.tpl"}