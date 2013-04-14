<html><head><title>SharedTree</title></head>
<body>
<h1>SharedTree</h1>
{if $error}{$error}<br>{/if}
{if $request.search}
{if $result_count > 10}<a href="#advanced">New Search</a><br>{/if}

{if $pages > 1}
	Page:
	{section name=page start=1 loop=$pages+1}
	{if $smarty.section.page.index != $request.page}
		<a href="search.php?search=1&page={$smarty.section.page.index}&family_name={$request.family_name|escape:'url'}&given_name={$request.given_name|escape:'url'}&gender={$request.gender}&birth_year={$request.birth_year}&birth_place={$request.birth_place|escape:'url'}&death_year={$request.death_year}&death_place={$request.death_place|escape:'url'}&adbc={$request.adbc}&range={$request.range}">{$smarty.section.page.index}</a>
	{else}
		{$smarty.section.page.index}
	{/if}
	{/section}
{/if}
{if $results}
Showing {$result_count} of {$total_records} records
<table border="1"><tr><td>Name</td><td>Birth</td><td>Place</td></tr>
{foreach item=result from=$results}
<tr>
<td><a href="index.php?id={$result.person_id}">{$result.family_name}, {$result.given_name}</a></td>
<td>{$result.birth_year}</td>
<td>{$result.location}</td>
</tr>
{/foreach}
</table>
{else}
<i>Sorry, no results were found</i>
{/if}
{/if}

<a name="advanced" /><form method="POST" action="search.php">
<h2>Search for Individual</h2>
Last name: <input type="text" name="family_name" value="{$request.family_name}"><br>
Given name: <input type="text" name="given_name" value="{$request.given_name}"><br>
<input type="submit" name="search" value="Search"><br>
Gender: {html_radios name="gender" options=$gender_options selected=$request.gender}<br>
Birth year: <input type="text" name="birth_year" size="4" value="{$request.birth_year}"><br>
Place: <input type="text" name="birth_place" value="{$request.birth_place}"><br>
Death year: <input type="text" name="death_year" size="4" value="{$request.death_year}"><br>
Place: <input type="text" name="death_place" value="{$request.death_place}"><br>
Range: <select name="range">
<option value=0 {if $request.range==0}selected{/if}>Exact</option>
<option value=1 {if $request.range==1}selected{/if}>+/- 1 year</option>
<option value=2 {if $request.range==2}selected{/if}>+/- 2 years</option>
<option value=5 selected>+/- 5 years</option>
<option value=10 {if $request.range==10}selected{/if}>+/- 10 years</option>
<option value=15 {if $request.range==15}selected{/if}>+/- 15 years</option>
<option value=20 {if $request.range==20}selected{/if}>+/- 20 years</option>
<option value=50 {if $request.range==50}selected{/if}>+/- 50 years</option>
<option value=100 {if $request.range==100}selected{/if}>+/- 100 years</option>
</select>{html_radios name="adbc" options=$adbc selected=$request.adbc}<br />
<input type="submit" name="search" value="Search">
</form>

</body>
</html>
