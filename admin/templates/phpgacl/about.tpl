<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

{include file="phpgacl/header.tpl"}
    <meta name="generator" content="HTML Tidy, see www.w3.org">
    <title>phpGACL Admin</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  </head>

  <body>
    {if $first_run != 1}
	{include file="phpgacl/navigation.tpl"}
	{/if}
      <table cellpadding="2" cellspacing="2" border="2" width="100%">
        <tbody>
		{if $first_run != 1}
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
				<b>Donate</b>
			</td>
          </tr>
          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
				Time working on phpGACL means less time that I can work to get paid.
				<br>
				Therefore any donations I receive will help me to devote more time to developing phpGACL.
				<br>
				<br>
				However, I'd much rather donations in the form of code and/or documentation.

				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="ipso@snappymail.ca">
				<input type="hidden" name="item_name" value="php Generic Access Control List">
				<input type="hidden" name="no_note" value="1">
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="tax" value="0">
				<input type="image" src="https://www.paypal.com/images/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
				</form>
			</td>
          </tr>
        {/if}
          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
				{if $first_run != 1}
					<b>Report</b>
				{else}
					<font color="#ff0000">* <b>Report</b> *</font>
				{/if}
			</td>
          </tr>
          <tr>
			<td valign="top" align="center" rowspan="1" colspan="4" bgcolor="#cccccc">
    			<form method="post" name="about" action="about.php">
				Report some basic information back to the phpGACL project so we know where to spend our time.
				<br>
				<b>All information will be kept private, will not be sold, and will only be used for informational purposes regarding phpGACL.</b>
				<br>
				<br>
				<textarea name="system_information" rows="10" cols="60" wrap="VIRTUAL">{$system_info}</textarea>
				<br>
				<input type="hidden" name="system_info_md5" value="{$system_info_md5}">
				<input type="submit" name="action" value="Submit">
				</form>
			</td>
          </tr>
		{if $first_run != 1}
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
        {/if}
        </tbody>
      </table>
{include file="phpgacl/footer.tpl"}
