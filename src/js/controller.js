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
			case 'track':
				YRM.controller.router.trackInit();
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

	speakers: function(packet) {

		// Check to see if a packet has been
		// passed in to the func
		var packet = packet || {};

		// If there is a filter set up the var
		// for use in the API call
		var filter = packet.filter || "";

		$.getJSON(YRM.globs.apiRoot + 'getSpeakers.php' + filter, function(speakers) {

			for (var i = speakers.length - 1; i >= 0; i--) {
				
				// For each speaker, render the speaker
				YRM.views.renderSpeakers(speakers[i]);

			};

			packet.callbackParams = packet.callbackParams || {};

			packet.callback ? packet.callback(speakers, packet.callbackParams) : null;
		}) 
	}
}

YRM.controller.router = {

	trackInit: function() {
		
		// Get the track name using the hash
		var track = document.location.hash.replace("#", "");

		// Split by - to get each word
		var trackWords    = track.split("-"), 
			trackWordsLen = trackWords.length,
			exceptionWords;

		exceptionWords = ['and'];

		// Uppercase the first letter of each word
		for (var i = trackWordsLen - 1; i >= 0; i--) {
			if(exceptionWords.indexOf(trackWords[i]) === -1) {
				trackWords[i] = trackWords[i][0].toUpperCase() + trackWords[i].substr(1);
			}
		};

		var trackWordsJoined = "?subject=" + trackWords.join("%20");

		YRM.controller.tasks.speakers({
			filter: trackWordsJoined,
			callbackParams: trackWords.join(' '),
			callback: YRM.views.track	
		})

	}

}

