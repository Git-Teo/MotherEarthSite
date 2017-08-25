function submitMainForm() {
  if (!validatePrice($('#PriceFrom').val()) && !validatePrice($('#PriceTo').val())) {
    $('#PriceFrom, #PriceTo').val('');
    $('#PriceFrom, #PriceTo').attr("disabled", "disabled");
  }

  if (($('#PriceFrom').val() == '') && ($('#PriceTo').val() == '')) {
    $('#PriceFrom, #PriceTo').attr("disabled", "disabled");
  }

  $('.mainForm').submit();
}

function submitFormNoRange() {
  $('#PriceFrom, #PriceTo').val('');
  $('#PriceFrom, #PriceTo').attr("disabled", "disabled");
  $('.mainForm').submit();
}

function submitEmptyForm() {
  window.location = window.location.href.split("?")[0];
}

$('.removeFilter').on('click', function () {
  //console.log("input[value="'+$(this).attr('id')+'"]:checked");
  $("input[value='"+$(this).attr('id')+"']:checked").prop('checked', false);
  submitMainForm();
});

function validatePrice(v) {
  var priceRegex = /^\d*(\u002E\d{0,2})?$/;
  return priceRegex.test(v);
}

$('#priceSubmit').on('click', function () {
  if (validatePrice($('#PriceFrom').val()) && validatePrice($('#PriceTo').val())) {
    if (($('#PriceFrom').val() == '') && ($('#PriceTo').val() == '')) {
      return false;
    } else if (($('#PriceFrom').val() != '') && ($('#PriceTo').val() == '')) {
      $('#PriceTo').attr("disabled", "disabled");
    } else if (($('#PriceFrom').val() == '') && ($('#PriceTo').val() != '')) {
      $('#PriceFrom').val(0);
    }
    $('input[type=radio]:checked').prop('checked', false);
    submitMainForm();
  }
});


//
// $('#priceSubmit').on('click', function () {
//   var v;
//   if (($('#PriceFrom').val() != '') && ($('#PriceTo').val() != '')) {
//     var from = $('#PriceFrom').val();
//     var to = $('#PriceTo').val();
//     v = from+"_"+to;
//   } else if ($('#PriceFrom').val() != '') {
//     var from = $('#PriceFrom').val();
//     v = from;
//   } else if ($('#PriceTo').val() != '') {
//     var to = $('#PriceTo').val();
//     v = "0_"+to;
//   } else {
//
//     return false;
//   }
//   $('#from_to').attr('value', v);
//   $('#from_to').attr('checked', 'checked');
//   submitMainForm();
// });
