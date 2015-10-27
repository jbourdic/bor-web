$(function(){

    /*Gestion de l'affichage des cookies dans le header*/
    var cookieLegal = localStorage.cookieLegal;
    if(cookieLegal == null || cookieLegal == undefined) {
        var $h = $('header');
        $h.prepend($('<a/>',{'class':'cookie-legal btn btn-default','html':'En naviguant sur notre site, vous acceptez que des cookies soient utilisés, cliquez sur ce message pour continuer la navigation.','title':'Cliquez pour fermer la fenêtre et accepter'}).on('click',function(){
            $(this).remove();
            localStorage.setItem('cookieLegal','true');
        }));
    }

    /*Gestion des bouttons actifs, ajout / Suppréssion de la classe active au click*/
    $('button.active-menu').click(function(){
        var $p = $(this).parent();
        $p.hasClass('active') ? $p.removeClass('active') : $p.addClass('active');
    });

    /*Open popup*/
    var $openPopup = $('[data-toggle="open-popup"]');
    if($openPopup.length > 0) {
        $openPopup.click(function(){
            var t = $(this).data('target');
            var $t = $(''+t);
            $t.hasClass('active') ? $t.removeClass('active') : $t.addClass('active');
        });
    }
    var $closePopup = $('[data-toggle="close-popup"]');
    if($closePopup.length > 0){
        $closePopup.click(function(){
            var t = $(this).data('target');
            var $t = $(''+t);
            $t.removeClass('active');

        });
    }
    /*Alert box*/
    var $alerts = $('.alert');
    if($alerts.length > 0){
        $('button',$alerts).on('click',function(){
            $(this).parent().remove();
        });
    }
    //-------------------------- DEBUT - GESTION  DES FAVORIS ----------------------------
    //Gestion favoris rework
    var $favs = $('.fav'); // Favoris sur la page

    //URL AJOUT FAVORIS CAPSLOCKK /favorites var idAdvert
    if($favs.length > 0){
        var isConnected = $('#isCo').val() == 1;
        var path = $('#pth').val();

        var removeFav = function(ct){
            if(isConnected){
                ct.removeClass('active');
                $.ajax({
                   type:"POST",
                    url:path+"delete",
                    data:{'idAdvert': ct.data('id')},
                    success: function(data) {
                    },
                    error: function() {
                        ct.addClass('active');
                    }
                });
            }else {
                localStorage.removeItem(ct.data('id'));
                ct.removeClass('active');
            }

        };
        var addFav = function(ct){
            if(isConnected){
                ct.addClass('active');
                $.ajax({
                    type:"POST",
                    url:path,
                    data:{'idAdvert': ct.data('id')},
                    success: function(data) {

                    },
                    error: function() {
                        ct.removeClass('active');

                    }
                });
            }else {
                localStorage.setItem(ct.data('id'),'active');
                ct.addClass('active');
            }
        };
        if(!isConnected){
            $favs.each(function(){
                var favId = $(this).data('id');
                var status = localStorage.getItem(favId); //récupération de l'item

                if(status){$(this).addClass('active');} //Ajout de la classe active
            });
        }
        $favs.on('click',function(){
           if($(this).hasClass('active')){
               removeFav($(this));
           } else {
                addFav($(this));
           }
        });
    }

    //--------------------------FIN - GESTION  DES FAVORIS ----------------------------


    //-------------------DEBUT - Changement d'onglet dans la page creation_article.html----------------
    // Passge de l'onglet Article à Référencement au clic qur référencement
    $("#bouton-referencement").click( function() {
        $("#BlockFormAddArticle").css( "display", "none" );
        $("#BlockFormAddReferencement").css( "display", "inherit" );
    });
    // Passge de l'onglet Référencement à Article au clic qur Article
    $("#bouton-article2").click( function() {
        $("#BlockFormAddReferencement").css( "display", "none" );
        $("#BlockFormAddArticle").css( "display", "inherit" );
    });

    //bouton déroulement
    $("#bouton-deroulement").click( function() {
        $("#div-bouton-deroulement").toggle();
    });
    //-------------------FIN - Changement d'onglet dans la page creation_article.html----------------


    // ----------------------------- DEBUT - POPUP -----------------------------
    var popup=0;

    // -- Popup1 --
    $(".open-popup1").click(function(){
        if (popup==0) {
            popup=1;
            $("body").append("<div class='popup1'><div class='close-popup'>X</div><h1>Rédiger un commentaire sur cet article</h1><hr><div class='popup-left'><h2>Note* :</h2><h2>Commentaires* :</h2><p>10/240</p></div><div class='popup-right'><ul><li></li><li></li><li></li><li></li><li></li></ul><form> <textarea placeholder='240 Caractères' maxlength='240'></textarea><input type='hidden' class='nb-star' name='Etoiles commentaire' value='0'><input type='submit' value='Poster un commentaire'><span></span></form></div>");
            $(".popup1").fadeIn(400);
            // Appel de la fonction commentStars()
            commentStars();
            $(".close-popup").click(function(){
                popup=0;
                $(".popup1").fadeOut(400, function(){$(this).remove();});
            });
        }
    });
    
    function commentStars(){
        var testStar=0;
        $(".popup1 ul li").mouseenter(function(){
            var index=$(this).index();
            var star=0;
            for(star=0;star<=5;star++){
                if(star<=index){
                    $(".popup1 ul li:eq(" + star + ")").css('background-position','0px -19px');
                }else {
                    $(".popup1 ul li:eq(" + star + ")").css('background-position','0px 0px');
                }
            }
            
            $(".popup1 ul li").click(function(){
                var index=$(this).index();
                var star=0;
                var testStar=1;
                for(star=0;star<=5;star++){
                    if(star<=index) {
                        $(".popup1 ul li:eq(" + star + ")").addClass("star-active");
                    }else{
                        $(".popup1 ul li:eq(" + star + ")").removeClass("star-active");
                    }
                }
                $("input.nb-star").attr('value',index+1);
            });
        });
        $(".popup1 ul").mouseleave(function(){
            $(".popup1 ul li").css('background-position','0px 0px');
            $(".popup1 ul li.star-active").css('background-position','0px -19px');
        });
    }    
    
    
    // -- Popup2 --
    $(".open-popup2").click(function(){
        if (popup==0) {
            popup=1;
            $("body").append("<div class='popup2'><div class='close-popup'>X</div><h1>Déclarer cette annonce</h1><hr><div class='popup-left'><h2>Message* :</h2><p>10/240</p></div><div class='popup-right'><select name='Search filtrer'><option value=''>Motif de cet abus</option><option value=''>Produit déja vendu</option><option value=''>Contact Injoignable</option></select><form><textarea placeholder='240 Caractères' maxlength='240'></textarea><input type='submit' value='Signaler un abus'><span></span></form></div>");
            $(".popup2").fadeIn(400);
            $(".close-popup").click(function(){
                popup=0;
                $(".popup2").fadeOut(400, function(){$(this).remove();});
            });
        }
    });

    // Popup disable user account
    $(".disable-profil").click(function(){
        if (popup==0) {
            popup=1;
            $(".popup1").fadeIn(400);
            $(".close-popup, .cancel").click(function(){
                popup=0;
                $(".popup1").fadeOut(400);
            });
        }
    });
    // ----------------------------- FIN - POPUP -----------------------------
    


    //-------------------DEBUT - VALIDATION DES FORMULAIRES----------------
    //Script valable sur tous les formulaires du site !!!
  /*  $("form").submit( function(event) {
        // On initialise une variable à true, si au cours des tests la variables passe à false
        // --> le formulaire n'est pas envoyé !
        var valid = true;
        // On récupère ici les balises du formulaire
        var inputText = $(this).find("input[type='text'][required]");
        var inputMail = $(this).find("input[type='mail'][required]");
        var inputPassword = $(this).find("input[type='password'][required]");
        var inputCheckbox = $(this).find("input[type='checkbox'][required]");
        var inputTextarea = $(this).find("textarea[required]");
        var inputSelect = $(this).find("select[required]");

        //On test que chaque champ text soit remplit
        inputText.each(function(){
            var inputTextLength = $(this).val().length;
            if(inputTextLength <= 0){
                valid = false;
            }
        });
        //On test que chaque champ mail soit remplit et conforme au format mail
        inputMail.each(function(key){
            var inputMailLength = $(this).val().length;
            var inputMailVal = $(this).val();
            if((inputMailLength <= 0) || (!validateEmail(inputMailVal) )){
                valid = false;
            }
            if(key == 1){
                var mail0 = inputMail[0].value;
                var mail1 = inputMail[1].value;
                if(mail0 != mail1){
                    valid = false;
                }
            }
        });
        //On test que chaque champ password soit supérieure à 7 caractere (8 minimum)
        inputPassword.each(function(key){
            var inputPasswordLength = $(this).val().length;
            if(inputPasswordLength <= 0){
                valid = false;
            }
            if(key == 1){
                var password0 = inputPassword[0].value;
                var password1 = inputPassword[1].value;
                if(password0 != password1){
                    valid = false;
                }
            }
        });
        //On test que chaque checkbox obligatoire soit cochée
        inputCheckbox.each(function(){
            if(this.is(':checked')){

            }else{
                valid = false;
            }
        });
        //On test que chaque textarea soit remplit
        inputTextarea.each(function(){
            var inputTextareaLength = $(this).val().length;
            if(inputTextareaLength <= 0){
                valid = false;
            }
        });
        //On test que chaque champ select soit choisi
        inputSelect.each(function(){
            var inputSelectVal = $(this).val();
            if (inputSelectVal == 0) {
                valid = false;
            }
        });

        // Si valid = false --> On envoie pas le formulaire
        if(valid == false){
            $(".Form").parent().find("p").hide();
            $(".Form").parent().append("<p>Tous les champs n'ont pas été remplis !</p>");
            event.preventDefault();
        }
    });*/
    //-------------------FIN - VALIDATION DES FORMULAIRES----------------

    //------------------DEBUT - PAGE MULTIMEDIA---------------
    // Affichage de la corbeille au hover
    $(".search-result-bottom-hover").css( "display", "none" );
    $(".search-result").hover( function(){
        $(this).find(".search-result-bottom-hover").css( "display", "inherit" );
    },function(){
        $(this).find(".search-result-bottom-hover").css( "display", "none" );
    });

    // Edit
    $(".edit-search-result").click(function(){
        $(".bouton_suppr").toggle(function(){
            $(this).parent().parent().hover(function () {
                $(this).find(".search-result-bottom-hover").css( "display", "none" );
            });
        });
    });
    //-------------------FIN - PAGE MULTIMEDIA----------------

    /*GESTION DES MODAL-MSG*/
    $('.modal-msg .modal-close').click(function(){$(this).parent().fadeOut().remove();});

    //Gestion des coverflow
    var $coverflow = $('#coverflow');
    if($coverflow.length > 0) {
        $coverflow.coverflow();
    }

    //-------------------Debut - PAGE CONSULTATION ARTICLE----------------
    //A ajouter avec le bloc multimedia
    $(".liste-vignette").click(
        function(){
            $("#vignette > img").attr("src", $(this).data("img"));
        });
    $("#bouton-avis").click(
        function(){
            $(".contenu-onglet-1").hide();
            $(".contenu-onglet-2").show();
            $("#bouton-avis").addClass("active");
            $("#bouton-infos").removeClass("active");
            $("#bouton-avis").removeClass("inactive");
            $("#bouton-infos").addClass("inactive");
        });

    $("#bouton-infos").click(
        function(){
            $(".contenu-onglet-2").hide();
            $(".contenu-onglet-1").show();
            $("#bouton-infos").addClass("active");
            $("#bouton-avis").removeClass("active");
            $("#bouton-infos").removeClass("inactive");
            $("#bouton-avis").addClass("inactive");
});
    //-------------------FIN - PAGE CONSULTATION ARTICLE----------------


    //-------------------DEBUT - GESTION GALERIE MULTIMEDIA----------------

    var $mediaSelector = $('.media-selector');
    if($mediaSelector.length){
        $mediaSelector.each(function(){
            var ct = this;
            //Affichage de l'input en mode selectionné
            var $inputHidden = $('input[type="file"].visuallyhidden',$(this));
            if($inputHidden.length > 0){
                $inputHidden.on('change',function(){
                    $(this).val().length > 0 ? $(this).parent().addClass('has-element') : $(this).parent().removeClass('has-element')
                });
            }
            //Affichage de la galerie
            var $galleryLink = $('.rollover-photo,.rollover-photo-little',$(this));
            if($galleryLink.length > 0){
                $galleryLink.click(function(e){
                    e.preventDefault();
                    $(ct).addClass('gallery-open');
                });
            }
            //affichage de l'input galerie en mode selectionne
            var radioName = $('input:radio',$(this)).attr('name');
            var radioGroup = $('input[name="'+radioName+'"]');
            radioGroup.each(function(){
                if($(this).val().length > 0 && $(this).prop('checked')){
                    $galleryLink.addClass('active');
                    return;
                }
            });
            radioGroup.on('change',function(){
                $(this).val().length > 0 ? $galleryLink.addClass('active') : $galleryLink.removeClass('active');
            });
            //Fermeture du multimédia
            var $iconClose = $('.icon-close',$(this));
            if($iconClose.length > 0){
                $iconClose.click(function(){
                    $(ct).removeClass('gallery-open');
                });
            }
        });

    }
    //-------------------FIN - GESTION GALERIE MULTIMEDIA----------------


    //-------------------DEBUT - COLLAPSE----------------
    var $collapse = $('[data-toggle="collapse"]');
    if($collapse.length > 0){
        $collapse.each(function(){
            var $t = $($(this).data('target'));
               if($t.hasClass('in')){
                   var newHeight = 0;
                   $t.children().each(function(){
                       newHeight += $(this).innerHeight();
                   });
                   $t.css('height',newHeight);
               }
        });
        $collapse.click(function(){
            var $t = $($(this).data('target'));
            if($t.hasClass('collapsing')){return}
            var newHeight = 0;
            var dir;
            if($t.hasClass('in')){
                dir = "out";
                setTimeout(function(){
                    $t.attr('class','collapse');
                },350);
            }else {
                dir = "in";
                $t.children().each(function(){
                    newHeight = $(this).innerHeight();
                });
                setTimeout(function(){
                    $t.attr('class','collapse in');
                },350);
            }
            $t.attr({class:"collapsing"}).css({height:function(){
                if(dir == "in"){
                    $t.children().each(function(){
                        newHeight = $(this).innerHeight();
                    });
                    return newHeight;
                }else {
                    return newHeight
                }
            }});

        });
    }


});

//------------------------------FONCTIONS------------------------------------//

// Fonction de validation des adresses mail en jQuery
function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if( !emailReg.test( $email ) ) {
        return false;
    } else {
        return true;
    }
}
/**
 * Gère l'ouverture des popup pour les réseaux sociaux
 * */
function ouvre(fichier) {
    ff=window.open(fichier,"popup",
        "width=700,height=500,left=30,top=20")
}
