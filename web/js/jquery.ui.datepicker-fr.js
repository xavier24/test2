/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Keith Wood (kbwood{at}iinet.com.au),
			  Stéphane Nahmani (sholby@sholby.net),
			  Stéphane Raimbault <stephane.raimbault@gmail.com> */
jQuery(function($){
	$.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
		'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		monthNamesShort: ['Janv.','Févr.','Mars','Avril','Mai','Juin',
		'Juil.','Août','Sept.','Oct.','Nov.','Déc.'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],
		dayNamesMin: ['D','L','M','M','J','V','S'],
		weekHeader: 'Sem.',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
        
        $.timepicker.regional['fr'] = {
		currentText: 'Maintenant',
                closeText: 'Valider',
                timeOnlyTitle: 'Choisissez l\'heure',
                timeText: 'Temps',
                hourText: 'Heure',
                minuteText: 'Minute'};
        
        $.datepicker.regional['nl'] = {
		closeText: 'Sluiten',
		prevText: 'Vorig',
		nextText: 'Volgend',
		currentText: 'Vandaag',
		monthNames: ['Januari','Februari','Maart','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'],
		monthNamesShort: ['Janu.','Febr.','Maart','April','Mei','Juni',
		'Juli','Aug.','Sept.','Okt.','Nov.','Dec.'],
		dayNames: ['Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag'],
		dayNamesShort: ['Zon.','Maa.','Din.','Woe.','Don.','Vri.','Zat.'],
		dayNamesMin: ['Z','M','D','W','D','V','Z'],
		weekHeader: 'Week',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
            
        $.timepicker.regional['nl'] = {
		currentText: 'Nu',
                closeText: 'Voorleggen',
                timeOnlyTitle: 'Kies de tijd',
                timeText: 'Tijd',
                hourText: 'Uur',
                minuteText: 'Minuut'};
            
	//$.datepicker.setDefaults($.datepicker.regional['fr']);
});
