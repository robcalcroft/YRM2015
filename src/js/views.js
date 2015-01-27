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
							"<li><a href='sponsers'>Sponsers</a></li>"+
							"<li><a href='participants'>Participants</a></li>"+
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
		var speakerList = $('div.speaker-headshots'),
			tpl;

		tpl = 	'<div class="speaker-headshot">' +
					'<a href="'+ speaker.website +'"><div style="background-image: url('+ speaker.img_url +');" class="speaker-headshot-pic"></div></a>' +
					'<div class="speaker-headshot-name">'+ speaker.name +'</div>' +
					'<div class="speaker-headshot-contact">'+ speaker.subject +'</div>' +
					'<div>'+ speaker.location +'</div>' +
			 	'</div>';

		speakerList.append(tpl);
	}
}