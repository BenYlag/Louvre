$(document).ready(function() {

     $(".birthtest").attr('id','datepicker2');

     $("#datepicker2").datepicker({
          startView: 3,
          language: "fr",
          format: "dd/mm/yyyy",
          autoclose: true
     });


});
