var task = new (function() {

	var self = this;

	this.ini = function(taskJson) {
		for (var i = 0; i < taskJson.length; i++) {
			task = taskJson[i];
			taskCard = $('<li></li>', {'class': 'card'}).append($('<div></div>', {'class': 'bullet glyphicon glyphicon-stats'})).append($('<div></div>', {'class': 'cardTitle'}).text(task.title)).append($('<div></div>', {'class': 'cardBody'}).text(task.body));

			taskCard.appendTo($('#tasks'));
		}
	}
});