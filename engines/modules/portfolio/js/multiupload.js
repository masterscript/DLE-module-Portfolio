/* 

Script: 
	adekMultiUpload,
	http://www.kajda.com/scripts/multiupload/
	
Version:
	1.05

License:

	The MIT License
	
	Copyright (c) 2007 Adrian Kajda, kajda.com
	
	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.

How to use it:
	See: http://www.kajda.com/scripts/multiupload/
*/

function adekMultiUpload(uploadform,max_rows,ext_array,input_prefix) {

	this.max_rows = max_rows;
	this.ext_array = ext_array;
	this.count = 0;
	this.j = 0;
	
	this.CheckInputExt = function(id) {
	
		allow = false;
	    var file = document.getElementById(input_prefix+'_'+id).value;
	    var dot = file.lastIndexOf('.');
	
	    if (dot != -1) {
		    var extension = file.substr(dot,file.length);
			    for (var i = 0; i < ext_array.length; i++) {
			    if (ext_array[i] == extension) { allow = true; break; }
			    }
			    		}    		   
	    if (allow) { return true; }
	    else {
	    this.remove_div(input_prefix+'_'+id);
	    this.add_new_row();
	    alert("Alert! Bad file format.\nAccepted extensions are: "
	    + (ext_array.join("  ")) + "\n");
	    return false;
	    	}
	    };
	
	this.init = function() {
	var div_inputs = document.createElement( 'div' );
		div_inputs.id = input_prefix+'_inputs';
	var div_uploadlist = document.createElement( 'div' );
		div_uploadlist.id = input_prefix+'_uploadlist';
		document.getElementById('uploadform').appendChild(div_inputs);
		document.getElementById('uploadform').appendChild(div_uploadlist);
	this.add_new_row();
	}
	
	this.remove_div = function(id) {
			el = document.getElementById(id)
			el.parentNode.removeChild(el);
		};
	
	
	this.add_new_row = function()
	{
	  if(this.count >= this.max_rows)
	    return false;
	    
	    var i = document.getElementById(input_prefix+'_inputs');
		//Create new input button 
		var new_input = document.createElement( 'input' );
		new_input.type = 'file';
		new_input.name =input_prefix+'[]';
		new_input.id = input_prefix+'_' + (this.j+1);
		new_input.selector = this;
		new_input.onchange = function(){ 
		this.selector.update_input(this.selector.j+1);
		};
		// Add new element
		i.appendChild(new_input);
	};
	
	
	this.delete_row = function(id)
	{
	  if(this.count == 1){return false};
	  var upload_files = document.getElementById(input_prefix+'_uploadlist');
	  this.remove_div(input_prefix+'_'+id);
	  this.remove_div(input_prefix+'_del'+id);
	  this.count--;
	  if(this.count+1 == this.max_rows){this.add_new_row();};
	};
	
	this.update_input = function(id) {
	  if( (this.count >= this.max_rows) || (this.CheckInputExt(id)==false) )
	    return false;
	    var ObjectName = this;
	  	var upload_files = document.getElementById(input_prefix+'_uploadlist');
		//Files list: Filename
		var new_delete = document.createElement( 'div' );
		new_delete.id = input_prefix+'_del' + id;
		new_delete.innerHTML = document.getElementById(input_prefix+'_'+id).value;
		//Files list: Delete button 
		var del_button = document.createElement( 'input' );
		del_button.type = 'button';
		del_button.value = 'Delete';
		del_button.selector = this;
		del_button.onclick = function(){ 
		this.selector.delete_row(id);
		};
		//Create list row
		upload_files.appendChild(new_delete);
		new_delete.appendChild(del_button);
		//Counters ++
		this.count++;
		this.j++;
		//Hide input and add another
		var o = document.getElementById(input_prefix+'_'+id);
		o.style.position = 'absolute';
		o.style.left = '-1920px';
		this.add_new_row();
	};

}; //end
