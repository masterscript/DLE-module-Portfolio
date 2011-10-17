$(document).ready(function(){
	  
$('.MultiFile').MultiFile({ 
	accept:'jpg|gif|bmp|png|rar', max:15, STRING: { 
		remove:'удалить',
		file:'$file', 
		selected:'Выбраны: $file', 
		denied:'Неверный тип файла: $ext!', 
		duplicate:'Этот файл уже выбран:\n$file!' 
	} 
});		  
	  
$("#loading").ajaxStart(function(){
	$(this).show();
})
.ajaxComplete(function(){
        $(this).hide();
//        $.post( '/index.php', { do: 'portfolio', act: 'ajax', sub_act: 'fotos' }, function ( data ) {
//        $('#fotos').html( data );
//        });
});


$('#uploadForm').ajaxForm({
	beforeSubmit: function(a,f,o) {
		o.dataType = "html";
		$('#uploadOutput').html('Submitting...');
	},
	success: function(data) {
		var $out = $('#uploadOutput');
		$out.html('Form success handler received: <strong>' + typeof data + '</strong>');
		if (typeof data == 'object' && data.nodeType)
			data = elementToString(data.documentElement, true);
		else if (typeof data == 'object')
			data = objToString(data);
		$out.append('<div><pre>'+ data +'</pre></div>');
                $.post( '/index.php', { do: 'portfolio', act: 'ajax', sub_act: 'fotos' }, function ( data ) {
                    $('#fotos').html( data );
                });
        }
});
});
