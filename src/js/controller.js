// Declare ALL vars in this file
// Add subsequent files to Gruntfile AFTER this file
"use strict";
var YRM = YRM || {};
YRM.controller = {};

YRM.globs = {
	apiRoot: '/YRM2015/build/api/',
	speakerCount: 14,
	eventLength: 4
};

YRM.controller.common = {

	/**
	 * Initialise the main controller and
	 * start the page specific task checker
	 */
	init: function() {
		var me = this;
		$(document).ready(function(){
			YRM.controller.tasks.runner();
			YRM.controller.common.runner();
		});
	},

	runner: function() {
		var views = YRM.views;

		// Add the tooltips
		views.addToolTips();
	},


}
YRM.controller.tasks = {

	/**
	 * The central runner for the tasks 
	 * for various pages
	 */
	runner: function() {
		var page = $('.container-common')[0]?$('.container-common')[0].id:undefined || $('.home')[0].id;
		
		// Switch the id of the current container
		// to find out which task to run.
		switch(page) {
			case 'home':
				this.home();
				break;
			case 'participants':
				this.participants();
				break;
			case 'speakers':
				this.speakers();
				break;
		}
	},

	/**
	 * Request the names from the API 
	 * and pass each one to the 
	 * rendering function in the views.
	 */
	participants: function() {
		$.getJSON(YRM.globs.apiRoot + 'getRegistrants.php', function(participants) {

			// If there are no participants
			// then display an alternate string
			if(participants.length === 0) {
				YRM.views.renderParticipants(null, true)
				return;
			}
			for (var i = participants.length - 1; i >= 0; i--) {
				YRM.views.renderParticipants(participants[i])
			};
		})
	},

	/**
	 * Main task for the home screen
	 */
	home: function() {

		// Starts the counters
		$.getJSON(YRM.globs.apiRoot + 'getRegistrants.php', function(participants) {
			var anCntrs = YRM.views.animateCounters;

			// Participant Number
			anCntrs($('#participant-no'), participants.length);

			// Speaker Number
			anCntrs($('#speaker-no'), YRM.globs.speakerCount);

			// Day Number
			anCntrs($('#day-no'), YRM.globs.eventLength);

		})
		.error(function(err) {
			$('#participant-no').html('a lot')
			YRM.views.animateCounters($('#speaker-no'), YRM.globs.speakerCount)
			YRM.views.animateCounters($('#day-no'), YRM.globs.eventLength)
		})

	},

	speakers: function() {

		$.getJSON(YRM.globs.apiRoot + 'getSpeakers.php', function(speakers) {

			for (var i = speakers.length - 1; i >= 0; i--) {
				
				YRM.views.renderSpeakers(speakers[i]);
			};
		}) 
	}
}

