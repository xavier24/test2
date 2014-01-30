<?php

$lang['db_invalid_connection_str'] = 'Impossible de déterminer les paramètres de base de données basée sur la chaîne de connexion que vous avez soumis.';
$lang['db_unable_to_connect'] = 'Impossible de se connecter à votre serveur de base de données en utilisant les paramètres fournis.';
$lang['db_unable_to_select'] = 'Impossible de sélectionner la base de données spécifiée: %s';
$lang['db_unable_to_create'] = 'Impossible de créer la base de données spécifiée: %s';
$lang['db_invalid_query'] = 'La requête que vous avez soumis n\'est pas valide.';
$lang['db_must_set_table'] = 'Vous devez définir la table de base de données pour être utilisé avec votre requête.';
$lang['db_must_use_set'] = 'Vous devez utiliser la méthode "set" de mettre à jour une entrée.';
$lang['db_must_use_index'] = 'Vous devez spécifier un index pour correspondre à des mises à jour par lots.';
$lang['db_batch_missing_index'] = 'Un ou plusieurs rangées soumis pour mise à jour batch est absent de l\'index spécifié.';
$lang['db_must_use_where'] = 'Mises à jour ne sont pas autorisés à moins qu\'ils ne contiennent une clause «where».';
$lang['db_del_must_use_where'] = 'Les suppressions ne sont pas autorisés à moins qu\'ils contiennent un "where" ou clause «comme».';
$lang['db_field_param_missing'] = 'Pour récupérer les champs requiert le nom de la table comme paramètre. ';
$lang['db_unsupported_function'] = 'Cette fonction n\'est pas disponible pour la base de données que vous utilisez.';
$lang['db_transaction_failure'] = 'échec de la transaction:. Restauration effectuée';
$lang['db_unable_to_drop'] = 'Impossible de supprimer la base de données spécifiée.';
$lang['db_unsuported_feature'] = 'fonction non prise en charge de la plate-forme de base de données que vous utilisez.';
$lang['db_unsuported_compression'] = 'Le format de compression de fichiers que vous avez choisie n\'est pas supporté par votre serveur.';
$lang['db_filepath_error'] = 'Impossible d\'écrire des données sur le chemin du fichier que vous avez soumis.';
$lang['db_invalid_cache_path'] = 'Le chemin du cache que vous avez soumis n\'est pas valide ou inscriptible.';
$lang['db_table_name_required'] = 'Un nom de table est nécessaire pour cette opération.';
$lang['db_column_name_required'] = 'Un nom de colonne est requis pour cette opération.';
$lang['db_column_definition_required'] = 'Une définition de colonne est requis pour cette opération.';
$lang['db_unable_to_set_charset'] = 'Impossible de définir la connexion client jeu de caractères: %s';
$lang['db_error_heading'] = 'Une erreur de base de données s\'est produit ';

/* End of file db_lang.php */
/* Location: ./system/language/english/db_lang.php */