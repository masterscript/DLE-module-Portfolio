<div align=right class="smain"><a href="/?do=portfolio&act=add" style="font-size:12px;">Додати (редагувати) своє портфоліо</a></div>
<h1>{title}</h1>

<div style="padding:6px; border:1px solid #c4c4c4; margin-top:5px; -moz-border-radius: 5px; border-radius: 5px;">

<table cellpadding="4" cellspacing="0" border="0">
<tr>
	<td style="padding:4px">Знайти всі анкети:</td>
	<td style="padding:4px">
		в країні:
		<select name="country" id="country" onChange="getRegions(this.value, 'region');">
			<option value="0"></option>
			{country}
		</select>
	</td>
	<td style="padding:4px">
		в області:
		<select name="region" id="region" onChange="getTowns(this.value, 'town');">
			<option value="0"></option>
		</select>
	</td>
	<td style="padding:4px">
		в місті:
		<select name="town" id="town">
			<option value="0"></option>
		</select>
	</td>
</tr>
<tr>
	<td style="padding:4px">Сортувати по:</td>
	<td style="padding:4px" colspan="3">
		<select name="sort" id="sort">
			<option value=0></option>
			<option value="cost">Ціні</option>
			<option value="add_date">Даті</option>
		</select>
		<select name="order" id="order">
			<option value="asc">По зростанню</option>
			<option value="desc">По спаданню</option>
		</select>
		<input type="submit" value="Шукати" style="width:80px; font:11px Tahoma, Verdana;" onClick="search('content_field');"/>
	</td>

</tr>
</table>

</div>
<br />