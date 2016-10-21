$(document).ready(function() {

    $('.interieur').removeClass('interieur');

    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#commande_tickets');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement

   // var index = $('div#commande_tickets > div').length;
    //$container.find(':input').length;


    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_ticket').click(function(e) {
        addTicket($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
        var $retest = -1;
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if ((index == 0)&&(testIndex != 1)) {
        addTicket($container);
        addDatePicker();
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container.children('div').each(function() {
            $retest = $retest + 1;
            console.log($(this));
            addDeleteLink($(this));
          //  $("#commande_tickets_"+$retest+"_birth").attr('id','datepicker_'+$retest);
          //  $("#commande_tickets_"+$retest+"_name").before("<span class=\"input-group-addon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span>");
            //$(".input-group-addon").before("<div class=\"input-group col-xs-6\">");
          // $(this).closest('div[class^="input-group"]').append("<div class=\"input-group col-xs-6\"><span class=\"input-group-addon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span>");
            addDatePicker2($retest);
        });
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addTicket($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
                //.replace(/__name__label__/g, "" )
                .replace("<input","<div class=\"input-group col-xs-6\"><span class=\"input-group-addon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span><input")
                .replace(/__name__/g, index)
                .replace("<input type=\"text\" id=\"commande_tickets_"+index+"_surname\"","<div class=\"input-group col-xs-6\"><span class=\"input-group-addon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span><input type=\"text\" id=\"commande_tickets_"+index+"_surname\"")
               .replace("<select id=\"commande_tickets_"+index+"_country\"","<div class=\"input-group col-xs-6\"><span class=\"input-group-addon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span><select id=\"commande_tickets_"+index+"_country\"")
         //   .replace("<select id=\"commande_tickets_"+index+"_birth","<div class=\"input-group col-xs-6\"><span class=\"input-group-addon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span><select id=\"commande_tickets_"+index+"_birth")
            .replace("<input type=\"text\" id=\"commande_tickets_"+index+"_birth","<div class=\"input-group col-xs-6\"><span class=\"input-group-addon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span><input type=\"text\" id=\"datepicker_"+index)
            ;

   // console.log(template);

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
        addDatePicker();
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;

    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {

        // Création du lien
        var $deleteLink = $('<div class="col-sm-offset-8 col-sm-4"><button class="btn btn-danger btn-block delete">' + $delete + '</button></div> ');

            // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function(e) {
            if (($('div#commande_tickets > div').length) > 1) {

                $prototype.remove();
            } else {   $("#supprimer").modal('show');}

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    function addDatePicker() {
       // $(".birthtest").attr('id','datepicker_'+index);

        $("#datepicker_"+index).datepicker({
            startView: 3,
            language: "fr",
            format: "dd/mm/yyyy",
            autoclose: true
        });
    }
    function addDatePicker2() {
    console.log($retest);
        $("#datepicker_"+$retest).datepicker({
            startView: 3,
            language: "fr",
            format: "dd/mm/yyyy",
            autoclose: true
        });
    }



    $("#commande_date").parent().attr('id','datepicker');

 $("#commande_date").hide();

    if (typeof $orderDate != 'undefined') {
        $orderDate = new Date($orderDate);
        var $testDate = true;
    }
    else {
        $orderDate = new Date();
        var $testDate = false;
    }


   /*
   $("#datepicker").datepicker({
        dateFormat: 'dd/mm/yy',
        minDate: 0,
        maxDate: "+2Y",
        firstDay:1,
        defaultDate: $orderDate,
        beforeShowDay: function(date) {
            var day = date.getDay();
            var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
            return [(day != 2 && day != 0 && $joursOff.indexOf(string) == -1) ];
        },
        onSelect: function(dateText, inst) {
            var date = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, dateText, inst.settings);
            var dateText1 = $.datepicker.formatDate("dd/mm/yy", date);
            $("#commande_date").val(dateText1);
            var $today = jQuery.datepicker.formatDate('dd/mm/yy',new Date());
            if (dateText1 == $today) {
            var $heure = new Date().getHours();
            if ($heure >= 14) {
                $('#commande_duree').prop('checked', true);
                $('#commande_duree').attr('disabled', true);
                 }
            }
            else {$('#commande_duree').removeAttr('disabled');
                $('#commande_duree').prop('checked', false);}
        },

    }).attr("readonly","readonly");
    $( "#datepicker" ).datepicker( "option",
        $.datepicker.regional[$lang] );
*/
    $("#datepicker").datepicker({
        format: "dd/mm/yyyy",
        todayBtn: "linked",
        startDate: "today",
        language: $lang,
        daysOfWeekDisabled: "0,2",
        datesDisabled: $joursOff,
    });

    $('#datepicker').on('changeDate', function(event) {
        var date = event.format();
        var $today =  $.datepicker.formatDate('dd/mm/yy', new Date());

        console.log(date);
        console.log($today);
    if (date == $today) {
        var $heure = new Date().getHours();
        if ($heure >= 14) {

            $('#commande_duree').prop('checked', true);
            $('#commande_duree').attr('disabled', true);
        }
    }
        else {
            $('#commande_duree').removeAttr('disabled');
            $('#commande_duree').prop('checked', false);
        }

    });

    $('#commande_date').on("invalid", function(e) {
        $('#datepicker').before("<div id='pbDate' class='col-sm-12 alert alert-danger'>" + $dateRequired + "</div>");
        $('#pbDate').delay(2000).hide("slow");
    });

    $('.ui-state-highlight').removeClass('ui-state-highlight');
    if (!$testDate) {
    $('.ui-state-active').removeClass('ui-state-active');
    $('.ui-state-hover').removeClass('ui-state-hover');
    }


    var $stopAffiche = 0;

    $(document).on('click', '.discount', function(e) {
       if ((e.target.checked) && ($stopAffiche == 0)) {
           $("#infos").modal('show');
       }
    });

    $(document).on('click', '.duree', function(e) {
        if ((e.target.checked)) {
            $("#duree").modal('show');
        }
    });

    $('#stopAffiche').on('click', function() {
        $stopAffiche = 1;
    });

    $('form').submit(function () {
        $('#commande_duree').removeAttr('disabled');
    });

 $('.duree').closest('div[class^="checkbox"]').append('  <i class="fa fa-question-circle" aria-hidden="true"></i>');



});
