<?php
//Fichier contenant tous les textes du site

$lang_site = array(

/******** Common *********/
	//Spécifiques à la langue
	'Lang' => 'fr',
	'Changelang short' => 'en',
	'Changelang long' => 'English',
//	'ID news' => '100',

	//Header et footer
	'Skip link' => 'Aller au contenu',
	'Head desc' => 'Ressources et services d’aide graphique 100% gratuits pour vos forums sites blogs',
//	'Site name' => 'Ishimaru Design',
	'Site desc' => 'Ressources et services gratuits pour vos forums, sites, blogs',
	'Footer content' => '%s ©'.date('Y').', tous droits réservés - Les ressources et tutoriels publiés sur ce site sont la propriété de leurs auteurs respectifs | <a href="http://validator.w3.org/check?uri=referer">XHTML 1.0</a>',
	'Goto top' => 'Revenir en haut',
	'Footer site' => 'Le site',
	'Footer affiliates' => 'Partenaires',

	//Clés communes - En ordre alpha pour éviter les doublons
	'Actions' => 'Actions',
	'Add' => 'Ajouter',
	'Add subcat' => 'Ajouter une sous-catégorie',
	'Add version' => 'Ajouter une version',
	'Admin' => 'Administration',
	'Advanced' => 'Avancé',
	'All' => 'Tous',
	'Approve' => 'Approuver',
	'Archives' => 'Archives',
	'Ascending' => 'Ascendant',
	'Author' => 'Auteur',
	'Author notes' => 'Notes de l’auteur',
	'Autologin' => 'Connexion auto',
	'BBCode' => 'BBCode :',
	'By' => 'Par',
	'by' => ' par ',
	'Categories' => 'Catégories',
	'Category' => 'Catégorie',
	'comments' => 'commentaires',
	'Comments' => 'Commentaires',
	'Comments moderation' => 'Panneau de modération des commentaires',
	'Comment preview' => 'Aperçu du commentaire',
	'Delete' => 'Supprimer',
	'Descending' => 'Descendant',
	'Description' => 'Description',
	'Download' => 'Télécharger le ',
	'Edit' => 'Modifier',
	'English' => 'English',
	'Follow us' => 'Nous suivre',
	'Follow on' => 'Nous suivre sur ',
	'Follow Facebook' => 'Nous suivre sur Facebook',
	'Follow GooglePlus' => 'Nous suivre sur Google+',
	'Follow Twitter' => 'Nous suivre sur Twitter',
	'Forum' => 'Forum',
	'French' => 'Français',
	'General' => 'Général',
	'Go back' => 'Retour',
	'Google' => 'Publicité',
	'Hack' => 'Hack',
	'Hacks' => 'Hacks',
	'Has versions' => 'Utiliser les versions',
	'Has versions info' => 'Utile pour les tutoriels de logiciels (ex: Gimp)',
	'Home' => 'Accueil',
	'Info' => 'Informations',
	'Intermediate' => 'Intermédiaire',
	'img tag' => 'Balise [img] :',
	'Language' => 'Langue',
	'Languages' => 'Langues',
	'Last posts' => 'Derniers messages',
	'Last posts by' => 'Par',
	'Last update' => 'Dernière MAJ',
	'Level' => 'Niveau',
	'Log in' => 'Connexion',
	'Log out' => 'Déconnexion',
	'Message' => 'Message',
	'My topics' => 'Mes interventions',
	'Name' => 'Nom',
	'Newbie' => 'Débutant',
	'News' => 'News',
	'Next' => 'Suivant',
	'No' => 'Non',
	'off' => 'Désactivé',
	'OK' => 'OK',
	'on' => 'Activé',
	'Order' => 'Ordre',
	'Overview' => 'Aperçu',
	'Pages' => 'Pages',
	'Password' => 'Mot de passe',
	'PMs' => 'Messagerie',
	'Position' => 'Position',
	'Post comment' => 'Publier un commentaire',
	'Preview' => 'Aperçu du ',
	'Preview post' => 'Prévisualiser',
	'Preview style' => 'Aperçu du style',
	'Previous' => 'Précédent',
	'Profile' => 'Mon profil',
	'Published' => 'Publié le ',
	'Quote' => 'Citer',
	'Read tutorial' => 'Lire le tutoriel',
	'Register' => 'S’enregistrer',
	'Resource' => 'Resource',
	'Resources' => 'Ressources',
	'Save changes' => 'Enregistrer',
	'Screenshot' => 'Capture d’écran',
	'Screenshots' => 'Captures d’écran',
	'Set as default' => 'Par défaut',
	'Settings' => 'Configuration',
	'Show all' => 'Tout afficher',
	'Smilies' => 'Émoticônes :',
	'Sort' => 'Trier',
	'Sort by' => 'Trier par',
	'Sort tutorials' => 'Trier les tutoriels',
	'Styles' => 'Styles',
	'Style' => 'Style',
	'Subcategories' => 'Sous-catégorie',
	'Submit' => 'Envoyer',
	'Summary' => 'Sommaire',
	'Tutorial' => 'Tutoriel',
	'Tutorials' => 'Tutoriels',
	'Tutorial icon' => 'Icone du tutoriel',
	'Tutorial ID' => 'ID du tuto',
	'Type' => 'Type',
	'Types' => 'Types',
	'Unapprove' => 'Désapprouver',
	'Update positions' => 'Modifier les positions',
	'Updated' => 'Modifié le ',
	'URL' => 'URL',
	'Use right click' => 'Utilisez le clic-droit pour récupérer l’adresse du lien.',
	'Versions' => 'Versions',
	'Username' => 'Nom d’utilisateur',
	'Version' => 'Version',
	'View details' => 'Voir les détails',
	'View info' => 'Voir les informations',
	'View list' => 'Voir la liste des ',
	'View tutorials' => 'Voir les tutoriels',
	'wrote' => 'a écrit',
	'Yes' => 'Oui',

	//Redirection
	'Redirecting' => 'Redirection',
	'Click redirect' => 'Cliquez ici si vous ne voulez pas attendre (ou si votre navigateur ne vous redirige pas automatiquement).',

	//Pour la page d'accueil v6
	'About site' => ' c’est…',
/*	'About resources' => 'Des ressources',
	'About resources explain' => 'Des styles, des MODs et des hacks en libre téléchargement pour vos forums phpBB2, phpBB3, Forumactif et Connectix Boards, ainsi que quelques petits extras.',
	'About tutorials' => 'Des tutoriels',
	'About tutorials explain' => 'De la documentation pour personnaliser l’aspect graphique de votre forum, que ce soit la modification des templates ou la réalisation d’éléments graphiques avec Gimp.',
	'About community' => 'Une communauté',
	'About community explain' => 'Des bénévoles prêts à répondre à vos questions relatives à la personnalisation de votre forum et aux problèmes relatifs à l’utilisation d’une de nos ressources.',*/
	'Latest news' => 'Dernières news',
	'Latest tutorials' => 'Derniers tutoriels',
	'Latest styles' => 'Derniers styles',

	//Spécifique aux commentaires
	'Add comment' => 'Ajouter un commentaire',
	'Edit comment' => 'Modifier un commentaire',
	'Delete comment' => 'Supprimer un commentaire',
	'Delete comment head' => 'Supprimer le commentaire',
	'Confirm delete comment subhead' => 'Confirmer la suppression du commentaire',
	'Confirm delete comment info' => 'Souhaitez-vous vraiment supprimer le commentaire suivant ?<br /><q>%s</q>',
	'Delete comment warn' => '<strong>ATTENTION !</strong> La suppression sera irréversible !',
	'Comment errors' => 'Erreurs dans le commentaire',
	'Comment errors info' => 'Les erreurs suivantes doivent être corrigées pour que le commentaire puisse être envoyé :',
	'Comment preview' => 'Prévisualisation du commentaire',
	'Comment added redirect' => 'Commentaire ajouté. Redirection …',
	'Comment edited redirect' => 'Commentaire édité. Redirection …',
	'Comment deleted redirect' => 'Commentaire supprimé. Rediraction …',

	//Administration - Général
	'Admin heading' => 'Administration du site',
	'Admin manage settings' => 'Configuration générale',
	'Admin manage pages' => 'Gérer les pages',
	'Admin manage res' => 'Gérer les ressources',
	'Admin manage tuts' => 'Gérer les tutoriels',
	'Admin stats' => 'Statistiques du site',
	'Admin nb res' => 'Nombre de ressources',
	'Admin nb tutorials' => 'Nombre de tutoriels',
	'Admin nb comments' => 'Nombre de commentaires',
	'Admin nb pages' => 'Nombre de pages',

	//Config générale
	'Admin general settings' => 'Options générales',
	'Admin cfg site desc' => 'Description du site',
	'Admin intro module settings' => 'Options du module de présentation',
	'Admin cfg enable intro' => 'Activer le module de présentation',
	'Admin cfg site intro' => 'Contenu de la présentation',
	'Admin news module settings' => 'Options du module de news',
	'Admin cfg enable news' => 'Activer le module de news',
	'Admin cfg forum news' => 'Forum des news',
	'Admin cfg nb news home' => 'Nombre de news à afficher sur l’accueil',
	'Admin cfg nb news page' => 'Nombre de news à afficher sur la page des news',
	'Admin resources module settings' => 'Options du module des ressources',
	'Admin cfg enable res' => 'Activer le module des ressources',
	'Admin cfg nb res home' => 'Nombre de ressources à afficher sur l’accueil',
	'Admin cfg styles per page' => 'Styles par page',
	'Multiple 2' => 'Doit être un multiple de 2',
	'Admin cfg hacks per page' => 'Hacks par page',
	'Multiple 3' => 'Doit être un multiple de 3',
	'Admin tutorials module settings' => 'Options du module des tutorials',
	'Admin cfg enable tuts' => 'Activer le module des tutoriels',
	'Admin cfg nb tuts home' => 'Nombre de tutoriels à afficher sur l’accueil',
	'Admin cfg tuts per page' => 'Tutoriels par page',
	'Admin sidebar modules settings' => 'Options de la sidebar',
	'Admin cfg enable lastposts' => 'Activer le module des dernières réponses',
	'Admin cfg nb lastposts' => 'Nombre de réponses à afficher',
	'Admin cfg enable ads' => 'Activer le module des publicités',
	'Admin cfg your ads' => 'Votre publicité',
	'Admin cfg enable social' => 'Activer le module des liens sociaux',
	'Admin cfg social links' => 'Liens sociaux',
	'Admin footer settings' => 'Options du footer',
	'Admin cfg enable footer links' => 'Activer les liens du footer',
	'Admin cfg footer sitelinks' => 'Liens du site',
	'Admin cfg footer affiliates' => 'Liens de partenariats',

	'Options updated redirect' => 'Options mises à jour. Redirection …',

	//Administrations - Catégories
	'Admin add cat' => 'Ajouter une catégorie',
	'Admin add subcat' => 'Ajouter une sous-catégorie',
	'Admin add version' => 'Ajouter une version',
	'Admin edit cat' => 'Modifier une catégorie',
	'Admin edit subcat' => 'Modifier une sous-catégorie',
	'Admin edit version' => 'Modifier une version',
	'Admin delete category' => 'Supprimer une catégorie',
	'Admin delete subcat' => 'Supprimer une sous-catégorie',
	'Admin delete version' => 'Supprimer une version',

	'Admin cat name' => 'Nom de la catégorie',
	'Admin sub name' => 'Nom de la sous-catégorie',
	'Admin ver name' => 'Nom de le version',
	'Admin cat clear' => 'Non court de la catégorie',
	'Admin sub clear' => 'Nom court de la sous-catégorie',
	'Admin cat desc' => 'Description de la catégorie',
	'Admin sub desc' => 'Description de la sous-catégorie',
	'Admin cat icon' => 'Icône de la catégorie',

	'Admin cat added redirect' => 'Catégorie ajoutée. Redirection …',
	'Admin subcat added redirect' => 'Sous-catégorie ajoutée. Redirection …',
	'Admin version added redirect' => 'Version ajoutée. Redirection …',
	'Admin cat edited redirect' => 'Catégorie modifiée. Redirection …',
	'Admin subcat edited redirect' => 'Sous-catégorie modifiée. Redirection …',
	'Admin version edited redirect' => 'Version modifiée. Redirection …',
	'Admin cat deleted redirect' => 'Catégorie supprimée. Redirection …',
	'Admin subcat deleted redirect' => 'Sous-catégorie modifiée. Redirection …',
	'Admin version deleted redirect' => 'Version supprimée. Redirection …',

	'Admin delete res cat head' => 'Supprimer la catégorie (et son contenu)',
	'Admin delete res subcat head' => 'Supprimer la sous-catégorie (et son contenu)',
	'Admin delete tut cat head' => 'Supprimer la catégorie (et son contenu)',
	'Admin delete version head' => 'Supprimer la version (et son contenu)',
	'Admin confirm delete cat subhead' => 'Confirmer la suppression de la catégorie',
	'Admin confirm delete subcat subhead' => 'Confirmer la suppression de la sous-catégorie',
	'Admin confirm delete version subhead' => 'Confirmer la suppression de la version',
	'Admin confirm delete cat info' => 'Souhaitez-vous vraiment supprimer la catégorie « %s » ?',
	'Admin confirm delete subcat info' => 'Souhaitez-vous vraiment supprimer la catégorie « %s » ?',
	'Admin confirm delete version info' => 'Souhaitez-vous vraiment supprimer la version « %s » ?',
	'Admin delete res cat warn' => '<strong>ATTENTION !</strong> Supprimer une catégorie entraîne la suppression des sous-catégories et ressources qu’elle contient !',
	'Admin delete res subcat warn' => '<strong>ATTENTION !</strong> Supprimer une sous-catégorie entraîne la suppression des ressources qu’elle contient !',
	'Admin delete tut cat warn' => '<strong>ATTENTION !</strong> Supprimer une catégorie entraîne la suppression des tutoriels et versions qu’elle contient !',
	'Admin delete version warn' => '<strong>ATTENTION !</strong> Supprimer une version entraîne la suppression des tutoriels qu’elle contien !',

	//Administration - Ressources
	'Admin add res' => 'Ajouter une ressource',
	'Admin edit res' => 'Mettre à jour une ressource',
	'Admin delete res' => 'Supprimer une ressource',

	'Admin res name' => 'Nom de la ressource',
	'Admin res shortdesc' => 'Courte description',
	'Admin res desc' => 'Description longue',
	'Admin res notes' => 'Notes de l’auteur',

	'Admin res added redirect' => 'Ressource ajoutée. Redirection …',
	'Admin res edited redirect' => 'Ressource mise à jour. Redirection …',
	'Admin res approved redirect' => 'Ressource approuvée. Redirection …',
	'Admin res unapproved redirect' => 'Ressource désapprouvée. Redirection …',
	'Admin res deleted redirect' => 'Capture supprimée. Redirection …',

	'Admin delete res head' => 'Supprimer la ressource (et ses captures d’écran)',
	'Admin confirm delete res subhead' => 'Confirmer la suppression de la ressource',
	'Admin confirm delete res info' => 'Souhaitez-vous vraiment supprimer la ressource suivante ?<br /><q>%s</q>',
	'Admin delete res warn' => '<strong>ATTENTION !</strong> Supprimer une ressource entraîne la suppression des captures d’écran qui y sont associées !',

	//Administration - captures d'écran
	'Admin add screen' => 'Ajouter une capture d’écran',
	'Admin edit screen' => 'Mettre à jour une capture d’écran',
	'Admin delete screen' => 'Supprimer une capture d’écran',

	'Admin legend english' => 'Légende en anglais',
	'Admin legend french' => 'Légende en français',
	'Admin download link' => 'Lien de téléchargement',
	'Admin resource screenshots' => 'Captures d’écran',

	'Admin screenshot added redirect' => 'Capture d’écran ajoutée. Redirection …',
	'Admin screenshot updated redirect' => 'Capture mise à jour. Redirection …',
	'Admin screenshot deleted redirect' => 'Capture supprimée. Redirection …',
	'Admin default screen changed redirect' => 'Capture par défaut changée. Redirection …',

	'Admin delete screen head' => 'Supprimer la capture d’écran',
	'Admin confirm delete screen subhead' => 'Confirmer la suppression de la capture d’écran',
	'Admin confirm delete screen info' => 'Souhaitez-vous vraiment supprimer la capture d’écran suivante ?<br /><q>%s</q>',
	'Admin delete screen warn' => '<strong>ATTENTION !</strong> La suppression sera irréversible !',

	//Administration - Types de tutoriels
	'Admin add type' => 'Ajouter un type de tutoriel',
	'Admin edit type' => 'Modifier un type de tutoriel',
	'Admin delete type' => 'Supprimer un type de tutoriel',

	'Admin type name' => 'Nom du type de tutoriel',

	'Admin type added redirect' => 'Type de tutoriel ajoutée. Redirection …',
	'Admin type edited redirect' => 'Type de tutoriel modifiée. Redirection …',
	'Admin type deleted redirect' => 'Type de tutoriel supprimée. Redirection …',

	'Admin delete type head' => 'Supprimer le type de tutoriel',
	'Admin confirm delete type subhead' => 'Confirmer la suppression du type de tutoriel',
	'Admin confirm delete type info' => 'Souhaitez-vous vraiment supprimer le type de tutoriel suivant ?<br /><q>%s</q>',
	'Admin delete type warn' => '<strong>ATTENTION !</strong> Tous les tutoriels utilisant ce type n’auront plus de type défini et devront être édités par la suite !',

	//Administrations - Tutoriels
	'Admin add tut' => 'Ajouter un tutoriel',
	'Admin edit tut' => 'Modifier un tutoriel',
	'Admin delete tut' => 'Supprimer un tutoriel',

	'Admin tut name' => 'Nom du tutoriel',
	'Admin tut desc' => 'Description du tutoriel',
	'Admin tut extra' => 'Informations supplémentaires',
	'Admin tut icon' => 'Icône du tutoriel',
	'Admin tut type' => 'Type de tutoriel',
	'Admin tut level' => 'Niveau de difficulté',

	'Admin tut added redirect' => 'Tutoriel ajouté. Redirection …',
	'Admin tut edited redirect' => 'Tutoriel modifié. Redirection …',
	'Admin tut deleted redirect' => 'Tutoriel supprimé. Redirection …',

	'Admin delete tut head' => 'Supprimer le tutoriel (et ses parties)',
	'Admin confirm delete tut subhead' => 'Confirmer la suppression du tutoriel',
	'Admin confirm delete tut info' => 'Souhaitez-vous vraiment supprimer le tutoriel suivant ?<br /><q>%s</q>',
	'Admin delete tut warn' => '<strong>ATTENTION !</strong> Supprimer le tutoriel va aussi supprimer les parties qui y sont associées !',

	'Admin add part' => 'Ajouter une partie',
	'Admin edit part' => 'Modifier une partie',
	'Admin delete part' => 'Supprimer une partie',

	'Admin part name' => 'Nom de la partie',
	'Admin part text' => 'Texte de la partie',

	'Admin part added redirect' => 'Partie ajoutée. Redirection …',
	'Admin part edited redirect' => 'Partie modifiée. Redirection …',
	'Admin part deleted redirect' => 'Partie supprimée. Redirection …',

	'Admin delete tut part head' => 'Supprimer la partie de tutoriel',
	'Admin confirm delete tut part subhead' => 'Confirmer la suppression de la partie de tutoriel',
	'Admin confirm delete tut part info' => 'Souhaitez-vous vraiment supprimer la partie de tutoriel suivante ?<br /><q>%s</q>',
	'Admin delete tut part warn' => '<strong>ATTENTION !</strong> Cette opération est irréversible !',

	//Administration - Pages
	'Admin add page' => 'Ajouter une page statique',
	'Admin edit page' => 'Modifier une page statique',
	'Admin delete page' => 'Supprimer une page statique',

	'Admin page title' => 'Titre de la page',
	'Admin page clean title' => 'Titre court de la page',
	'Admin page text' => 'Texte de la page (BBCode autorisé)',

	'Admin page added redirect' => 'Page ajoutée. Redirection …',
	'Admin page edited redirect' => 'Page modifiée. Redirection …',
	'Admin page deleted redirect' => 'Page supprimée. Redirection …',

	'Admin delete page head' => 'Supprimer la page statique',
	'Admin confirm delete page subhead' => 'Confirmer la suppression de la page statique',
	'Admin confirm delete page info' => 'Souhaitez-vous vraiment supprimer la page statique suivante ?<br /><q>%s</q>',
	'Admin delete page warn' => '<strong>ATTENTION !</strong> Cette opération est irréversible !',

	//Erreurs - Public/commun
	'No news' => 'Aucune news',
	'No tutorial' => 'Aucun tutoriel',
	'No style' => 'Aucun style',
	'No screen' => 'Aucune capture',
	'No cat' => 'Aucune catégorie',
	'No subcat' => 'Aucune sous-catégorie',
	'Ne screen' => 'Aucune capture d’écran',
	'No resource' => 'Aucune ressource',
	'No comment' => 'Aucun commentaire',
	'No part' => 'Aucune partie',
	'No type' => 'Aucun type',
	'No page' => 'Aucune page',

	'Category not found' => 'La catégorie que vous recherchez n’existe pas !',
	'Subcat not found' => 'La sous-catégorie que vous recherchez n’existe pas !',
	'Resource not found' => 'La ressource que vous recherchez n’existe pas !',
	'part not found' => 'La partie que vous recherchez n’existe pas !',
	'Comment not found' => 'Le commentaure que vous recherchez n’existe pas !',
	'Resource not found' => 'La ressource que vous recherchez n’existe pas ou n’est pas validée !',
	'Tutorial not found' => 'Le tutoriel que vous recherchez n’existe pas ou n’est pas validé !',
	'Page not found' => 'La page que vous recherchez n’existe pas !',
	'Wrong format' => 'Le format est incorrect ! Veuillez vous référez à la page de documentation pour savoir comment formater correctement les données',

	'No screenshot sent'	=> 'Aucune capture n’a été envoyée !',
	'Cannot edit comment' => 'Désolé, mais vous n’avez pas les permissions pour modifier ce commentaire !',
	'All caps message' => 'Il n’est pas autorisé d’écrire un message entièrement en lettres capitales.',
	'No message after censoring' => 'Vous devez entrer un message. Après application de la censure, votre message était vide.',
	'Must login to comment' => 'Vous devez vous identifier pour publier un commentaire.',

//	'Info' => 'Informations',
//	'Goback tutorials' => 'l’accueil des tutoriels',
//	'Error' => 'Erreur !',
//	'Page error' => 'Erreur',
//	'Return to' => 'Cliquez ici pour retourner à ',
//	'Cannot post' => 'Vous ne pouvez pas poster de commentaire car vous êtes présentement sanctionné !',
//	'Cannot delete comment' => 'Vous n’avez pas les permissions pour supprimer le commentaire d’un autre utilisateur',
//	'Must login' => 'Vous devez vous connecter pour poster un commentaire !',
//	'Goback resources' => 'l’accueil des ressources',

	'Bad request' => 'Erreur. Le lien que vous avez suivi est incorrect ou périmé.',
	'No view' => 'Vous n\'êtes pas autorisé(e) à visiter ces forums.',
	'No permission'	=> 'Vous n\'êtes pas autorisé(e) à afficher cette page.',
	'Bad referrer' => 'Mauvais HTTP_REFERER. Vous avez été renvoyé(e) vers cette page par une source inconnue ou interdite. Si le problème persiste, assurez-vous que le champ «&#160;URL de base&#160;» de la page Administration&#160;» Options est correctement renseigné et que vous vous rendez sur ces forums en utilisant cette URL. Vous pourrez trouver davantage d\'informations dans la documentation de FluxBB.',

	//Erreurs - Admin
	'Admin no catname' => 'Le nom de la catégorie est vide',
	'Admin no catclear' => 'Le nom raccourci de la catégerie est vide',
	'Admin no data' => 'Aucune donnée n’a été envoyée.  Assurez-vous d’avoir sélectionné les lengues à utiliser et rempli les champs correspondants.',
	'Admin no pic' => 'Aucune image n’a été envoyée ou elle ne s’est pas envoyée correctement.',
	'Admin no subname' => 'Le nom de la sous-catégorie est vide',
	'Admin no subclear' => 'Le nom raccourci de la sous-catégorie est vide',
	'Admin no desc' => 'La description est vide',
	'Admin no type' => 'Aucun type n’a été défini',
	'Admin no file' => 'Aucun fichier !',
	'Admin no folder' => 'Le dossier suivant n’existe pas : %s',
	'Admin no resname' => 'Le nom de la ressource envoyée est vide',
	'Admin no shortdesc' => 'La description courte est vide',
	'Admin no longdesc' => 'La description longue est vide',
	'Admin no notes' => 'Les notes de l’auteur sont vides',
	'Admin no typename' => 'Au moins un des deux champs de nom du type de tutoriel n’est pas rempli !',
	'Admin no typecat' => 'Vous n’avez spécifié aucune catégorie pour le type de tutoriel !',
	'Admin no tutname' => 'Le nom de tutoriel est vide !',
	'Admin no part name' => 'Le nom de la partie est vide !',
	'Admin no text' => 'Le texte est vide !',
	'Admin no language' => 'Aucune langue n’a été cochée !',
	'Admin no page title' => 'Le titre de la page est vide !',
	'Admin no clean title' => 'Le titre court est vide !',

	'Admin must have categories' => 'Vous devez avoir créé des catégories avant d’utiliser cette page !',
	'Admin must be integer'	=>	'La position doit être indiquée sous forme de nombre entier positif.',
	'Admin bad transfer' => 'Une erreur est survenue lors du transfert de l’image',
	'Admin file too big' => 'Le fichier est trop gros !',
	'Admin bad extension' => 'Extension incorrecte !',
	'Admin transfer success' => 'Transfert réussi !',
	'Admin bad chmod' => 'Le CHMOD du dossier demandé ne permet pas l’écriture !  Celui-ci doit être réglé à 777.',
	'Admin upload failed' => 'L’envoi a échoué car au moins un critère n’est pas rempli :',
	'Admin error while uploading' => 'Veuillez corriger les erreurs suivantes avant de reprendre l’envoi :',

	//Bouts de titres spécifiques aux pages v6
	'Pagename home' => 'Accueil',
	'Pagename news' => 'Actualités',
	'Pagename res for' => 'Ressources pour ',
	'Pagename res home' => 'Accueil des ressources',
	'Pagename for' => ' pour ',
	'Pagename tut home' => 'Accueil des tutoriels',
	'Pagename view tutorial' => 'Lire le tutoriel',
	'Pagename view comments' => 'Lire les commentaires',
	'Pagename write comment' => 'Écrire/Modifier un commentaire',
	'Pagename adm home' => 'Accueil de l’administration',
	'Pagename adm cfg' => 'Configuration du site',
	'Pagename adm resources' => 'Administration des ressources',
	'Pagename adm res cat' => 'Administration des catégories de ressources',
	'Pagename adm add res' => 'Ajouter une ressource',
	'Pagename adm tutorials' => 'Administration des tutoriels',
	'Pagename adm tuts cat' => 'Administration des catégories de tutoriels',
	'Pagename adm tuts types' => 'Administration des types de tutoriels',
	'Pagename adm pages' => 'Administration des pages',
	'Pagename adm comments' => 'Administration des commentaires',

	//titres d'accueil
	'Title home' => 'Bienvenue sur %s !',
	'Title news' => 'À la une !',
	'Title resources' => 'Ressources pour vos sites',
	'Title tutorials' => 'Tutoriels pour vos sites',
	'Explain home' => '<p>Des tutoriels et ressources pour vos sites, dans l’esprit du libre !</p>',
	'Explain news' => '<p>Les actualités du site !</p>',
	'Explain res home' => '<p>Vous retrouverez ici les styles, des hacks (modifications, add-ons et snippets) et autres ressources pour personnaliser vos sites et forums</p>',
	'Explain tuts home' => '<p>Vous retrouverez ici les tutoriels et astuces pour vous aider à personnaliser vos sites et forums.</p>',
	'Title adm home' => 'Accueil de l’administration',
	'Explain adm home' => '<p>Ici vous pouvez gérer les ressources, tutoriels, commentaires et pages publiées par vous ou vos membres, ainsi que configurer votre site.</p>',
	'Title adm cfg' => 'Configuration du site',
	'Explain adm cfg' => '<p>C’est à partir d’ici que vous pouvez configurer les modules de votre site.  Notez qu’une notation spéciale est utilisée pour assurer une gestion souple du bilinguisme.  Pour en apprendre plus sur son fonctionnement, consultez la page wiki consacrée à ce système sur le dépôt GitHub du site.</p>',
	'Title adm resources' => 'Administration des ressources',
	'Explain adm res home' => '<p>Ici vous pouvez gérer tout ce qui est relatif aux ressources.</p>',
	'Title adm res cat' => 'Administration des catégories des ressources',
	'Explain adm res cat' => '<p>C’est ici que vous créez, modifiez, supprimez et organisez vos catégories et sous-catégories pour les ressources à publier sur le site.</p><p>Les catégories doivent représenter la plate-forme ou le CMS correspondant aux ressources à publier, tandis que les sous-catégories, permettent de subdiviser les ressources en deux types au sein de la catégorie, selon s’il s’agit d’une modification (hack) ou d’un style.</p>',
	'Explain adm resources' => '<p>Ici vous pouvez ajouter, mettre à jour et supprimer des ressources, ainsi que les captures d’écran associés.</p>',
	'Title adm tutorials' => 'Administration des tutoriels',
	'Explain adm tuts home' => '<p>Ici vous pouvez gérer tout ce qui est relatif aux tutoriels.</p>',
	'Explain adm tutorials' => '<p>Ici vous pouvez ajouter, modifier et supprimer des tutoriels et leurs parties.</p>',
	'Title adm tuts cat' => 'Administration des catégories de tutoriels',
	'Explain adm tuts cat' => '<p>C’est ici que vous créez, modifiez, supprimez et organisez vos catégories pour les tutoriels à publier sur le site.</p><p>Les catégories doivent représenter la plate-forme ou le CMS correspondant aux tutoriels à publier.</p>',
	'Title adm tuts types' => 'Administration des types de tutoriels',
	'Explain adm tuts types' => '<p>Ici vous pouvez gérer les types de tutorials, pour classifier ceux-ci pour faciliter la recherche pour les utilisateurs.  Ainsi par exemple pour des tutoriels Gimp, on peut faire des types « Design », « Retouche », « Astuce », et ainsi de suite.  Certains types plus généraux comme « Astuces » peuvent être utilisés par plus d’une catégorie.</p>',
	'Explain add comment' => '<p>Les commentaires sont faits pour exprimer votre avis sur le tutoriel.  Si des choses sont à améliorer, soyez constructifs dans vos critiques, en expliquant les détails du point à améliorer.<br />IMPORTANT : Les commentaires ne sont pas faits pour les demandes de supports, ceux-ci doivent être faits sur le forum !</p>',
	'Title adm pages' => 'Administration des pages',
	'Explain adm pages' => '<p>Ici vous pouvez créer, modifier et supprimer les pages statiques pour afficher des informations qui seront peu enclins à des changements fréquents.  Les BBCodes sont autorisés.</p>',
	'Title adm comments' => 'Administration des commentaires',
	'Explain adm comments' => '<p>Ici vous pouvez modérer les commentaires, peu importe le tutoriel auquel les commentaires sont rattachés.  Vous pouvez utiliser le menu déroulant pour n’afficher les commentaires d’un tutoriel spécifique.</p>',


//Obsolètes
/*	'pagename_access' => 'Politique d’accessibilité',
	'pagename_disclaimer' => 'Conditions d’utilisation',
	'pagename_reviews' => 'Classement des sites analysés',
	'pagename_battles' => 'Résultats des duels',
	'pagename_forumotion' => 'Thèmes pour forumactifs',
	'pagename_phpbb2' => 'Templates pour phpBB2',
	'pagename_phpbb3' => 'Styles pour phpBB3',
	'pagename_cb' => 'Skins pour Connectix Boards',
	'pagename_tutogimp' => 'Tutoriels GIMP',
	'pagename_webdiz' => 'Kits graphiques',
	'pagename_svg' => 'Fichiers SVG',
	'pagename_archives' => 'Archives des news',
	'pagename_howto' => 'Installation des styles pour forums',*/



//Obsolètes
/*	'title_access' => 'Politique d’accessibilité',
	'title_disclaimer' => 'Conditions d’utilisation',
	'title_reviews' => 'Classement des sites analysés',
	'title_battles' => 'Résultat des duels',
	'title_forumotion' => 'Thêmes Forumactif',
	'title_phpbb2' => 'Templates phpBB2',
	'title_phpbb3' => 'Styles phpBB3',
	'title_cb' => 'Skins Connectix Boards',
	'title_tutogimp' => 'Tutoriels GIMP',
	'title_webdiz' => 'Kits graphiques',
	'title_svg' => 'Mes fichiers SVG',
	'title_archives' => 'Anciennes news',
	'title_howto' => 'Comment installer un style sur son forum',*/

	//Descriptions des pages

/*	'explain_home' => '<p class="explain"><strong>À propos du site :</strong> Ishimaru Design est un projet personnel datant de début 2007 et qui a pour but de fournir de l’aide et des ressources, principalement sur le plan graphique, pour les forums et sites. D’abord monté sur un forum Forumactif, Ishimaru Design ne donnait des services que pour les forums hébergés sur Forumactif, mais depuis, le site a déménagé sur un hébergement indépendant et le forum utilise maintenant la plateforme libre <a href="http://www.connectix-boards.org">Connectix Boards</a>, et les services se sont élargis pour prendre en compte également les sites non-Forumactif.</p>
	<p class="explain"><strong>Les services proposés :</strong> Ishimaru Design fournit du support pour le graphisme de vos sites et de vos forums Forumactif, phpBB2, phpBB3 et Connectix Boards, telles que les commandes graphiques, du support graphique et technique, des astuces et des thèmes complets. Vous y retrouverez aussi des MODs Connectix Boards, des tutoriaux GIMP et un laboratoire d’analyse.</p>
	<p class="explain"><strong>Coût :</strong> C’est totalement gratuit !</p>
	<!--[if lte IE 6]>
	<p class="warning">Le navigateur que vous utilisez (Internet Explorer 6) est obsolète et risque de ne pas afficher correctement certaines parties du site en plus de vous exposer à de grands risques de problèmes de sécurité. Je vous recommande autant que possible de mettre à jour vers une version plus récente d’Internet Explorer, ou de prendre un navigateur plus respectueux des standards comme <a href="http://www.mozilla-europe.org/fr/firefox/">Mozilla Firefox</a>, <a href="http://www.opera-fr.com/telechargements/">Opera</a>, <a href="http://www.google.com/chrome?hl=fr">Google Chrome</a> ou <a href="http://www.apple.com/fr/safari/">Apple Safari</a>. Vous ne pouvez rien installer au boulot ? Essayez l’une des versions portables de <a href="http://portableapps.com/apps/internet/firefox_portable">Firefox</a>, <a href="http://portableapps.com/apps/internet/google_chrome_portable">Google Chrome</a> ou <a href="http://portableapps.com/apps/internet/opera_portable">Opera</a>, qui ne requièrent aucune installation et qui peuvent être transportées sur une clé USB !</p>
	<![endif]-->',*/



/*	'explain_access' => '<p class="text explain">En tant qu’internaute vivant avec une déficience visuelle, au moment de la refonte du site, je me suis penchée sur l’accessibilité du site afin de l’améliorer graduellement afin de viser une clientèle plus large.<br />
	   Voici les points qui ont été améliorés jusqu’à maintenant :</p>
	   <ul class="list">
	     <li>Afin de garder une lecture aisée même sur les grosses résolutions et sous Internet Explorer, les tailles des polices en pixels (taille fixe) ont été remplacées par des tailles relatives avec l’usage de l’unité <em>em</em>;</li>
		 <li>Des liens d’accès rapide ont été ajoutés en haut de la page afin de faciliter la navigation à ceux qui naviguent sans souris. Un lien d’évitement en bas de la page a aussi été ajouté pour faciliter le retour en haut de la page;</li>
		 <li>Aucun élément Javascript, applet Java ou animation Flash pouvant entraver la navigation n’a été utilisé sur le site. De toutes façons, mes connaissances en Javascript sont encore trop maigres pour pouvoir en mettre;</li>
		 <li>Pour un chargement rapide et une navigation aisée, la présentation est séparée du contenu avec l’usage d’une feuille de style pour gérer la présentation, ainsi que l’utilisation d’une déclaration de type de document stricte;</li>
		 <li>Toujours pour assurer un chargement rapide et une navigation aisée, la mise en page du site n’utilise pas de tableaux, ni de GIFs transparent. Les tableaux ne sont utilisés que pour les données classifiées, ce qui inclut la liste des forums et la liste des sujets.</li>
	   </ul>
	   <p class="text explain"><strong>Note</strong> : D’autres mesures viendront s’ajouter au fil de mon apprentissage en accessibilité.</p>',

	'explain_reviews' => '<p class="explain text">Le forum offrant un service d’analyse du design de votre site, vous avez le choix de participer au classement des sites analysés. Pour simplifier la gestion du classement, celui-ci sera géré avec la base de données, et les analystes qui auront les permissions pourront gérer la liste des sites analysés.</p>',

	'explain_battles' => '<p class="explain text">Ici vous retrouverez les résultats des différents duels graphiques qui ont eu lieu sur le forum.</p>',

	'explain_disclaimer' => '<p class="explain text">Veuillez lire ces conditions d’utilisation avant d’utiliser les ressources disponibles sur le site.</p>
       <h4 class="solo">Utilisation des ressources graphiques</h4>
       <p class="explain text">Tous les ressources disponibles appartiennent à leurs auteurs. Sauf mention explicite d’une licence le permettant, vous ne pouvez pas redistribuer les ressources sans ma permission, ni les vendre.<br />
		Et même si une licence permettait la redistribution, vous ne devez en aucun cas vous approprier la création de ces ressources et donc, dans le cas des thèmes pour forums phpBB2 et phpBB3, vous ne devez pas retirer la ligne de copyright indiquant mon nom. En cas de manquement à ce point, ceci est considéré comme un manque de respect du travail d’autrui et le support vous sera refusé. Dans le cas des thèmes pour Forumactif et Connectix Boards, vous êtes tenus à indiquer le nom du site si quelqu’un vous demande la provenance. Le respect de l’auteur original vaut aussi pour la publication d’un thème Forumactif sur Hitskin.<br />
		Vous pouvez modifier ces styles et templates pour vos besoins et les adapter pour une autre plate-forme de forum, mais veuillez me contacter si vous comptez distribuer ces adaptations.</p>
       <h4 class="solo">Les tutoriels</h4>
       <p class="explain text">Comme pour les templates et styles, les tutoriels sont la propriété de leurs auteurs respectifs. Sauf si une licence le permet, il est interdit de les distribuer, ni de les vendre sans ma permission.</br />
		Et quelle que soit la licence, vous ne pouvez pas vous en approprier l’écriture de ces tutoriels.</p>
       <h4 class="solo">Sites autorisés</h4>
       <p class="explain text">Une liste de sites ayant eu mon approbation pour la diffusion de mes ressources ou tutoriels sera tenue à jour.</p>
	   <ul class="links list">
		 <li><a href="http://www.connectix-boards.org">Connectix Boards</a></li>
		 <li><a href="http://forums.phpbb-fr.com">phpBB-fr</a></li>
		 <li><a href="http://phpbb.2037.org">phpBB.biz</a></li>
		 <li><a href="http://projet-gimp.forumpro.fr">Projet Gimp</a></li>
		 <li><a href="http://www.siteduzero.com">Le Site du Zéro</a></li>
		 <li><a href="http://www.phpbb-france.com">phpBB France</a></li>
	   </ul>',

	'explain_forumotion' => '<p class="explain text">Ici, vous trouverez mes thèmes pour forums Forumactif, à la fois disponibles sur Hitskin pour ceux qui préfèrent la manière rapide, et sous forme d’archives pour ceux qui préfèrent l’installation manuelle. Les thèmes sont disponibles en français et en anglais.</p>
		<p class="explain">Pour savoir comment installer ces thèmes manuellement, lisez les instructions données sur <a href="howto-style.php">cette page</a>.</p>',

	'explain_phpbb2' => '<p class="explain text">Ici vous trouverez les templates phpBB2 que j’ai faits ou adaptés et que vous pouvez utiliser pour décorer votre forum. La plupart des templates sont des adaptations de thèmes Forumactif, d’où certaines similitudes dans la structure, en particulier au niveau de l’entête.<br />Ces templates sont tous bilingues français-anglais, barre de navigation incluse.</p>
		<p class="explain">Pour savoir comment installer ces templates, lisez les instructions données sur <a href="howto-style.php">cette page</a>.</p>',

	'explain_phpbb3' => '<p class="explain text">Ici vous trouverez les styles phpBB3 que j’ai faits et que vous pouvez utiliser pour décorer votre forum.</p>
		<p class="explain">Pour savoir comment installer ces styles, lisez les instructions données sur <a href="howto-style.php">cette page</a>.</p>',

	'explain_cb' => '<p class="explain text">Ici vous trouverez les skins Connectix Boards que j’ai faits et que vous pouvez installer sur votre forum.</p>
		<p class="explain">Pour savoir comment installer ces skins, lisez les instructions données sur <a href="howto-style.php">cette page</a>.</p>',

	'explain_tutogimp' => '<p class="explain text">Vous trouverez ici tous les tutoriels écrits qui ont été publiés sur le forum. Étant donné que GIMP a eu des mises à jour majeures au cours des deux dernières années, certains des tutoriels ont été écrits en se basant sur une version de GIMP qui est probablement plus ancienne que la vôtre, ce qui peut causer certaines difficultés à suivre les tutos. Pour cette raison, j’indique la version utilisée pour chaque tutoriel écrit.</p>
		<p class="explain text">Si vous êtes un total débutant dans l’utilisation de GIMP, je vous invite à consulter mon cours <a href="http://www.siteduzero.com/tutoriel-3-317520-apprenez-a-creer-avec-gimp-2-6.html">Apprenez à créer avec GIMP 2.6</a> qui a été validé au début de septembre, sur le Site du Zéro. Ce cours part de zéro et est basé sur la dernière version stable de GIMP.</a>',

	'explain_webdiz' => '<p class="text explain">Vous trouverez ici les webdesigns que j’ai réalisés pour des commandes et qui n’ont jamais été utilisés. Plutôt que de les laisser s’empoussiérer sur mon disque dur, j’ai préféré les recycler en kits librement téléchargeables.</p>
	   <p class="text explain">Ces kits sont valides XHTML 1.0 Strict et CSS 2.1, et une attention a été portée au niveau de l’accessibilité en utilisant des tailles de texte relatives avec une dégradation graphique la plus gracieuse possible à l’agrandissement, et l’utilisation de liens d’évitement. Ils ont été testés sous Firefox et Chromium et devraient donc fonctionner sur l’ensemble des navigateurs utilisant Gecko ou Webkit. Par contre, étant sous Linux, il m’est plus difficile de garantir la compatibilité avec Internet Explorer.</p>
	   <p class="text explain">En cas de souci, n’hésitez pas à poster sur le forum de ce site ou sur les forums du <a href="http://www.siteduzero.com">Site du Zéro</a> ou d’<a href="http://www.alsacreations.com">Alsacréations</a> pour obtenir de l’aide.</p>',

	'explain_svg' => '<p class="explain text">Voici les icônes que j’ai réalisés avec Inkscape depuis que j’ai recommencé à faire du dessin vectoriel.<br />
          Vous pouvez les utiliser pour vos icones, thèmes et webdesigns, et les modifier à votre guise.</p>
          <p class="explain text">Tous ces icônes sont compressés au format ZIP.</p>
       <p class="explain text"><a rel="license" href="http://creativecommons.org/licenses/by/2.5/ca/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/2.5/ca/88x31.png"/></a><br/>Ces créations sont mises à disposition sous un <a rel="license" href="http://creativecommons.org/licenses/by/2.5/ca/">contrat Creative Commons</a></p>',

	'explain_archives' => '<p class="text">Ici vous trouverez les news qui avaient été publiées avec l’ancien module qui n’était pas relié au forum.</p>',

	'explain_howto' => '<p class="explain text">Si vous ne savez pas comment installer un style sur votre forum Forumactif, phpBB ou CB, suivez les instructions ci-dessous.</p>
		<h4 class="solo">Décompresser le style</h4>
		<p class="explain">Tous les styles hébergés ici sont archivés au format .ZIP. À moins d’avoir une vieille version de Windows (ça existe encore Windows 2000 ??), vous devez normalement pouvoir les décompresser sans avoir besoin d’installer de logiciel de décompression.</p>
		<ul class="list">
			<li><strong>Sous Windows XP et versions ultérieures :</strong> clic-droit &gt; Extraire tout.</li>
			<li><strong>Sous n’importe quelle distribution Linux :</strong> clic-droit &gt; Ouvrir avec le gestionnaire d’archives <strong>ou</strong> utiliser la commande <code>unzip monarchive.zip</code> dans le terminal.</li>
			<li><strong>Sous Mac OSX :</strong> N’ayant jamais touché à cet OS, je suppose que la manière est semblable à ce qu’on fait sur les deux autres systèmees.</li>
		</ul>
		<h4 class="solo">Installer un thème Forumactif (depuis un ZIP)</h4>
		<p class="explain">Bien que Hitskin soit pratique pour installer rapidement un thème certains de mes thèmes ne sont pas disponibles là-bas en plus que certains boutons peuvent être manquants.</p>
		<p class="explain">Pour installer manuellement un thème Forumactif depuis une archive ZIP, voici les étapes à suivre une fois celle-ci décompressée :</p>
		<ol class="list">
			<li>Tout d’abord, tous les thèmes signés <strong>Ishimaru Chiaki</strong> comprennent un fichier <strong>fr/install/panadmin.html</strong> pour les correspondances des images, et un fichier <strong>fr/install/couleurs.html</strong> pour les instructions d’installation, et il est donc fortement recommandé de les consulter avant l’installation.</li>
			<li>Les thèmes signés <strong>Ishimaru Chiaki</strong> incluent un fichier <strong>.bbtheme</strong> dans le dossier <strong>fr/install</strong>, pour importer facilement les couleurs du thème. Pour l’importer, allez dans votre <em>panneau d’administration &gt; Affichage &gt; Gestion des thèmes</em> et utilisez le formulaire d’importation de thèmes pour y importer le .bbtheme des couleurs.</li>
			<li>Hébergez tous vos images sur un hébergeur prévu à cette fin, comme <a href="http://www.servimg.com">servimg</a>, <a href="http://www.imageshack.us">imageshack</a> ou <a href="http://www.archive-host.com">Archive-Host</a> entre autres.</li>
			<li>Une fois vos images hébergées, en vous aidant avec le fichier <strong>fr/install/panadmin.html</strong>, placez les liens un à un dans votre <em>panneau d’administration &gt; Affichage &gt; Gestion des images</em>.</li>
			<li>Certains thèmes demandent des configurations particulières pour un rendu optimal, comme le centrage du logo et/ou de la barre des liens, la désactivation des textes dans la barre des liens ou le repositionnement de certains boutons. Tout cela peut être configuré depuis votre <em>panneau d’admin &gt; Affichage &gt; En-tête et Navigation</em>.</li>
			<li>Dans le cas où une fichier <strong>css.txt</strong> est inclus dans le dossier <strong>fr/install</strong> du thème, allez dans votre <em>panneau d’admin &gt; Affichage &gt; Couleurs &gt; (onglet) Feuille de style CSS</em> puis recopiez dans le champs tout le contenu du fichier.</li>
			<li>Votre thème est maintenant installé !</li>
		</ol>
		<h4 class="solo">Installer un template phpBB2</h4>
		<ol class="list">
			<li>Avec un logiciel FTP (ex: Filezilla), envoyez le dossier décompressé du template dans le dossier <strong>templates</strong> de votre forum. Faites attention à bien vous assurer d’avoir quelque chose comme <strong>templates/votrestyle</strong> et non comme <strong>templates/votrestyle/votrestyle</strong> car sinon, le thème ne sera pas détecté ! Cette erreur est particulièrement courante chez les utilisateurs de Windows.</li>
			<li>En supposant que vous n’utilisez pas le MOD eXtremeStyle de CyberAlien, allez dans votre <em>panneau d’administration &gt; Administration des thèmes &gt; Ajouter</em>, puis installez le nouveau thème qui doit normalement apparaître.</li>
			<li>Il ne vous reste plus qu’à mettre ce thème par défaut dans la configuration générale du forum et à changer le style utilisé par les utilisateurs.</li>
		</ol>
		<h4 class="solo">Installer un style phpBB3</h4>
		<ol class="list">
			<li>Avec un logiciel FTP (ex: Filezilla), envoyez le dossier décompressé du style dans le dossier <strong>styles</strong> de votre forum.  Faites attention à bien vous assurer d’avoir quelque chose comme <strong>styles/votrestyle</strong> et non comme <strong>styles/votrestyle/votrestyle</strong> car sinon, le style ne sera pas détecté ! Cette erreur est particulièrement courante chez les utilisateurs de Windows.</li>
			<li>Allez dans votre <em>panneau d’administration &gt; Styles &gt; dans la page d’accueil des styles</em>, vous devez normalement voir un nouveau style dans la liste des styles non-installés. Si c’est le cas, installez-le. En même temps, vous pouvez le mettre par défaut.</li>
		</ol>
		<h4 class="solo">Installer un skin CB</h4>
		<ol class="list">
			<li>Avec un logiciel FTP (ex: Filezilla), envoyez le dossier décompressé contenant les fichiers CSS et les images dans le dossier <strong>skins</strong> de votre forum. Ensuite, si un dossier contenant un jeu de templates est présent, envoyez ce dossier dans le dossier <strong>templates</strong> de votre forum. Dans les deux cas, assurez-vous d’éviter les doublons dans les chemins (ex: skins/monstyle/ est correcte tandis que skins/monstyle/monstyle ne l’est pas), comme cela peut particulièrement arriver si vous êtes sur Windows.</li>
			<li>Aucune installation requise ! Vous n’avez qu’à aller dans l’<em>administration &gt; Configuration de base &gt; Paramètres généraux du forum &gt; Design par défaut &gt;</em> vous le changez pour le mettre par défaut pour les invités et ceux qui n’ont pas encore choisi de skin dans leur profil. Ensuite, les utilisateurs ayant déjà choisi un skin dans leur profil n’auront qu’à le changer.</li>
		</ol>',*/

	//Menu horizontal
/*	'speedbar_home' => 'Accueil',
	'speedbar_forum' => 'Forum',
	'speedbar_blog' => 'Blogue',
	'speedbar_access' => 'Accessibilité',
	'speedbar_portfolio' => 'Portfolio',
	'speedbar_switch_lang' => 'English',
	'speedbar_staff' => 'Espace Staff',
	'speedbar_disclaimer' => 'Conditions d’utilisation',*/

	//Sidebar
/*	'sub_community' => 'Communauté',
	'sub_styles' => 'Thèmes complets',
	'sub_misc' => 'Divers',
	'sub_topten' => 'Top 10 des posteurs',
	'sub_lastposts' => 'Derniers sujets',
	'sub_poll' => 'Sondage',
	'page_reviews' => 'Sites analysés',
	'page_battles' => 'Résultats duels',
	'page_forumotion' => 'Forumactif',
	'page_phpbb2' => 'phpBB 2.0.x',
	'page_phpbb3' => 'phpBB 3.0.x',
	'page_cb' => 'ConnectixBoards 0.8.x',
	'page_tutogimp' => 'Tutos GIMP',
	'page_svg' => 'Fichiers SVG',
	'page_webdiz' => 'Kits graphiques',	*/

	//Navigation rapide
/*	'goto_menu' => 'Aller au menu',
	'goto_content' => 'Aller au contenu',
	'goto_top' => 'Revenir en haut',

	//Divers
	'lastposts_nothing' => 'Aucun sujet à afficher !',
	'lastposts_by' => ' par ',
	'topten_nothing' => 'Rien à afficher !',
	'poll_id' => 5,
	'poll_erroneous' => 'La proposition sélectionnée est erronée',
	'poll_none' => 'Aucun sondage à afficher !',
	'by' => 'par',
	'poll_nb_votes' => ' vote(s)',
	'poll_nb_blank' => 'Votes blancs',
	'poll_send_vote' => 'Voter',
	'poll_send_blank' => 'Voter blanc',
	'footer_content' => 'Copyright "Ishimaru-Design" 2007, tous droits réservés | <a href="http://validator.w3.org/check?uri=referer">XHTML 1.0</a>',

	//Index
	'home_news' => 'Les nouvelles du site',
	'home_forum_news' => 100,
	'home_posted' => 'Posté par ',
	'home_by' => 'par ',
	'home_reacted' => 'a suscité',
	'home_comments' => ' commentaires',
	'home_read' => 'Lire la news',
	'home_showall' => 'Voir toutes les news du site',
	'home_archives' => 'Archives de l’ancien module de news',
	'home_nothing' => 'Aucune news à afficher !',

	//Résultats des duels
	'battles_thumb' => 'Cliquer pour agrandir',
	'battles_winner' => 'Gagnant : ',
	'battles_desc' => 'Description : ',
	'battles_nothing' => 'Aucun résultat à afficher !',

	//Sites analysés
	'reviews_standing' => 'Classement',
	'reviews_site' => 'Site analysé',
	'reviews_admin' => 'Admin du site',
	'reviews_reviewed' => 'Analysé par',
	'reviews_result' => 'Note',
	'reviews_nothing' => 'Aucun site analysé à afficher !',

	//Variables communes aux pages de styles
	'fm_heading' => 'Liste des thèmes',
	'phpbb2_heading' => 'Liste des templates',
	'phpbb3_heading' => 'Liste des styles',
	'cb_heading' => 'Liste des skins',
	'fm_preview' => 'Aperçu du thème',
	'phpbb2_preview' => 'Aperçu du template',
	'phpbb3_preview' => 'Aperçu du style',
	'cb_preview' => 'Aperçu du skin',
	'fm_name' => 'Nom du thème :',
	'phpbb2_name' => 'Nom du template :',
	'phpbb3_name' => 'Nom du style :',
	'cb_name' => 'Nom du skin :',
	'style_author' => 'Auteur :',
	'style_compat' => 'Compatibilité :',
	'style_lang' => 'Langues :',
	'style_desc' => 'Caractéristiques :',
	'style_download' => 'Téléchargement :',
	'style_here' => 'ici',
	'fm_notes' => 'Notes sur le thème :',
	'phpbb2_notes' => 'Notes sur le template :',
	'phpbb3_notes' => 'Notes sur le style :',
	'cb_notes' => 'Notes sur le skin :',
	'fm_support' => 'Le support pour ce thème doit se faire ',
	'phpbb2_support' => 'Le support pour ce template doit se faire ',
	'phpbb3_support' => 'Le support pour ce style doit se faire ',
	'cb_support' => 'Le support pour ce skin doit se faire ',
	'fm_no_support' => 'Aucun support disponible pour ce thème !',
	'phpbb2_no_support' => 'Aucun support disponible pour ce template !',
	'phpbb3_no_support' => 'Aucun support disponible pour ce style !',
	'cb_no_support' => 'Aucun support disponible pour ce skin !',
	'fm_nothing' => 'Aucun thème à afficher !',
	'phpbb2_nothing' => 'Aucun template à afficher !',
	'phpbb3_nothing' => 'Aucun style à afficher !',
	'cb_nothing' => 'Aucun skin à afficher !',

	//Spécifiques à certains types
	'fm_version' => 'Forumactif version ',
	'fm_css' => 'CSS personnalisé :',
	'fm_zip' => 'Archive .ZIP',
	'fm_hitskin' => 'Lien Hitskin',
	'fm_available' => 'Thèmes disponibles par version',
	'fm_ver_explain' => '<p class="explain text">Forumactif met à la disposition de ses utilisateurs 4 versions différentes, offrant chacun un affichage différent avec une structure HTML et une feuille CSS spécifiques à la version, mais les fonctionnalités restent les mêmes quelque soit la version.</p>
		<p class="explain text">Bien que les thèmes aient tous une relative compatibilité, je vous recommande quand même d’opter pour un thème correspondant à la version que vous utilisez, afin d’avoir un rendu optimal sur le plan esthétique et aussi parce que la présence d’une feuille CSS personnalisée dans le panneau d’administration empêche le changement de version.</p>',
	'fm_pagelink' => 'themes_fa',
	'fm_bb2' => 'Forumactif phpBB2',
	'fm_bb2_desc' => 'C’est la plus ancienne version disponible sur Forumactif, et aussi la plus stable. Forumactif ayant démarré vers 2003-2004, il est donc parti d’une base phpBB2 qui a été lourdement modifiée depuis. Vous y trouverez les thèmes qui ont été faits à partir de cette version.',
	'fm_bb3' => 'Forumactif phpBB3',
	'fm_bb3_desc' => 'C’est la deuxième version à arriver sur Forumactif. Basée sur phpBB3 Olympus, cette version offre un style très moderne aux coins arrondis et codé sans tableaux. Vous y trouverez les thèmes qui ont été faits à partir de cette version.',
	'fm_pun' => 'Forumactif PunBB',
	'fm_pun_desc' => 'Légère et épurée, cette version, basée sur PunBB 1.3.x est la troisième version à arriver sur Forumactif. Vous y trouverez les thèmes qui ont été faits à partir de cette version.',
	'fm_ipb' => 'Forumactif Invision',
	'fm_ipb_desc' => 'Dérivé du template de la célèbre plateforme payante Invision Power Board, cette version offre beaucoup de personnalisation au niveau du CSS et est la dernière version à arriver sur Forumactif. Vous y trouverez les thèmes qui ont été faits à partir de cette version.',
	'phpbb3_base' => 'Basé sur',
	'phpbb3_html' => 'Fichiers HTML modifiés',

	//Tuto GIMP
	'gimp_published' => 'Publié sur le forum par ',
	'gimp_thumb' => 'Vignette du tutoriel',
	'gimp_read' => 'Lire le tutoriel',
	'gimp_type' => 'Type',
	'gimp_version' => 'Version de GIMP',
	'gimp_level' => 'Niveau',
	'gimp_nothing' => 'Aucun tutoriel à afficher !',
	'gimp_sort' => 'Trier les tutoriels',
	'gimp_newbie' => 'Débutant',
	'gimp_newbie_lower' => 'Debutant',
	'gimp_inter' => 'Intermédiaire',
	'gimp_inter_lower' => 'Intermediaire',
	'gimp_adv' => 'Avancé',
	'gimp_adv_lower' => 'Avance',
	'gimp_all' => 'Tous',
	'gimp_sortby' => 'Trier par',
	'gimp_id' => 'ID du tuto',
	'gimp_author' => 'Auteur',
	'gimp_order' => 'Ordre',
	'gimp_asc' => 'Ascendant',
	'gimp_desc' => 'Descendant',
	'gimp_send' => 'Valider',
	'gimp_url' => 'tutogimp',

/*
	Partie administration
*/

	//Page de connexion
/*	'login_title' => 'Connexion à l’espace réservée au Staff',
	'login_heading' => 'Connexion',
	'login_explain' => 'Veuillez entrer l’identifiant et le mot de passe pour vous connecter',
	'login_login' => 'Identifiant :',
	'login_passwd' => 'Mot de passe :',
	'login_auto' => 'Connexion auto :',
	'login_send' => 'Envoyer',

	//Titre des pages
	'adm_pagename_home' => 'Accueil Espace Staff',
	'adm_pagename_battles' => 'Administration des résultats des duels',
	'adm_pagename_reviews' => 'Administration des sites analysés',
	'adm_pagename_fm' => 'Administration des styles Forumactif',
	'adm_pagename_phpbb2' => 'Administration des styles phpBB2',
	'adm_pagename_phpbb3' => 'Administration des styles phpBB3',
	'adm_pagename_cb' => 'Administration des skins Connectix Boards',
	'adm_pagename_gimp' => 'Administration des tutoriels GIMP',

	//Titres d'accueil
	'adm_title_home' => 'Espace Staff',
	'adm_title_add_battle' => 'Gestion des résultats des duels - Ajout/Édition d’une entrée',
	'adm_title_list_battles' => 'Gestion des résultats des duels',
	'adm_title_add_review' => 'Gestion des sites analysés - Ajout/Édition d’une entrée',
	'adm_title_list_reviews' => 'Gestion du classement des sites analysés',
	'adm_title_add_fm' => 'Gestion des thèmes Forumactif - Ajout/Édition d’une entrée',
	'adm_title_list_fm' => 'Gestion des thèmes Forumactif',
	'adm_title_add_phpbb2' => 'Gestion des templates phpBB2 - Ajout/Édition d’une entrée',
	'adm_title_list_phpbb2' => 'Gestion des templates phpBB2',
	'adm_title_add_phpbb3' => 'Gestion des styles phpBB3 - Ajout/Édition d’une entrée',
	'adm_title_list_phpbb3' => 'Gestion des styles phpBB3',
	'adm_title_add_cb' => 'Gestion des skins Connectix Boards - Ajout/Édition d’une entrée',
	'adm_title_list_cb' => 'Gestion des skins Conectix Boards',
	'adm_title_add_gimp' => 'Gestion des tutoriels GIMP - Ajout/Édition d’une entrée',
	'adm_title_list_gimp' => 'Gestion des tutoriels GIMP',

	//Descriptions des pages
	'adm_explain_home' => '',
	'adm_explain_add_battle' => '<p class="explain text">Ici vous pouvez ajouter ou modifier une entrée. Prenez note que vous <strong>devez</strong> envoyer la création du gagnant depuis votre ordinateur en utilisant ce formulaire, afin d’éviter les liens morts.</p>',
	'adm_explain_list_battles' => '<p class="explain text">Ici vous pouvez ajouter, éditer ou supprimer les résultats de duels qui sont affichées dans la page des résultats.</p>',
	'adm_explain_add_review' => '<p class="text explain">Ici vous entrez les infos pour une nouvelle entrée ou éditer les infos d’une entrée existante.</p>',
	'adm_explain_list_reviews' => '<p class="text explain">Ici vous pouvez gérer le classement des sites analysés en ajoutant, éditant ou supprimant une entrée.</p>',
	'adm_explain_add_fm' => '<p class="explain text">Ici vous pouvez ajouter ou modifier une entrée. Prenez note que vous <strong>devez</strong> envoyer la capture depuis votre ordinateur en utilisant ce formulaire, afin d’éviter les liens morts. Prenez note aussi que la suppression d’une entrée ne supprimera pas l’archive du thème.</p>',
	'adm_explain_list_fm' => '<p class="explain text">Ici vous pouvez ajouter, éditer ou supprimer les thèmes Forumactif qui sont affichées dans la page des thèmes.</p>',
	'adm_explain_add_phpbb2' => '<p class="explain text">Ici vous pouvez ajouter ou modifier une entrée. Prenez note que vous <strong>devez</strong> envoyer la capture depuis votre ordinateur en utilisant ce formulaire, afin d’éviter les liens morts. Prenez note aussi que la suppression d’une entrée ne supprimera pas l’archive du template.</p>',
	'adm_explain_list_phpbb2' => '<p class="explain text">Ici vous pouvez ajouter, éditer ou supprimer les templates phpBB2 qui sont affichées dans la page des templates.</p>',
	'adm_explain_add_phpbb3' => '<p class="explain text">Ici vous pouvez ajouter ou modifier une entrée. Prenez note que vous <strong>devez</strong> envoyer la capture depuis votre ordinateur en utilisant ce formulaire, afin d’éviter les liens morts. Prenez note aussi que la suppression d’une entrée ne supprimera pas l’archive du style.</p>',
	'adm_explain_list_phpbb3' => '<p class="explain text">Ici vous pouvez ajouter, éditer ou supprimer les styles phpBB3 qui sont affichées dans la page des styles.</p>',
	'adm_explain_add_cb' => '<p class="explain text">Ici vous pouvez ajouter ou modifier une entrée. Prenez note que vous <strong>devez</strong> envoyer la capture depuis votre ordinateur en utilisant ce formulaire, afin d’éviter les liens morts. Prenez note aussi que la suppression d’une entrée ne supprimera pas l’archive du skin.</p>',
	'adm_explain_list_cb' => '<p class="explain text">Ici vous pouvez ajouter, éditer ou supprimer les skins Connectix Boards qui sont affichées dans la page des skins.</p>',
	'adm_explain_add_gimp' => '<p class="text explain">C’est ici que vous entrez ou modifiez les informations relatives à un tutoriel affiché sur le site.</p>',
	'adm_explain_list_gimp' => '<p class="text explain">Ici vous pouvez ajouter, éditer, supprimer les tutoriaux GIMP qui sont affichés sur le site.  Notez que la suppression d’une entrée ne supprimera pas le sujet posté sur le forum.</p>',

	//Menu de l'administration
	'adm_sub_admin' => 'Partie admin',
	'adm_sub_styles' => 'Gestion des styles téléchargeables',
	'adm_sub_reviewer' => 'Partie Analyste',
	'adm_sub_gfx' => 'Partie Graphiste',
	'adm_page_forum' => 'Administration du forum',
	'adm_page_fm' => 'Gestion des styles Forumactif',
	'adm_page_phpbb2' => 'Gestion des styles phpBB2',
	'adm_page_phpbb3' => 'Gestion des styles phpBB3',
	'adm_page_cb' => 'Gestion des styles Connectix Boards',
	'adm_page_reviews' => 'Gestion des sites analysés',
	'adm_page_battles' => 'Gestion des résultats de duels',
	'adm_page_gimp' => 'Gestion des tutoriels GIMP',

	//Page d'accueil
	'adm_home_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/index.php">ici</a> pour retourner à l’accueil du site',

	//Éléments communs aux différentes pages
	'adm_edit' => 'Modifier',
	'adm_delete' => 'Supprimer',
	'adm_date' => 'Date',
	'adm_img' => 'Image',
	'adm_link' => 'Lien',
	'adm_send' => 'Envoyer',
	'adm_cancel' => 'Annuler',
	'adm_desc' => 'Caractéristiques',
	'adm_author' => 'Auteur',
	'adm_type' => 'Type',
	'adm_version' => 'Version',
	'adm_preview' => 'Aperçu',
	'adm_yes' => 'Oui',
	'adm_yes_check' => 'oui',
	'adm_no' => 'Non',
	'adm_no_check' => 'no',

	//Pages Battles
	'adm_battles_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/battles.php">ici</a> pour retourner aux résultats',
	'adm_battles_no_pic' => 'L’image pour le résultat de duel est manquant ! Cliquez <a href="ajouter_battle.php">ici</a> pour retourner au formulaire.',
	'adm_battles_added' => 'Le résultat a bien été ajouté !',
	'adm_battles_edited' => 'Le résultat sélectionné a bien été mis à jour !',
	'adm_battles_deleted' => 'Le résultat sélectionné a bien été retiré de la liste !',
	'adm_battles_add' => 'Ajouter un battle',
	'adm_battles_add_url' => 'ajouter_battle',
	'adm_battles_list_url' => 'liste_battles',
	'adm_battles_opponents' => 'Adversaires',
	'adm_battles_winner' => 'Gagnant',
	'adm_battles_heading' => 'Ajout/Édition d’un résultat de duel',
	'adm_battles_image' => 'Création du vainqueur',

	//Pages Analyses
	'adm_reviews_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/analyses.php">ici</a> pour retourner au classement',
	'adm_reviews_added' => 'Le site analysé a bien été ajouté !',
	'adm_reviews_edited' => 'Le site analysé sélectionné a bien été mis à jour !',
	'adm_reviews_deleted' => 'Le site analysé sélectionné a bien été retiré du classement !',
	'adm_reviews_add' => 'Ajouter un site analysé',
	'adm_reviews_add_url' => 'ajouter_analyse',
	'adm_reviews_list_url' => 'liste_analyses',
	'adm_reviews_site' => 'Site analysé',
	'adm_reviews_admin' => 'Admin du site',
	'adm_reviews_reviewer' => 'Analyste',
	'adm_reviews_result' => 'Note',
	'adm_reviews_heading' => 'Ajout/Édition d’un site analysé',
	'adm_reviews_siteurl' => 'URL du site',
	'adm_reviews_sitename' => 'Nom du site analysé',
	'adm_reviews_siteadmin' => 'Admin du site analysé',

	//Commun aux pages des styles
	'adm_style_added' => 'Le style a bien été ajouté’!',
	'adm_style_edited' => 'Le style a bien été mis à jour’!',
	'adm_style_deleted' => 'Le style a bien été retiré de la liste’!',
	'adm_style_name' => 'Nom du style',
	'adm_style_author' => 'Auteur du style',
	'adm_style_lang' => 'Langue du style',
	'adm_style_desc' => 'Description',
	'adm_style_zip' => 'Nom de l’archive ZIP',
	'adm_stile_notes' => 'Notes',
	'adm_style_support' => 'ID du sujet de support',
	'adm_style_demo' => 'Capture de démo',

	//Pages Forumactif
	'adm_fm_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/themes_fa.php">ici</a> pour retourner à la liste des styles FA.',
	'adm_fm_no_pic' => 'L’image pour le style est manquante ! Cliquez <a href="ajouter_fa.php">ici</a> pour retourner au formulaire.',
	'adm_fm_add' => 'Ajouter un thème FA',
	'adm_fm_add_url' => 'ajouter_fa',
	'adm_fm_list_url' => 'liste_fa',
	'adm_fm_heading' => 'Ajout/Édition d’un style FA',
	'adm_fm_style_css' => 'CSS personnalisé',
	'adm_fm_style_hitskin' => 'Lien hitskin',
	'adm_fm_zip_explain' => '(<em>L’archive doit être présent dans '.STYLE_ZIP_FM.')</em>',

	//Pages phpBB2
	'adm_phpbb2_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/themes_phpbb2.php">ici</a> pour retourner à la page des thèmes phpBB2.',
	'adm_phpbb2_no_pic' => 'L’image pour le template est manquante ! Cliquez <a href="ajouter_phpbb2.php">ici</a> pour retourner au formulaire.',
	'adm_phpbb2_add' => 'Ajouter un thème phpBB2',
	'adm_phpbb2_add_url' => 'ajouter_phpbb2',
	'adm_phpbb2_list_url' => 'liste_phpbb2',
	'adm_phpbb2_heading' => 'Ajout/Édition d’un style phpBB2',
	'adm_phpbb2_zip_explain' => '(<em>L’archive doit être présent dans '.STYLE_ZIP_PHPBB2.')</em>',

	//Pages phpBB3
	'adm_phpbb3_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/styles_phpbb3.php">ici</a> pour retourner à la page des styles phpBB3.',
	'adm_phpbb3_no_pic' => 'L’image pour le style est manquante ! Cliquez <a href="ajouter_phpbb3.php">ici</a> pour retourner au formulaire.',
	'adm_phpbb3_add' => 'Ajouter un style phpBB3',
	'adm_phpbb3_add_url' => 'ajouter_phpbb3',
	'adm_phpbb3_list_url' => 'liste_phpbb3',
	'adm_phpbb3_base' => 'Base',
	'adm_phpbb3_heading' => 'Ajout/Édition d’un style phpBB3',
	'adm_phpbb3_style_based' => 'Basé sur',
	'adm_phpbb3_style_html' => 'Fichiers HTML modifiés',
	'adm_phpbb3_zip_explain' => '(<em>L’archive doit être présent dans '.STYLE_ZIP_PHPBB3.')</em>',

	//Pages Connectix Boards
	'adm_cb_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/skins_cb.php">ici</a> pour retourner à la liste des styles CB.',
	'adm_cb_no_pic' => 'L’image pour le skin est manquante ! Cliquez <a href="ajouter_cb.php">ici</a> pour retourner au formulaire.',
	'adm_cb_add' => 'Ajouter un skin CB',
	'adm_cb_add_url' => 'ajouter_cb',
	'adm_cb_list_url' => 'liste_cb',
	'adm_cb_heading' => 'Ajout/Édition d’un skin CB',
	'adm_cb_zip_explain' => '(<em>L’archive doit être présent dans '.STYLE_ZIP_CB.')</em>',

	//Pages tutos GIMP
	'adm_gimp_noaccess' => '<strong>Désolé, mais vous n’êtes pas autorisé à accéder à cette partie !</strong><br />Cliquez <a href="../fr/tutogimp.php">ici</a> pour retourner au classement.',
	'adm_gimp_no_pic' => 'L’icone pour le tutoriel est manquant ! Cliquez <a href="ajouter_tutogimp.php">ici</a> pour revenir au formulaire.',
	'adm_gimp_added' => 'Le tutoriel a bien été ajouté !',
	'adm_gimp_edited' => 'Le tutoriel sélectionné a bien été mis à jour !',
	'adm_gimp_deleted' => 'Le tutoriel sélectionné a bien été retiré de la liste !',
	'adm_gimp_add' => 'Ajouter un tutoriel GIMP',
	'adm_gimp_add_url' => 'ajouter_tutogimp',
	'adm_gimp_list_url' => 'liste_tutogimp',
	'adm_gimp_tutorial' => 'Tutoriel',
	'adm_gimp_heading' => 'Ajout/Édition d’un tutoriel GIMP',
	'adm_gimp_topicid' => 'ID du sujet',
	'adm_gimp_topicid_explain' => '<em>(Se trouve dans l’URL du sujet sur le forum)</em>',
	'adm_gimp_icon' => 'Icône',
	'adm_gimp_icon_explain' => '<em>(Image représentant le tutoriel)</em>',
	'adm_gimp_desc' => 'Description du tuto',
	'adm_gimp_category' => 'Catégorie',
	'adm_gimp_level' => 'Niveau',
	'adm_gimp_version' => 'Version de GIMP',

	//Messages d'erreur et de notices
	'adm_no_access' => 'Accès refusé !',
	'adm_no_data' => 'Aucune donnée à afficher !',
	'adm_bad_transfer' => 'Une erreur est survenue lors du transfert de l’image',
	'adm_file_too_big' => 'Le fichier est trop gros !',
	'adm_bad_extension' => 'Extension incorrecte !',
	'adm_transfer_success' => 'Transfert réussi !',
	'adm_bad_chmod' => 'Le dossier demandé n’existe pas ou son CHMOD ne permet pas l’écriture !<br />Voici le fichier que vous tentez de transférer :',
	'adm_upload_failed' => 'L’envoi a échoué car au moins un critère n’est pas rempli !',
	'adm_no_file' => 'Aucun fichier !',
	'adm_field_opp1_empty' => 'Le champ <strong>Adversaire 1</strong> est vide !',
	'adm_field_opp2_empty' => 'Le champ <strong>Adversaire 2</strong> est vide !',
	'adm_field_win_empty' => 'Le champ <strong>Gagnant</strong> est vide !',
	'adm_field_url_wrong' => 'L’URL que vous avez entrée pour le site analysé est incorrecte ! Elle doit commencer par <strong>http://</strong> ou <strong>https://</strong>',
	'adm_field_sitename_short' => 'Le nom du site doit contenir au moins 2 caractères !',
	'adm_field_admin_empty' => 'Le champ <strong>Admin du site</strong> est vide !',
	'adm_field_reviewer_empty' => 'Le champ <strong>Analyste</strong> est vide !',
	'adm_field_result_wrongtype' => 'La note <strong>doit</strong> être un nombre ou une décimale et être inférieure ou égale à 100% !',
	'adm_field_result_empty' => 'Le champ <strong>Note</strong> est vide !',
	'adm_field_stylename_short' => 'Le nom du style doit contenir au moins 3 caractères !',
	'adm_field_styleauthor_empty' => 'Le champ <strong>Auteur</strong> est vide !',
	'adm_field_stylecompat_wrong' => 'Format incorrect ! Le numéro de version doit être écrit sous le format <strong>X.X.X</strong> ou <strong>X.X.X-RCX</strong> ou <strong>X.X.X-PLX</strong> et chaque nombre peut compter un ou deux chiffre.',
	'adm_field_stylelang_short' => 'La langue du style doit contenir au moins 2 caractères !',
	'adm_field_styledesc_short' => 'La description du style doit contenir au moins 5 caractères !',
	'adm_field_stylezip_wrong' => 'Le nom du fichier doit contenir au moins un caractère et se terminer par <strong>.zip</strong> !',
	'adm_field_stylenotes_short' => 'Les notes sur le style doivent contenir au moins 5 caractères !',
	'adm_field_styletype_wrong' => 'Le choix de la version de Forumactif est erroné !',
	'adm_field_stylecss_wrong' => 'Vous devez spécifier si ce style contient un style CSS personnalisé ou non',
	'adm_field_stylebase_wrong' => 'La base sélectionnée est erronée !',
	'adm_field_stylehtml_short' => 'Les fichiers HTML modifiés doit contenir au moins 4 caractères !',
	'adm_field_gimptopic_wrong' => 'L’ID du topic <strong>doit</strong> être un nombre entier !',
	'adm_field_gimpdesc_short' => 'La description du tutoriel doit contenir au moins 5 caractères !',
	'adm_field_gimptype_short' => 'Le type de tuto doit contenir au moins 5 caractères !',
	'adm_field_gimplevel_wrong' => 'Le niveau entré est erroné !',
	'adm_field_gimpversion_wrong' => 'La version entrée est erronée !'
*/
);
