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

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if ((index == 0)&&(testIndex != 1)) {
        addTicket($container);
        var $deleteBouton = 0;
        //deleteButton();
    }
    else {
        var $deleteBouton = 1;
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container.children('div').each(function() {
           // console.log(index);
            addDeleteLink($(this));

            customTicket(index-2);
            index ++;
        });
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addTicket($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
                //.replace(/__name__label__/g, "" )
                .replace(/__name__/g, index)
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

        customTicket("_name");
        customTicket("_surname");
      //  $('.discount').closest('div[class^="checkbox"]').append('  <i class="fa fa-question-circle" aria-hidden="true"></i>');

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
            }
            else {


                $("#supprimer").modal('show');
            }

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    function customTicket($var) {
        $leChamp = $('input[id*=\"'+ $var + '\"]');

        $leChamp = $leChamp.prev();
        $leChamp.children(".fa-envelope").removeClass('fa-envelope').addClass('fa-user');

    }
    function addDatePicker() {

        $varAcces = $(".birthtest").attr('id','datepicker_'+index);
        $varAcces.datepicker({

               language: $lang,
               format: "dd/mm/yyyy",
                startDate: '01/01/1900',
                endDate: '-1d',
            startView: 3,
                autoclose: true,
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

    $("#datepicker").datepicker({
        format: "dd/mm/yyyy",
        startDate: "today",
        endDate: "+24m",
        language: $lang,
        daysOfWeekDisabled: "0,2",
        datesDisabled: $joursOff,
    });

    $('#datepicker').on('changeDate', function(event) {
        var date = event.format();
        var $today =  $.datepicker.formatDate('dd/mm/yy', new Date());

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
        if (!$('#pbDate').length) {


        $('#datepicker').parent().append("<div id='pbDate' class='col-sm-12 alert alert-danger'>" + $dateRequired + "</div>");
        $('#pbDate').delay(2000).hide("slow");
        }
        else {
            $('#pbDate').show("slow");
            $('#pbDate').delay(2000).hide("slow");
        }
    });

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

    $('#datepicker > span').remove();


   // console.log($('#commande_tickets > *').length);

    $test = $('.delete').length;
    if ($test == 1) {
        $('.delete').remove();
    }
   // console.log($test);

    //console.log($('#commande_tickets > *').length);
    console.log( $deleteBouton);
    $('#commande_tickets').bind("DOMSubtreeModified",function() {
      //  console.log($('#commande_tickets > *').length);
        $test = $('.delete').length;
        if (($deleteBouton == 1) &&($test == 1) && (index!=2)) {
            $('.delete').remove();
            $deleteBouton = 0;
        }

    });


});