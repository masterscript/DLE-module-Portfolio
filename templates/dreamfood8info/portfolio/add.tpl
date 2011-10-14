<script type="text/javascript" src="/engine/modules/portfolio/js/jquery-1.3.2.js"></script>
<!-- JQuery UI Forms  -->
<link rel="stylesheet" href="/engine/modules/portfolio/js/ui_themes/ui-lightness/jquery.ui.all.css">
<script type="text/javascript" src="/engine/modules/portfolio/js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="/engine/modules/portfolio/js/ui/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="/engine/modules/portfolio/js/ui/jquery.ui.button.min.js"></script>
<script type="text/javascript" src="/engine/modules/portfolio/js/ui/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="/engine/modules/portfolio/js/ui/jquery.ui.dialog.min.js"></script>
<!--   End UI Forms   -->

<script type="text/javascript" src="/engine/modules/portfolio/js/ui_scripts/form_add_city.js"></script>

<div class="demo">
    <div id="dialog-form" title="Add new city">
	<p class="validateTips">Write the name of the city in the box below.</p>
	<form>
	    <fieldset>
		<label for="name">New name of the city:</label>
                <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
	    </fieldset>
	</form>
    </div>
</div>


<table width="100%">
    <tr>
        <td width="5"> </td>
        <td background="{THEME}/images/mtdbgred.png" class="ntitle">

        	{title}

        </td>
        <td width="5"> </td>
    </tr>
    <tr>
        <td>  </td>
        <td class="news" style="padding-bottom:10px;">

<form method="POST">

	<table cellpadding="4" cellspacing="0">
	<tr>
		<td style="padding:4px">Ваші послуги:</td>
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
			<input type="text" name="port[price]" value="{price}" title="Вкажіть ціну в грн., тільки цифру, без слів" />
		</td>
	</tr>
	<tr>
		<td style="padding:4px" align=left> Мінімальна сума замовлення: </td>
		<td style="padding:4px">
			<input type="text" name="port[minimum_order]" value="{minimum_order}" title="Вкажіть ціну за яку Вам не шкода буде тратити свого часу в грн., тільки цифру, без слів"  />
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
			<select name="port[town]" id="town" title="Якщо Вашого міста нема в списку, зверніться до адміністратора, щоб його додав">
				{town}
			</select>
		</td>
	</tr>
	<tr>
		<td style="padding:4px">Адреса:</td>
		<td style="padding:4px"><input type="text" name="port[address]" value="{address}" title="Щоби людям, що зацікавляться Вашими послугами було краще зрозуміло де ви знаходитесь вкажіть свою адресу, якщо не повну то хоч вулицю чи район" /></td>
	</tr>
	<tr>
		<td style="padding:4px" align=left>Коментар про себе чи свої послуги:</td>
		<td style="padding:4px">
			<textarea title="Кілька слів про Ваші послуги або про себе" name="port[comment]" cols="60" rows="8">{comment}</textarea>
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
		<td style="padding:4px" align=left>Найкращий час для звя'зку:</td>
		<td style="padding:4px"><input type="text" name="port[contact_time]" value="{contact_time}" title="В який час найкраще телефонувати до Вас для замовлення" /></td>
	</tr>
	<tr>
		<td style="padding:4px">Ваше фото:</td>
		<td style="padding:4px"><input type="file" name="foto" value="" title="Додайте свою фотографію розмірів шириною не менше 200" /></td>
	</tr>
	</table>

	<div style="padding:4px">

		<div style="padding-bottom:10px">Фото ваших робіт: <br /><small>Нажміть кнопочку <i>Browse</i> (мають бути включені JavaScript і Flash) і виділіть декілька фото ваших робіт (утримуючи клавішу CTRL, або виділивши мишкою). Коли всі фото будуть вибрані, нажміть <i>Розпочати завантаження</i>. Коли зменшеншені копії Ваших фото зявляться на сторінці, можете натискати кнопку <i>Додати портфоліо</i></div>

		<div id="fotos">{images}</div>
		<div id="upload"></div>
		<div style="padding-top:10px;">
		      <input type="button" onClick="$('#upload').fileUploadStart();" class="bbcodes" value=" Розпочати завантаження " />
    		  <input type="button" onClick="$('#upload').fileUploadClearQueue();" class="bbcodes" value=" Очистити чергу " />
		</div>

	</div>

	<input type="submit" value=" {button} " />
</form>
<br /><br /><br />
<div align=left>
 P.s. Ваше портфоліо зявиться на сайті після перевірки адміністратором. <br>
В любий зручний для Вас час ви зможете додати нову фотографію чи видалити стару, змінити ціну чи контактну інформацію. Всі зміни відбуватимуться в режимі онлайн. 
</div>
</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td width="5"></td>
		<td" class="slink" align="right">&nbsp;</td>
        <td width="5"></td>
    </tr>
</table>

