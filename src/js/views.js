YRM.views = {

	renderParticipants: function(participant, empty) {
		var tpl,
			participantList = $('.participant-list');

		if(empty) {
			participantList.append('No participants currently');
			return;
		}
		tpl = "<div class='participant'>"+ participant + "</div>";
		participantList.append(tpl);
	},

	addToolTips: function() {
		var ttContent = "<ul class='nav-tooltip'>"+
							"<li><a href='sponsors'>Sponsors</a></li>"+
							"<li><a href='accomodation'>Accomodation</a></li>"+
							"<li><a href='bmcbamc'>BMC&#47;BAMC 2015</a></li>"+
							"<li><a href='poster'>Poster</a></li>"+
							"<li><a href='past-yrms'>Past YRMs</a>"+
							"<li><a href='committee'>Committee</a>"+
							"<li><a href='faqs'>FAQs</a></li>"+
						"</ul>";

		$('#nav-more').tooltipster({
			content: $(ttContent),
			interactive: true,
			iconTouch: true
		})
	},

	animateCounters: function(element, length) {
		var counter = 0, num;

		if(length === 0) {
			return false;
		}

		(function count() {
			num = parseInt(element.html())
			element.html(++num);
			++counter;
			if(counter !== length) {
				setTimeout(function() { count() }, 75);
			}
		})()
	},

	renderSpeakers: function(speaker) {
		var speakerList = $('div.speaker-headshots');
		var subjects    = speaker.subjects || [];
		var info        = speaker.other || '';
		var subjectsLen = subjects.length;
		var tpl, speakerTracks = '', prefix = "";

		if(!info) {
			for (var i = subjectsLen - 1; i >= 0; i--) {
				if(i !== subjectsLen - 1) {
					prefix = ', ';
				}
				speakerTracks += prefix + '<a href="track#'+ subjects[i].split(" ").join("-") +'"><span class="speaker-headshot-contact">'+ speaker.subjects[i] +'</span></a>';
			};
		} else {
			speakerTracks = "<span class='speaker-headshot-contact'>" + info + "</span>";
		}

		tpl = 	'<div class="speaker-headshot">' +
					'<a href="'+ speaker.website +'"><div style="background-image: url('+ speaker.img_url +');" class="speaker-headshot-pic"></div></a>' +
					'<div class="speaker-headshot-name">'+ speaker.name +'</div>' +
					'<div class="speaker-subjects">' + speakerTracks + '</div>'+
					'<div>'+ speaker.location +'</div>' +
			 	'</div>';

		speakerList.append(tpl);
	},

	track: function(speakers, extras) {

		var len          = speakers.length;
		var trackSpeaker = $('#track_speaker');
		var title        = $('#track_speaker_title');

		// If there are no results then
		// set the title and detail accordingly
		if(len === 0) {
			trackSpeaker.html("No results for this track");
			title.html(extras);
			return;
		}

		// Set the title to the subject of
		// the person
		title.html(document.location.hash.replace('#','').split('-').join(' '));

		// If there is only one speaker we
		// need slightly different wording
		if(len === 1) {
			trackSpeaker.html(
				"The keynote speaker for this track is " +
				speakers[0].name + " from " + speakers[0].location
			);
			return;
		}

		trackSpeaker.html("The keynote speakers for this track are: ");

		// If there is more than one speaker then have a list of speakers.
		for (var i = len - 1; i >= 0; i--) {
				if(i !== len - 1) {
					trackSpeaker.append(' and ');
				}
				trackSpeaker.append(speakers[i].name + " from " + speakers[i].location);
		};

	},

	renderDinnerText: function(state, count) {
		var el = $('#dinner-text');

		if(state) {
			el.append('Currently there are ' + count + ' places avaliable');
		} else {
			el.append('Currently no places are available, but we hope to release more places soon. Buy this item to add your name to the waiting list.')
		}
	}
}