<?php

error_reporting(E_ERROR|E_WARNING|E_PARSE);

require_once("config.php");
require_once("inc/main.php");
$T->assign('title',"Buy SharedTree");
$T->display('header.tpl');
?>
<h1>Buy SharedTree for $9</h1>

<table class="portal" width="600">
<tr><td>

<h3>Why am I "selling" SharedTree?</h3>
<p>No joke, I'm actually serious. I started SharedTree to solve the problem my large, extended family was having coordinating all of our genealogy work. Although SharedTree has worked great for my family, I no longer work with PHP on a regular basis and have found that I just don't have the resources anymore to properly maintain it. In fact, I currently spend less than one hour per month now and I think the site deserves a little bit more.</p>
<p>The reason I'm selling the site for only $9 is because I never intended for the site to make money and I don't want the new "buyer" to look at it as an investment for which he has to recoup his costs.</p> 

<h3>Things you must be willing to agree to:</h3>
<ul>
<li>Keep all current features free for all users (but you can decide to add "premium" features to cover future costs)</li>
<li>Maintain privacy of living records</li>
<li>Never sell user data to other companies without consent (I'd rather just turn off the website)</li>
<li>Maintain the spirit of open content (this is what drove me to create SharedTree)</li>
<li>Keep me around so I can continue to help out (I don't have time to lead anymore, but I'd like to follow)</li>
</ul>

<h3>What you need to have to qualify:</h3>
<ul>
<li>Working knowledge of the following technologies</li>
<ol>
<li>PHP</li>
<li>HTML, CSS, and Java Script</li>
<li>MySQL</li>
<li>Linux</li>
<li>Apache</li>
</ol>
<li>A passion for genealogy work</li>
<li>A web server of your own or the ability to pay the $15 hosting fee if there aren't any donations one month</li>
<li>An active membership here at SharedTree</li>
</ul>

<h3>How big is SharedTree?</h3>
<ul>
<li>691529 individuals</li>
<li>253990 marriages</li>
<li>3061 users</li>
<li>started in August 2006</li>
</ul>

<p>Please contact me directly if you might be interested in "buying" SharedTree. Individuals or businesses are welcome. I would prefer someone with a strong knowledge of the English language. You must be over 18 and willing to sign a contract.</p>

<h3 align="center"><a href="contact.php">Send me an email</a></h3>

<b>Trevor Allred<br/>
Founder of SharedTree.com<br/>
</b>
</td>
</tr>
</table>
<?php
$T->display("footer.tpl");
?>
