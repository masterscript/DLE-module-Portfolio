<script type="text/javascript" src="/engine/modules/portfolio/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="/engine/modules/portfolio/js/jquery.form.js"></script>
<script type="text/javascript" src="/engine/inc/portfolio/js/town.js"></script>


<form method="POST">
	<input type="hidden" name="act" value="do_edit" />
	<input type="hidden" name="id" value="{id}" />

	<table cellpadding="4" cellspacing="0">
	<tr>
		<td style="padding:4px">Послуги:</td>
		<td style="padding:4px">
		<select name="port[services]" id="services">
			<option value="0"></option>
			{services}
		</select>
		</td>
	</tr>
	<tr>
		<td style="padding:4px">Ціна за 1 кг:</td>
		<td style="padding:4px">
			<input type="text" name="port[price]" value="{price}" />
		</td>
	</tr>
	<tr>
		<td style="padding:4px">Мінімальна сума заказу:</td>
		<td style="padding:4px">
			<input type="text" name="port[minimum_order]" value="{minimum_order}" />
		</td>
	</tr>
	<tr>
		<td style="padding:4px">Країна:</td>
		<td style="padding:4px">
			<select name="port[country]" id="country" onChange="getRegions(this.value, 'region');">
				<option value="0"></option>
				{country}
			</select>
		</td>
	</tr>
	<tr>
		<td style="padding:4px">Область:</td>
		<td style="padding:4px">
			<select name="port[region]" id="region" onChange="getTowns(this.value, 'town');">
				{region}
			</select>
		</td>
	</tr>
	<tr>
		<td style="padding:4px">Місто:</td>
		<td style="padding:4px">
			<select name="port[town]" id="town">
				{town}
			</select>
		</td>
	</tr>
	<tr>
		<td style="padding:4px">Адреса:</td>
		<td style="padding:4px"><input type="text" name="port[address]" value="{address}" /></td>
	</tr>
	<tr>
		<td style="padding:4px">Коментар про себе чи послугах:</td>
		<td style="padding:4px">
			<textarea name="port[comment]" cols="60" rows="8">{comment}</textarea>
		</td>
	</tr>
	<tr>
		<td style="padding:4px">ICQ:</td>
		<td style="padding:4px"><input type="text" name="port[icq]" value="{icq}" /></td>
	</tr>
	<tr>
		<td style="padding:4px">Skype:</td>
		<td style="padding:4px"><input type="text" name="port[skype]" value="{skype}" /></td>
	</tr>
	<tr>
		<td style="padding:4px">E-mail:</td>
		<td style="padding:4px"><input type="text" name="port[email]" value="{email}" /></td>
	</tr>
	<tr>
		<td style="padding:4px">Телефон:</td>
		<td style="padding:4px"><input type="text" name="port[phone]" value="{phone}" /></td>
	</tr>
	<tr>
		<td style="padding:4px">Найкращий час для зв'язку:</td>
		<td style="padding:4px"><input type="text" name="port[contact_time]" value="{contact_time}" /></td>
	</tr>
	<!--
	<tr>
		<td style="padding:4px">Фото кондитера:</td>
		<td style="padding:4px"><input type="file" name="foto" value="" /></td>
	</tr>
	-->
	<tr>
		<td></td>
		<td style="padding:2px" colspan="2"><input type="checkbox" name="port[approve]" value="1" {approve} /> опублікувати на сайтику</td>
	</tr>
	</table>

	<input type="submit" value="Збережисі" />

<!--
	<div style="padding:4px">

		<div style="padding-bottom:10px">Завантаження фото послуг:</div>

		<div id="fotos">{images}</div>
		<div id="upload"></div>
		<div style="padding-top:10px;">
		      <input type="button" onClick="$('#upload').fileUploadStart();" class="bbcodes" value="Розпочати завантаження" />
    		  <input type="button" onClick="$('#upload').fileUploadClearQueue();" class="bbcodes" value="Очистити чергу" />
		</div>

	</div>
-->


</form>

