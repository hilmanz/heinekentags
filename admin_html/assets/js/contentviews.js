   

function listofuserviews(data){
	var html ="";

	$.each(data,function(k,v){
	 
		html+='<tr>';
			html+='<th>'+v.name+' '+v.last_name+'</th>';
			html+='<th>'+v.email+'</th>';
			html+='<th>'+v.pagename+'</th>';
			 html+='<th><a class="Btn icon-update" href="'+basedomain+'administrator/edit/'+v.id+'" ></a></th>';
			html+='<th><a class="Btn icon-delete" href="'+basedomain+'administrator/unusers/'+v.id+'" ></a></th>';
		html+='</tr>';
	});
	return html;
}	
 

function monthconverter(i){
	var i  = parseInt(i,10);
	var month=new Array();
	month[1]="January";
	month[2]="February";
	month[3]="March";
	month[4]="April";
	month[5]="May";
	month[6]="June";
	month[7]="July";
	month[8]="August";
	month[9]="September";
	month[10]="October";
	month[11]="November";
	month[12]="December";
return month[i];

}

function post_json(data){
	var response = jQuery.ajax({
                    type: "POST",
                    url: data.url,
                    data: data.params,
                    dataType: data.type
	            });
	return response;
}

function paging_ajax_userreg(fungsi,start){ 

	$.post(basedomain+"home/ajaxPaging",{start:start,ajax:1},function(response){
		if(response){
			  if(response.status==1){
		var no = start+1;
		var str="";
		$.each(response.data.result,function(k,v){  
	
			str+='<tr>';
				str+='<td>'+no+'</td>';
				str+='<td>'+v.brand+'</td>';
				str+='<td>'+v.name+'</td>';
				str+='<td>';							
					str+='<a href="'+basedomain+'home/edit?id='+v.id+'" class="btn btn-link" >Edit</a>';
					str+='| <a href="'+basedomain+'home/hapus?id='+v.id+'" class="btn btn-link" >Delete</a>';
				str+='</td>';
			str+='</tr>';
			no++;
		});
		$('.pagingtemplates').html(str);
		
	}else{
	   $('.pagingtemplates').html('<tr><td colspan="5">'+response.msg+'</td></tr>');
	 
	}
		}
	},"JSON");
}

function paging_ajax_regis(fungsi,start){ 

	$.post(basedomain+"home/ajaxPaging",{start:start,ajax:1},function(response){
		if(response){
			  if(response.status==1){
		var no = start+1;
		var str="";
		$.each(response.data.result,function(k,v){  
			str+='<tr>';
				str+='<td width="1">'+no+'</td>';
				str+='<td><span>'+v.title+'</span></td> ';
				str+='<td><span>'+v.description+'</span></td> ';
					str+='<td><span>'+v.date+'</span></td> ';
			
			
				str+='<td>';
			
				
					str+='<a href="'+basedomain+'home/editeducation?id='+v.id_education+'" class="button"><span class="icon-pencil">&nbsp;</span> Edit</a>'; 
					 str+='<a href="'+basedomain+'home/deleteeducation?id='+v.id_education+'" class="button"><span class="icon-trash">&nbsp;</span> Delete</a>'; 
				str+='</td>';
			str+='</tr>';
			no++;
		});
		$('.pagilist').html(str);
		
	}else{
	   $('.pagilist').html('<tr><td colspan="5">'+response.msg+'</td></tr>');
	 
	}
		}
	},"JSON");
}
function paging_ajax_news(fungsi,start){ 

	$.post(basedomain+"news/ajaxPaging",{start:start,ajax:1},function(response){
		if(response){
			  if(response.status==1){
		var no = start+1;
		var str="";
		$.each(response.data.result,function(k,v){  
			str+='<tr>';
				str+='<td width="1">'+no+'</td>';
				str+='<td><span>'+v.title+'</span></td> ';
				str+='<td><span>'+v.description+'</span></td> ';
				str+='<td><span>'+v.date+'</span></td> ';
			
			
				str+='<td>';
			
				
					str+='<a href="'+basedomain+'news/editnews?id='+v.id_news+'" class="button"><span class="icon-pencil">&nbsp;</span> Edit</a>'; 
					 str+='<a href="'+basedomain+'news/deletenews?id='+v.id_news+'" class="button"><span class="icon-trash">&nbsp;</span> Delete</a>'; 
				str+='</td>';
			str+='</tr>';
			no++;
		});
		$('.pagilist').html(str);
		
	}else{
	   $('.pagilist').html('<tr><td colspan="5">'+response.msg+'</td></tr>');
	 
	}
		}
	},"JSON");
}
function paging_ajax_location(fungsi,start){ 

	$.post(basedomain+"location/ajaxPaging",{start:start,ajax:1},function(response){
		if(response){
			  if(response.status==1){
		var no = start+1;
		var str="";
		$.each(response.data.result,function(k,v){  
			str+='<tr>';
				str+='<td width="1">'+no+'</td>';
				str+='<td><span>'+v.name+'</span></td> ';
				str+='<td><span>'+v.logitude+'</span></td> ';
				str+='<td><span>'+v.attitude+'</span></td> ';
				str+='<td><span>'+v.date+'</span></td> ';
			
			
				str+='<td>';
			
				
					str+='<a href="'+basedomain+'location/editlocation?id='+v.id_location+'" class="button"><span class="icon-pencil">&nbsp;</span> Edit</a>'; 
					 str+='<a href="'+basedomain+'location/deletelocation?id='+v.id_location+'" class="button"><span class="icon-trash">&nbsp;</span> Delete</a>'; 
				str+='</td>';
			str+='</tr>';
			no++;
		});
		$('.pagilist').html(str);
		
	}else{
	   $('.pagilist').html('<tr><td colspan="5">'+response.msg+'</td></tr>');
	 
	}
		}
	},"JSON");
}

