$(document).ready(function() {
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#commande_tickets');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_ticket').click(function(e) {
        addTicket($container);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addTicket($container);
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addTicket($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
                .replace(/__name__label__/g, (index+1))
                .replace(/__name__/g,        index)
            ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {

        // Création du lien
        var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function(e) {

            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
    $("form input.date").parent().attr('id','datepicker');
    $("form input.date").hide();
    if (typeof $orderDate != 'undefined') {
        $orderDate = new Date($orderDate);
    }
    else {
        $orderDate = new Date();
    }
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
            $("form input.date").val(dateText1);
        },
    }).attr("readonly","readonly");
    $( "#datepicker" ).datepicker( "option",
        $.datepicker.regional[$lang] );
});
