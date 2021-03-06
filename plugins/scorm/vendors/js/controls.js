/* SVN FILE: $Id$ */
/**
 * Ósmosis LMS: <http://www.osmosislms.org/>
 * Copyright 2008, Ósmosis LMS
 *
 * This file is part of Ósmosis LMS.
 * Ósmosis LMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ósmosis LMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ósmosis LMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @filesource
 * @copyright		Copyright 2008, Ósmosis LMS
 * @link			http://www.osmosislms.org/
 * @package			org.osmosislms
 * @subpackage		org.osmosislms.app
 * @since			Version 2.0 
 * @version			$Revision: 451 $
 * @modifiedby		$LastChangedBy: jose.zap $
 * @lastmodified	$Date: 2008-06-25 15:45:38 -0430 (Wed, 25 Jun 2008) $
 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 */
var ScormControl = new function(){
	var lastLink = null;
	var loading_msg = null;
	this.updateUI = function(link, msg) {
		var href = link.href;
		var id = link.id;
		var id_data = id.match(/([^0-9])*([0-9]+)/);
		var local_sco_id = id_data[2];
		var local_sco_number =  parseInt(id_data[2]);
		if (this.loading_msg==null) {
			this.loading_msg = msg;
		}
		this.lastLink = link;
		this.updateLinks(local_sco_number-1, local_sco_number+1, local_sco_id);
		var className = link.className;
		id_data = className.match(/([^0-9])*([0-9]+)/);
		var sco_id = parseInt(id_data[2]);		

		$('script#api').remove();
		$.blockUI('<h1><img src="' + webroot + '/img/default/loading.gif" /> ' + this.loading_msg + '</h1>'); 
		$('#api').remove();
		$('head').createAppend(
			'script',
			{
				'id' : 'api',
				'src' : webroot + 'scorm/scos/api/' + sco_id + '.js',
				'type' : 'text/javascript',
				'onload' : function() {
					$('iframe#viewport')[0].src = href;
					$.unblockUI();
					$.scrollTo('#scorm_ui', 800, {easing : 'easein'});
				}
			}, ''
		);
		// TODO: Safari sucks, correctly handle onload event
		// if ($.browser.safari) {
		// 			$('head').createAppend(
		// 				'script', {}, '$("iframe#viewport")[0].src = "' + href + '";$.unblockUI();'
		// 			);
		// 		}
		return false;
	}
	
	this.storeDataCallback = function () {
		$().ajaxSuccess(function (event,request,options) {
			if (!options.url.match(/.*scorm_attendee_trackings\/store_data.*/))
				return;
			real_id_data = ScormControl.lastLink.className.match(/([^0-9])*([0-9]+)/);
			real_id = real_id_data[2];
			//This will query all scoes completed in the same scorm than real_id sco
			ScormControl.getCompleted(real_id)
		});
	}
	
	this.getCompleted = function (sco) {
		$.get(webroot+'scorm/scos/completed/'+sco+'.json',{},ScormControl.showCompleted,'json');
	}
	
	this.showCompleted = function (data) {
		var completed = data.response;
		if (completed.length < 1) return;
		$('#scorm_toc li:not(.completed)').each(function() {	
			if (jQuery.inArray(this.className.match(/([^0-9])*([0-9]+)/)[2],completed) > -1) {
				$(this).addClass('completed');
			}		
		});
	}
	
	this.updateLinks = function(prev, next, id) {
		this.updateLink(prev, 'previous');
		this.updateLink(next, 'next');
	}

	this.updateLink = function(id, which) {
		id = 'sco-'+id;
		link_id = 'scorm_control_' + which;
		if (document.getElementById(id)!=null) {
			var link_element = document.getElementById(link_id);
			link_element.href = document.getElementById(id).href;
			link_element.className = id;
			link_element.style.display = "inline";
			link_element.onclick = function() {
				var className = this.className;
				var id_data = className.match(/(.*)(\d+)/);
				var link = document.getElementById(id_data[1] + (id_data[2]));
				ScormControl.updateUI(link);
			}
		} else {
			document.getElementById(link_id).style.display = "none";
		}
	}
}