function paging_ajax_career(fungsi,start){ 

	$.post(basedomain+"career/ajaxPaging",{start:start,ajax:1},function(response){
		if(response){
			  if(response.status==1){
		var no = start+1;
		var str="";
		$.each(response.data.result,function(k,v){  
			str+='<tr>';
				str+='<td width="1">'+no+'</td>';
				str+='<td><span>'+v.title_job+'</span></td> ';
				str+='<td><span>'+v.job_description+'</span></td> ';
		
				str+='<td><span>'+v.date+'</span></td> ';
			
			
				str+='<td>';
			
				
					str+='<a href="'+basedomain+'career/editcareer?id='+v.id_location+'" class="button"><span class="icon-pencil">&nbsp;</span> Edit</a>'; 
					 str+='<a href="'+basedomain+'career/deletecareer?id='+v.id_location+'" class="button"><span class="icon-trash">&nbsp;</span> Delete</a>'; 
				str+='</td>';
			str+='</tr>';
			no++;
		});
		$('.pagilist').html(str);
		
	}else{
	   $('.pagilist').html('<tr><td colspan="5">'+response.msg+'</td></tr>');
	 
	}
		}
	},"JSON");
}
function paging_report_pages(fungsi,start){ 

	$.post(basedomain+"reports/ajaxPaging",{start:start,ajax:1},function(response){
		if(response){
			  if(response.status==1){
		var no = start+1;
		var str="";
		$.each(response.data.result,function(k,v){  
			str+='<tr>';
				str+='<td class="center">'+no+'</td>';
				str+='<td>'+v.brand+'</td>';
				str+='<td>'+v.first_name+' '+v.middle_name+' '+v.last_name+'</td>';
				str+='<td>'+v.fb_id+'</td>';
				str+='<td><a href="https://twitter.com/'+v.twitter_id+'" target="_blank">'+v.twitter_id+'</a></td>';
				str+='<td>';	  
				str+='<img height="55px" width="55px" src="'+basedomainpath+'public_html/public_assets/reports/default.png" />';
				str+='</td>';
				str+='<td>'+v.birthday+'</td>';
				str+='<td>'+v.location+'</td>';					   
				str+='<td>'+v.collect_date+'</td>';
			str+='</tr>';
			no++;
		});
		$('.paginglist').html(str);
		
	}else{
	   $('.paginglist').html('<tr><td colspan="5">'+response.msg+'</td></tr>');
	 
	}
		}
	},"JSON");
}
				