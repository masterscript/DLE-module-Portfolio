function search(param2 )
{

    var v_country = document.getElementById('country').value;
    var v_region = document.getElementById('region').value;
    var v_town = document.getElementById('town').value;
    var v_sort = document.getElementById('sort').value;
    var v_order = document.getElementById('order').value;
		$('#' + param2 ).empty();
		$('#' + param2 ).load ( '/engine/inc/portfolio/ajax.php', { act : "search", country : v_country, region : v_region, town : v_town, sort : v_sort, order : v_order });
}
