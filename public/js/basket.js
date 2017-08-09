$("#qty").change(function() {
  $sku = $(".sku").siblings(".sku").attr("id");
  alert($sku);
});

$(".more").click(function() {
  $("#qty").get(0).stepUp();
});

$(".less").click(function() {
  $("#qty").get(0).stepDown();
});
