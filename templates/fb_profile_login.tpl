{include file="fb_header.tpl"}
<fb:error message="{$error}" />
<h2>Create a SharedTree Account</h2>
<fb:editor>
	<fb:editor-text label="Email address" name="email" value="{$udata.email}"/>
	<fb:editor-custom label="Password">
		<input type="password" name="password">
	</fb:editor-custom>
	<fb:editor-custom label="Password again">
		<input type="password" name="password2">
	</fb:editor-custom>
	<fb:editor-divider/>
	<fb:editor-text label="Last name" name="family_name" value="{$udata.last_name}"/>
	<fb:editor-text label="First name" name="given_name" value="{$udata.first_name}"/>
	<fb:editor-text label="City" name="city" value="{$udata.current_location.city}"/>
	<fb:editor-text label="State" name="state_code" value="{$udata.current_location.state}"/>
	<fb:editor-text label="Postal code" name="postal_code" value="{$udata.current_location.zip}"/>
	<fb:editor-buttonset>
		<fb:editor-button name="register" value="Create account"/>
	</fb:editor-buttonset>
</fb:editor>

<h2>Link an Existing SharedTree Account</h2>
<fb:editor>
	<fb:editor-text label="Email address" name="email" value="{$udata.email}" />
	<fb:editor-custom label="Password">
		<input type="password" name="password">
	</fb:editor-custom>
	<fb:editor-buttonset>
		<fb:editor-button name="login" value="Link Existing Account"/>
	</fb:editor-buttonset>
</fb:editor>


{include file="fb_footer.tpl"}
