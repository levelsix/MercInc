/*
* Ajax functions that is used to handle ajax requests 
* Creared and modified by @Waseem Safder 
*/

var objJScrollSettings = {scrollbarWidth: 12, scrollbarMargin: 0, topBottomFadeHeight: 30, dragMinHeight: 12, dragMaxHeight: 12, showArrows: true, arrowSize: 14, wheelSpeed: 36};
function handleAjaxResponse(responseText)
{
	var jsExec = '';
	
	if (responseText.indexOf("<jsExec>") != -1)
	{
		var rawjsExec = responseText.substr(
									responseText.indexOf("<jsExec>") ,
									responseText.indexOf("</jsExec>") + 9
									);
		jsExec = rawjsExec.substr(
									rawjsExec.indexOf("<jsExec>") + 8 ,
									rawjsExec.indexOf("</jsExec>") - 8
									);

		responseText = responseText.replace(rawjsExec, '');
	}

	responseText = $.trim(responseText);

	if ( responseText.indexOf("|E_A_O|") != -1 )
	{
		var rawdata = responseText.split("|E_A_O|");

		for ( var i = 0; i < rawdata.length; i++ )
		{
			if ( (rawdata[i]).indexOf("~i~n~r~") != -1 )
			{
				var item = (rawdata[i]).split("~i~n~r~");
				if (item.length > 1)
				{
					var objElem = $.trim(item[0]);
					objElem = objElem.replace(/\#/g, '');
					objElem = objElem.replace(/\./g, '');
					if (objElem != "")
					{
						$($.trim(item[0])).html($.trim(item[1]));
					}
				}
			}
			if ( (rawdata[i]).indexOf("~a~p~n~") != -1 ) // For appending purpose
			{
				var item = (rawdata[i]).split("~a~p~n~");
				if (item.length > 1)
				{
					var objElem = $.trim(item[0]);
					objElem = objElem.replace(/\#/g, '');
					objElem = objElem.replace(/\./g, '');
					if (objElem != "")
						$($.trim(item[0])).append($.trim(item[1]));
				}
			}
		}
	}

	if (jsExec != '')
		eval(jsExec);
}

function frmCtrlsToStr(frm)
{
	var formData = "";
	
	for (var i = 0; i < frm.length; i++)
	{
		if ( $.trim( frm.elements[i].name ) != "" && frm.elements[i].disabled == false )
		{
			if ( frm.elements[i].type == "checkbox" || frm.elements[i].type == "radio" )
			{
				if ( frm.elements[i].checked )
				{
					frmVal = $.trim( frm.elements[i].value );
					frmVal = urlEncode( frmVal );
					formData = formData + $.trim( frm.elements[i].name ) + "=" + frmVal + "&";
				}
			}
			else if ( frm.elements[i].type == 'select-multiple' )
			{
				for ( var innerCount = 0; innerCount < frm.elements[i].length; innerCount++ )
				{
					if ( frm.elements[i][innerCount].selected )
					{
						frmVal = $.trim( frm.elements[i][innerCount].value );
						frmVal = urlEncode( frmVal );
						formData = formData + $.trim( frm.elements[i].name ) + "=" + frmVal + "&";
					}
				}
			}
			else
			{
				frmVal = $.trim( frm.elements[i].value );
				frmVal = urlEncode( frmVal );
				formData = formData + $.trim( frm.elements[i].name ) + "=" + frmVal + "&";
			}
		}
	}

	formData = formData.substring( 0 , formData.length - 1 );
	
	return formData;
}
var ajaxObject=null;
function callAjax(url, data, startFunc, endFunc, type, async)
{
	if (data == null)
		data = '';

	if (type == null || type == '')
		type = 'POST';
	
	if (url == null)
		url = 'ajax.php';

	if (async == null)
		async = true;
	
	if (async === false && $.browser.msie)
		async = true;
	
	ajaxObject = $.ajax(
		{
			beforeSend: function()
			{
				if (startFunc)
					eval(startFunc);
			},
			type:type,
			url:url,
			data:data,
			async:async,
			success:function(data)
			{
				handleAjaxResponse(data);
				
				if (endFunc)
					eval(endFunc);
			}
		});
	
	return ajaxObject;
}

function urlEncode(str)
{
	return escape(str).replace(/\+/g,'%2B').replace(/%20/g, '+').replace(/\*/g, '%2A').replace(/\//g, '%2F').replace(/@/g, '%40');
}
function getURLparamsArray()
{
	var lochref = location.href;
	
	if (lochref.indexOf('#') == -1)
		return null;
	
	var hashStr = lochref.substr(lochref.indexOf('#')+1);
	
	var afterHashArr = hashStr.split('/');
	
	var retArr = new Array();
	
	for (var i = 0; i < afterHashArr.length; i++)
	{
		var currVal = $.trim(afterHashArr[i]);
		
		if (currVal == '')
			continue;
		
		if (currVal.indexOf('~') == -1)
		{
			var thisKey = currVal;
			
			var thisVal = null;
			
			thisKey = $.trim(thisKey);
		}
		else
		{
			var thisKey = currVal.substr(0, currVal.indexOf('~'));
			
			var thisVal = currVal.substr(currVal.indexOf('~')+1);
			
			thisKey = $.trim(thisKey);
			
			thisVal = $.trim(thisVal);
		}
		
		retArr[thisKey] = thisVal;
	}
	
	return retArr;
}

function focusObjId(objId)
{
	$('#'+objId).focus();
}


