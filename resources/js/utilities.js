exports.pushToast = function (message) {
  var newToast = $('<div class="toast" data-delay="3000"><div class="toast-header"><strong class="mr-auto">To Pet List</strong><button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>');
  var newMessage = $('<div class="toast-body"></div>').text(message);
  newToast.append(newMessage);
  $('#toast-container').append(newToast);
  newToast.toast('show');
}
