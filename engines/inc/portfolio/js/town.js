function getRegions( country_id, region_element )
{		$('#' + region_element ).empty();
		$('#' + region_element ).load ( '/engine/inc/portfolio/ajax.php', { act: 'get_regions', country_id: country_id });
}

function getTowns ( region_id, town_element )
{		$('#' + town_element ).empty();
		$('#' + town_element ).load ( '/engine/inc/portfolio/ajax.php', { act: 'get_towns', region_id: region_id });}