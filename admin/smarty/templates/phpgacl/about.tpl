<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="phpgacl/header.tpl"}
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

  <body>
    <form method="post" name="about" action="about.php">
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
          <tr align="center">
            <td valign="top" rowspan="1" colspan="4" bgcolor="#cccccc"><b>phpGACL</b> <b>About [ <a href="acl_list.php">ACL List</a> ] </b><br></td>
          </tr>

          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
				<b>Help</b>
			</td>
          </tr>
          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
				Please join the <a href="https://sourceforge.net/mail/?group_id=57103">Mailing Lists</a> if you have
				any questions, comments, or support questions.
				<br>
				<br>
				<b>TIP</b>: Searching the Mailing List archives may be a good idea prior to emailing the list, <br>
				as well the below "Report" information may be helpful in any support questions.
				<br>
				<br>
				PLEASE DO NOT EMAIL ME DIRECTLY REGARDING SUPPORT QUESTIONS
				<br>
				You will receive answers faster on the mailing list, and any answers given may benefit others.
				<br>
				But if you must email me (Mike Benoit) directly, click <a href="mailto:ipso@snappymail.ca">here</a>.
			</td>
          </tr>

          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
				<b>Report</b>
			</td>
          </tr>
          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
				Report some basic information back to the phpGACL project so we know where to spend our time.
				<br>
				<b>All information will be kept private, will not be sold, and will only be used for informational purposes regarding phpGACL.</b>
				<br>
				<br>
				<textarea name="system_information" rows="10" cols="60" wrap="VIRTUAL">{$system_info}</textarea>
				<br>
				<input type="hidden" name="system_info_md5" value="{$system_info_md5}">
				<input type="submit" name="action" value="Submit">
			</td>
          </tr>

		  <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
				<b>Credits</b>
			</td>
          </tr>
          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
<pre>
{$credits}
</pre>
			</td>
          </tr>

        </tbody>
      </table>

</form>
{include file="phpgacl/footer.tpl"}
