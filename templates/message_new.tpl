{include file="message_header.tpl" title="Message List"}

<form action="messages.php" method="post">
<input type="hidden" action="send">
To: <input type="textbox" name="to_uid"><br />

Subject: <input type="textbox" name="subject"><br />


Body:<br />
<textarea name="body" width="60" height="20"></textarea>
<br />
<input type="submit" name="send" value="Send Message">
<input type="submit" name="discard" value="Discard">
</form>

{include file="message_footer.tpl"}
