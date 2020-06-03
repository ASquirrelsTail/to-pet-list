$(function () {
  $('#list_image').on('change', function () {
    if ($(this).prop('files').length > 0) {

      $('#list_old_image').hide();
      $('#list_new_image').remove();
      var file = $(this).prop('files')[0];
      var urlEncImage = URL.createObjectURL(file);
      var imageData = new Image();
      imageData.onload = function () {
        if (this.width < 600 || this.height < 315) {
          $('#list_image').addClass('is-invalid')
                          .siblings('.invalid-feedback').remove();
          $('#list_image').after($('<span class="invalid-feedback" role="alert"><strong>Selected image is too small, please select another.</strong></span>'));
          $('#list_image').attr('type', '').val('').attr('type', 'file');

        } else {
          $('#list_image').removeClass('is-invalid')
                          .siblings('.invalid-feedback').remove();
          var newImage = $('<div id="list_new_image"></div>');
          newImage.css('background-image', 'url(' + urlEncImage + ')');
          $('#image-container').append($(newImage));
          newImage.hide().fadeIn(500);
        }
      };
      imageData.src = urlEncImage;
      
    } else {
      $('#list_new_image').fadeOut(500, function () {
        $(this).remove();
      });
      $('#list_image').removeClass('is-invalid')
                     .siblings('.invalid-feedback').remove();
    }
  });

  $('#list_form').on('submit', function (e) {
    if (!validateList()) e.preventDefault();
  });
});

function validateList() {
  if ($('#list_name').val().trim === '') {
    $('#list_name').addClass('is-invalid')
                   .siblings('.invalid-feedback').remove();
    $('#list_name').after($('<span class="invalid-feedback" role="alert"><strong>The name field is required.</strong></span>'));
    return false;
  }
  if ($('#list_image').hasClass('is-invalid') && $('list_image').prop('files').length > 0) return false;
  return true;
}
