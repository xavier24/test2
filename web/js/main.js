/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function ( $ ) {
	"use strict";

	// --- global vars
        var baseUrl = location.origin + "/PFE-CodeIgniter",
            $registerForm,
            $loginForm,
            $slideCompte,
            $lang,
            $edit,
            $editPhoto,
            $autreLang,
            $etapes,
            $inputEtape,
            $new_inputEtape,
            $count_inputEtape,
            $moreStep,
            $input_confort,
            $btn_actionOverlay,
            $cancel_action_overlay,
            $confirm_reserv_place;
	// --- methods
    //INITIALISE SELON PAGE
        
        var controlSession = function(){
            $.ajax({
                url:baseUrl+'/ajax/dataSession',
                type:'POST',
                success: function($data){
                var connect;
                    if($.parseJSON($data)){
                        connect = true;
                    }
                    else{
                        connect = false;
                    }
                initialisation(connect);
                }
            });
        };
        
        var initialisation = function(connect){
            if($("body").hasClass('accueil')){ //ACCUEIL
                //recuperer valeur slide conducteur pour ajouter la class
                sliderConducteur();
            }
            if($("body").hasClass('resultat')){ //RESULTAT
                //recuperer valeur slide conducteur pour ajouter la class
                sliderConducteur();
            }
            if($("body").hasClass('ajouter_annonce')){ //AJOUTER ANNONCE
                
                //recuperer valeur slide conducteur pour ajouter la class
                if(connect){
                    sliderConducteur();
                    var $class = $('.choix_prix input:checked').attr('class');
                    $('.result_prix').hide();
                    $('#input_'+$class).show();
                }
                else{
                    $('.nav_compte').find('.slideBlock').slideToggle();
                    $('.nav_compte').toggleClass('ouvert');
                }
            }
            if($("body").hasClass("profil")){//PROFIL
                autreLangTextearea();
            }
        };
        
    //INSCRIPTION  
        var register = function(e){
            e.preventDefault();
            var $email = $('#regist_email').val();
            var $mdp = $('#regist_mdp').val();
            var $mdp2 = $('#mdp2').val();
            var $tel = $('#tel').val();
            var $condition;
            if($('#condition').is(':checked')){
                $condition = 1; 
            }
            else{
                $condition = 0;
            }
            $.ajax({
                url:baseUrl+'/ajax/register',
                type:'POST',
                dataType:"json",
                data: { email:$email, mdp:$mdp, mdp2:$mdp2, tel:$tel, condition:$condition },
               success: function($data){
                   if($data){
                       $('.erreur_inscription').remove();
                       if($data['error_email']){
                           $('#regist_email').after('<p class="erreur_inscription">'+$data['error_email']+'</p>');
                       }
                       if($data['error_exist']){
                           $('#regist_email').after('<p class="erreur_inscription">'+$data['error_exist']+'</p>');
                       }
                       if($data['error_mdp']){
                           $('#regist_mdp').after('<p class="erreur_inscription">'+$data['error_mdp']+'</p>');
                       }
                       if($data['error_mdp2']){
                           $('#mdp2').after('<p class="erreur_inscription">'+$data['error_mdp2']+'</p>');
                       }
                       if($data['error_tel']){
                           $('#tel').after('<p class="erreur_inscription">'+$data['error_tel']+'</p>');
                       }
                       if($data['error_condition']){
                           $('#condition').next().after('<p class="erreur_inscription">'+$data['error_condition']+'</p>');
                       }
                       if($data['id']){
                          window.location = baseUrl+"/user/profil/"+$data['id']; 
                       }
                   }
                   else{
                       location.reload();
                   }
               } 
            });
        }
        
    //MENU    
	var login = function(e){
            e.preventDefault();
            var $email = $('#email').val();
            var $mdp = $('#mdp').val();
            var $souvenir = $('#souvenir').val();
            $.ajax({
               url:baseUrl+'/ajax/login',
               type:'POST',
               dataType: "json",
               data: { email: $email, mdp:$mdp, souvenir:$souvenir },
               success: function($data){
                   if($data){
                       console.log($data);
                       $('#erreur_login').text($data).removeClass("hidden");
                   }
                   else{
                       location.reload();
                   }
               }               
            });
        }
        
        var loginFormSlide = function(){ //slide connexion menu (portable)
            $(this).parent().next().children('.slideBlock').slideToggle();
            $(this).parent().parent().toggleClass('ouvert');
        };//loginForm
    
    //CHANGER LANGUE
        var changeLang = function(e){
            e.preventDefault();
            //console.log($(this).attr("id"));
            $.ajax({
                    url:baseUrl+'/ajax/lang/'+$(this).attr("id"),
                    type:'POST',
                    success: function($data){
                        location.reload();
                    }
            });
        };//changeLang
    
    //INITIALISE SLIDER CHOIX CONDUCTEUR
        var sliderConducteur = function(){
            var $value = $( "#input_conducteur" ).val();
            $(".choix_conducteur"+$value).addClass('select');
            if($value == 0){
                $('.calculateur').hide();
            }
            else{
                $('.calculateur').show();
            }
        };//sliderConducteur

    //PROFIL   
        var editProfil = function(){ //editer profil
            $(this).parent().parent().find('.profil_modif').toggle();
            $(this).parent().parent().find(".edit_hidden").toggle();
            $(this).parent().parent().find(".colorPicker-picker").toggle();
	};//editProfil
        
        var uploadPhoto = function(){ //modifier photo profil
            $('#photo').click();
            $('#photo').on('change',function(){
                $('#upload_photo form').submit();
            });
        };//uploadPhoto
        
        var autreLangTextearea = function(){ // autre langue profil
            if($("#lang_autre_lang").is(':checked')){
                $("#input_autre_lang").show();
            }
            else{
                $("#input_autre_lang").hide();
            }
        };//autreLangTextearea
                
    //AJOUTER ANNONCE
        var addStep = function(){ //ajouter etape annonce
            $count_inputEtape = $etapes.find('.etape');
            if($count_inputEtape.length < 5){
                $new_inputEtape = $inputEtape.clone();
                $new_inputEtape.find('input').val("");
                $new_inputEtape.find('.input_stop').val("1");
                $new_inputEtape.appendTo($etapes);
                $('.nb_etape').empty().append($count_inputEtape.length+1);
                countStep();
            }
        };//addStep
        
        var removeStep = function(){ //supprimer etape annonce
            $count_inputEtape = $etapes.find('.etape');
            if($count_inputEtape.length > 1){
                $(this).parent().parent().remove();
                $('.nb_etape').empty().append($count_inputEtape.length-1);
                countStep();
            }
            else{
                $(this).parent().find('input').val("");
            }
        };//removeStep
        
        var countStep = function(){
            $count_inputEtape = $etapes.find('.etape');
            var i = 0;
            $count_inputEtape.each(function(){
                $(this).find('.input_etape').attr('name','input_etape_'+i);
                $(this).find('.input_etapeID').attr('name','input_etapeID_'+i);
                $(this).find('.input_etape_lat').attr('name','input_etape_lat_'+i);
                $(this).find('.input_etape_lng').attr('name','input_etape_lng_'+i);
                $(this).find('.input_stop').attr('name','input_stop_'+i);
                $(this).find('.input_duree').attr('name','input_duree_'+i);
                i++;
            });
        };//countStep
        
        var btnCheck = function($this,$check){ //bouton checkbox annonce
            if($this.next().is(':checked') && !$check){
                $this.removeClass('bouton_orange').addClass('bouton_gris');
                $this.find('.button').removeClass('orange').addClass('gris');
            }
            else{
                $this.addClass('bouton_orange').removeClass('bouton_gris');
                $this.find('.button').addClass('orange').removeClass('gris');
            }
            
            if($this.next().hasClass('show_calendar')){
                $(".calendar").toggle();
            }
            if($this.next().hasClass('show_retour')){
                $(".table_retour").toggle();
                $(".input_heure_retour").toggleClass('hidden');
                $(".input_date_retour").toggleClass('hidden');
            }
            
        };//btnCheck
        
        var actionOverlay = function(){
            $("#overlay").show();
            $(".actionOverlay").show();
            var $id_annonce = $(this).parent().parent().next().find('.id_annonce').val();
            var $id_reservation = $(this).parent().parent().next().find('.id_reservation').val();
            
            $("#input_id_annonce").val($id_annonce);
            
            if($(this).hasClass('refuser_reservation')){
                $("#input_id_reservation").val($id_reservation);
                $("#input_type_reservation").val('refuser');
            }
            if($(this).hasClass('annuler_reservation')){
                $("#input_id_reservation").val($id_reservation);
                $("#input_type_reservation").val('annuler');
            }
        };//actionOverlay
        
        var cancelActionOverlay = function(){
            $("#overlay").hide();
            $(".actionOverlay").hide();
        };//cancelDeleteAnnonce
              
        var reserverPlace = function(e){
            e.preventDefault();
            var $id_annonce = $("#id_annonce").val();
            var $nb_place = $(".nb_place option:selected").attr('value');
            $.ajax({
                    url:baseUrl+'/annonce/reservation',
                    type:'POST',
                    dataType: "json",
                    data: { id_annonce: $id_annonce, place:$nb_place },
                    success: function($data){
                        if($data){
                            location.reload();
                        }
                    }
            });
        }
        
        var choixPrix = function(){
            var $class = $(this).attr('class');
            $('.result_prix').hide();
            $('#input_'+$class).show();
        }
        
        $( function () {

            // --- onload routines
		$registerForm = $("#inscription");
                $loginForm = $('#login_form');
                $slideCompte = $('.slide_compte');
                $lang = $('.btn_lang');
                $edit = $(".edit");
                $editPhoto = $(".edit_photo");
                $autreLang = $("#lang_autre_lang");
                $input_confort = $("input[name='confort']");
                $etapes = $(".etapes");
                $inputEtape = $etapes.find('.etape').first().clone();
                $moreStep = $("#more_step");
                
                $btn_actionOverlay = $('.btn_actionOverlay');
                $cancel_action_overlay = $(".cancel_action_overlay");
                $confirm_reserv_place = $("#confirm_reserve_place button");
                
            // --- events
                $registerForm.on('submit',register);
                $loginForm.on('submit',login)
                $slideCompte.on('click',loginFormSlide);
                $lang.on('click',changeLang);
                $edit.on("click", editProfil);
                $editPhoto.on('click',uploadPhoto);
                $moreStep.on('click',addStep);
                $autreLang.on('click',autreLangTextearea);
                $input_confort.select(function(){modifConfort()});
                $(document).on('click',".min_step",removeStep);
                
                $btn_actionOverlay.on('click',actionOverlay);
                $cancel_action_overlay.on('click',cancelActionOverlay);
                $confirm_reserv_place.on('click',reserverPlace);
                
                $(".choix_prix input").change(choixPrix);
                
            // --- execute
                controlSession();
                
                if($("#input_retour").is(':checked')){
                   btnCheck($("#input_retour").prev(),"isCheck");
                }
                if($("#input_regulier").is(':checked')){
                   btnCheck($("#input_regulier").prev(),"isCheck");
                }
                $(".btn_check label").on('click',function(){
                    btnCheck($(this))
                });
                
            // --- Appel function externe
                
        } );
        
}( jQuery ) );


// Avoid `console` errors in browsers that lack a console.
/*if (!(window.console && console.log)) {
    (function() {
        var noop = function() {};
        var methods = ['assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error', 'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log', 'markTimeline', 'profile', 'profileEnd', 'markTimeline', 'table', 'time', 'timeEnd', 'timeStamp', 'trace', 'warn'];
        var length = methods.length;
        var console = window.console = {};
        while (length--) {
            console[methods[length]] = noop;
        }
    }());
}*/

// Place any jQuery/helper plugins in here.

