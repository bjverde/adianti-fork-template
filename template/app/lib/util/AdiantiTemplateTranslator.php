<?php
use Adianti\Core\AdiantiCoreTranslator;

/**
 * AdiantiTemplateTranslator
 *
 * @version    8.3
 * @package    util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class AdiantiTemplateTranslator
{
    private static $instance; // singleton instance
    private $lang;            // target language
    private $messages;
    private $sourceMessages;
    
    /**
     * Class Constructor
     */
    private function __construct()
    {
        $this->messages = [];
        $this->messages['en'] = [];
        $this->messages['pt'] = [];
        $this->messages['es'] = [];
        $this->messages['it'] = [];
        $this->messages['de'] = [];
        $this->messages['fr'] = [];
        
        $this->messages['en'][] = 'File not found';
        $this->messages['pt'][] = 'Arquivo não encontrado';
        $this->messages['es'][] = 'Archivo no encontrado';
        $this->messages['it'][] = 'File non trovato';
        $this->messages['de'][] = 'Datei nicht gefunden';
        $this->messages['fr'][] = 'Fichier non trouvé';
        
        $this->messages['en'][] = 'Search';
        $this->messages['pt'][] = 'Buscar';
        $this->messages['es'][] = 'Buscar';
        $this->messages['it'][] = 'Cerca';
        $this->messages['de'][] = 'Suchen';
        $this->messages['fr'][] = 'Rechercher';
        
        $this->messages['en'][] = 'Register';
        $this->messages['pt'][] = 'Cadastrar';
        $this->messages['es'][] = 'Registrar';
        $this->messages['it'][] = 'Registrati';
        $this->messages['de'][] = 'Registrieren';
        $this->messages['fr'][] = 'S’inscrire';
        
        $this->messages['en'][] = 'Record saved';
        $this->messages['pt'][] = 'Registro salvo';
        $this->messages['es'][] = 'Registro guardado';
        $this->messages['it'][] = 'Record salvato';
        $this->messages['de'][] = 'Datensatz gespeichert';
        $this->messages['fr'][] = 'Enregistrement sauvegardé';
        
        $this->messages['en'][] = 'Do you really want to delete ?';
        $this->messages['pt'][] = 'Deseja realmente excluir ?';
        $this->messages['es'][] = 'Deseas realmente eliminar ?';
        $this->messages['it'][] = 'Vuoi davvero eliminare?';
        $this->messages['de'][] = 'Möchten Sie wirklich löschen?';
        $this->messages['fr'][] = 'Voulez-vous vraiment supprimer ?';
        
        $this->messages['en'][] = 'Record deleted';
        $this->messages['pt'][] = 'Registro excluído';
        $this->messages['es'][] = 'Registro eliminado';
        $this->messages['it'][] = 'Record eliminato';
        $this->messages['de'][] = 'Datensatz gelöscht';
        $this->messages['fr'][] = 'Enregistrement supprimé';
        
        $this->messages['en'][] = 'Function';
        $this->messages['pt'][] = 'Função';
        $this->messages['es'][] = 'Función';
        $this->messages['it'][] = 'Funzione';
        $this->messages['de'][] = 'Funktion';
        $this->messages['fr'][] = 'Fonction';
        
        $this->messages['en'][] = 'Table';
        $this->messages['pt'][] = 'Tabela';
        $this->messages['es'][] = 'Tabla';
        $this->messages['it'][] = 'Tabella';
        $this->messages['de'][] = 'Tabelle';
        $this->messages['fr'][] = 'Table';
        
        $this->messages['en'][] = 'Tool';
        $this->messages['pt'][] = 'Ferramenta';
        $this->messages['es'][] = 'Herramienta';
        $this->messages['it'][] = 'Strumento';
        $this->messages['de'][] = 'Werkzeug';
        $this->messages['fr'][] = 'Outil';
        
        $this->messages['en'][] = 'Data';
        $this->messages['pt'][] = 'Dados';
        $this->messages['es'][] = 'Datos';
        $this->messages['it'][] = 'Dati';
        $this->messages['de'][] = 'Daten';
        $this->messages['fr'][] = 'Données';
        
        $this->messages['en'][] = 'Open';
        $this->messages['pt'][] = 'Abrir';
        $this->messages['es'][] = 'Abrir';
        $this->messages['it'][] = 'Apri';
        $this->messages['de'][] = 'Öffnen';
        $this->messages['fr'][] = 'Ouvrir';
        
        $this->messages['en'][] = 'New';
        $this->messages['pt'][] = 'Novo';
        $this->messages['es'][] = 'Nuevo';
        $this->messages['it'][] = 'Nuovo';
        $this->messages['de'][] = 'Neu';
        $this->messages['fr'][] = 'Nouveau';
        
        $this->messages['en'][] = 'Save';
        $this->messages['pt'][] = 'Salvar';
        $this->messages['es'][] = 'Guardar';
        $this->messages['it'][] = 'Salva';
        $this->messages['de'][] = 'Speichern';
        $this->messages['fr'][] = 'Sauvegarder';
        
        $this->messages['en'][] = 'Find';
        $this->messages['pt'][] = 'Buscar';
        $this->messages['es'][] = 'Buscar';
        $this->messages['it'][] = 'Trova';
        $this->messages['de'][] = 'Finden';
        $this->messages['fr'][] = 'Trouver';
        
        $this->messages['en'][] = 'Edit';
        $this->messages['pt'][] = 'Editar';
        $this->messages['es'][] = 'Modificar';
        $this->messages['it'][] = 'Modifica';
        $this->messages['de'][] = 'Bearbeiten';
        $this->messages['fr'][] = 'Modifier';
        
        $this->messages['en'][] = 'Delete';
        $this->messages['pt'][] = 'Excluir';
        $this->messages['es'][] = 'Eliminar';
        $this->messages['it'][] = 'Elimina';
        $this->messages['de'][] = 'Löschen';
        $this->messages['fr'][] = 'Supprimer';
        
        $this->messages['en'][] = 'Cancel';
        $this->messages['pt'][] = 'Cancelar';
        $this->messages['es'][] = 'Cancelar';
        $this->messages['it'][] = 'Annulla';
        $this->messages['de'][] = 'Abbrechen';
        $this->messages['fr'][] = 'Annuler';
        
        $this->messages['en'][] = 'Yes';
        $this->messages['pt'][] = 'Sim';
        $this->messages['es'][] = 'Sí';
        $this->messages['it'][] = 'Sì';
        $this->messages['de'][] = 'Ja';
        $this->messages['fr'][] = 'Oui';
        
        $this->messages['en'][] = 'No';
        $this->messages['pt'][] = 'Não';
        $this->messages['es'][] = 'No';
        $this->messages['it'][] = 'No';
        $this->messages['de'][] = 'Nein';
        $this->messages['fr'][] = 'Non';
        
        $this->messages['en'][] = 'January';
        $this->messages['pt'][] = 'Janeiro';
        $this->messages['es'][] = 'Enero';
        $this->messages['it'][] = 'Gennaio';
        $this->messages['de'][] = 'Januar';
        $this->messages['fr'][] = 'Janvier';
        
        $this->messages['en'][] = 'February';
        $this->messages['pt'][] = 'Fevereiro';
        $this->messages['es'][] = 'Febrero';
        $this->messages['it'][] = 'Febbraio';
        $this->messages['de'][] = 'Februar';
        $this->messages['fr'][] = 'Février';
        
        $this->messages['en'][] = 'March';
        $this->messages['pt'][] = 'Março';
        $this->messages['es'][] = 'Marzo';
        $this->messages['it'][] = 'Marzo';
        $this->messages['de'][] = 'März';
        $this->messages['fr'][] = 'Mars';
        
        $this->messages['en'][] = 'April';
        $this->messages['pt'][] = 'Abril';
        $this->messages['es'][] = 'Abril';
        $this->messages['it'][] = 'Aprile';
        $this->messages['de'][] = 'April';
        $this->messages['fr'][] = 'Avril';
        
        $this->messages['en'][] = 'May';
        $this->messages['pt'][] = 'Maio';
        $this->messages['es'][] = 'Mayo';
        $this->messages['it'][] = 'Maggio';
        $this->messages['de'][] = 'Mai';
        $this->messages['fr'][] = 'Mai';
        
        $this->messages['en'][] = 'June';
        $this->messages['pt'][] = 'Junho';
        $this->messages['es'][] = 'Junio';
        $this->messages['it'][] = 'Giugno';
        $this->messages['de'][] = 'Juni';
        $this->messages['fr'][] = 'Juin';
        
        $this->messages['en'][] = 'July';
        $this->messages['pt'][] = 'Julho';
        $this->messages['es'][] = 'Julio';
        $this->messages['it'][] = 'Luglio';
        $this->messages['de'][] = 'Juli';
        $this->messages['fr'][] = 'Juillet';
        
        $this->messages['en'][] = 'August';
        $this->messages['pt'][] = 'Agosto';
        $this->messages['es'][] = 'Agosto';
        $this->messages['it'][] = 'Agosto';
        $this->messages['de'][] = 'August';
        $this->messages['fr'][] = 'Août';
        
        $this->messages['en'][] = 'September';
        $this->messages['pt'][] = 'Setembro';
        $this->messages['es'][] = 'Septiembre';
        $this->messages['it'][] = 'Settembre';
        $this->messages['de'][] = 'September';
        $this->messages['fr'][] = 'Septembre';
        
        $this->messages['en'][] = 'October';
        $this->messages['pt'][] = 'Outubro';
        $this->messages['es'][] = 'Octubre';
        $this->messages['it'][] = 'Ottobre';
        $this->messages['de'][] = 'Oktober';
        $this->messages['fr'][] = 'Octobre';
        
        $this->messages['en'][] = 'November';
        $this->messages['pt'][] = 'Novembro';
        $this->messages['es'][] = 'Noviembre';
        $this->messages['it'][] = 'Novembre';
        $this->messages['de'][] = 'November';
        $this->messages['fr'][] = 'Novembre';
        

        $this->messages['en'][] = 'December';
        $this->messages['pt'][] = 'Dezembro';
        $this->messages['es'][] = 'Diciembre';
        $this->messages['it'][] = 'Dicembre';
        $this->messages['de'][] = 'Dezember';
        $this->messages['fr'][] = 'Décembre';
        
        $this->messages['en'][] = 'Today';
        $this->messages['pt'][] = 'Hoje';
        $this->messages['es'][] = 'Hoy';
        $this->messages['it'][] = 'Oggi';
        $this->messages['de'][] = 'Heute';
        $this->messages['fr'][] = 'Aujourd’hui';
        
        $this->messages['en'][] = 'Close';
        $this->messages['pt'][] = 'Fechar';
        $this->messages['es'][] = 'Cerrar';
        $this->messages['it'][] = 'Chiudi';
        $this->messages['de'][] = 'Schließen';
        $this->messages['fr'][] = 'Fermer';
        
        $this->messages['en'][] = 'The field ^1 can not be less than ^2 characters';
        $this->messages['pt'][] = 'O campo ^1 não pode ter menos de ^2 caracteres';
        $this->messages['es'][] = 'El campo ^1 no puede tener menos de ^2 caracteres';
        $this->messages['it'][] = 'Il campo ^1 non può contenere meno di ^2 caratteri';
        $this->messages['de'][] = 'Das Feld ^1 darf nicht weniger als ^2 Zeichen enthalten';
        $this->messages['fr'][] = 'Le champ ^1 ne peut pas contenir moins de ^2 caractères';
        
        $this->messages['en'][] = 'The field ^1 can not be greater than ^2 characters';
        $this->messages['pt'][] = 'O campo ^1 não pode ter mais de ^2 caracteres';
        $this->messages['es'][] = 'El campo ^1 no puede tener mas de ^2 caracteres';
        $this->messages['it'][] = 'Il campo ^1 non può contenere più di ^2 caratteri';
        $this->messages['de'][] = 'Das Feld ^1 darf nicht mehr als ^2 Zeichen enthalten';
        $this->messages['fr'][] = 'Le champ ^1 ne peut pas contenir plus de ^2 caractères';
        
        $this->messages['en'][] = 'The field ^1 can not be less than ^2';
        $this->messages['pt'][] = 'O campo ^1 não pode ser menor que ^2';
        $this->messages['es'][] = 'El campo ^1 no puede ser menor que ^2';
        $this->messages['it'][] = 'Il campo ^1 non può essere inferiore a ^2';
        $this->messages['de'][] = 'Das Feld ^1 darf nicht kleiner als ^2 sein';
        $this->messages['fr'][] = 'Le champ ^1 ne peut pas être inférieur à ^2';
        
        $this->messages['en'][] = 'The field ^1 can not be greater than ^2';
        $this->messages['pt'][] = 'O campo ^1 não pode ser maior que ^2';
        $this->messages['es'][] = 'El campo ^1 no puede ser mayor que ^2';
        $this->messages['it'][] = 'Il campo ^1 non può essere superiore a ^2';
        $this->messages['de'][] = 'Das Feld ^1 darf nicht größer als ^2 sein';
        $this->messages['fr'][] = 'Le champ ^1 ne peut pas être supérieur à ^2';
        
        $this->messages['en'][] = 'The field ^1 is required';
        $this->messages['pt'][] = 'O campo ^1 é obrigatório';
        $this->messages['es'][] = 'El campo ^1 es obligatorio';
        $this->messages['it'][] = 'Il campo ^1 è obbligatorio';
        $this->messages['de'][] = 'Das Feld ^1 ist erforderlich';
        $this->messages['fr'][] = 'Le champ ^1 est obligatoire';
        
        $this->messages['en'][] = 'The field ^1 has not a valid CNPJ';
        $this->messages['pt'][] = 'O campo ^1 não contém um CNPJ válido';
        $this->messages['es'][] = 'El campo ^1 no contiene un CNPJ válido';
        $this->messages['it'][] = 'Il campo ^1 non contiene un CNPJ valido';
        $this->messages['de'][] = 'Das Feld ^1 enthält keine gültige CNPJ';
        $this->messages['fr'][] = 'Le champ ^1 ne contient pas un CNPJ valide';
        
        $this->messages['en'][] = 'The field ^1 has not a valid CPF';
        $this->messages['pt'][] = 'O campo ^1 não contém um CPF válido';
        $this->messages['es'][] = 'El campo ^1 no contiene un CPF válido';
        $this->messages['it'][] = 'Il campo ^1 non contiene un CPF valido';
        $this->messages['de'][] = 'Das Feld ^1 enthält keine gültige CPF';
        $this->messages['fr'][] = 'Le champ ^1 ne contient pas un CPF valide';
        
        $this->messages['en'][] = 'The field ^1 contains an invalid e-mail';
        $this->messages['pt'][] = 'O campo ^1 contém um e-mail inválido';
        $this->messages['es'][] = 'El campo ^1 contiene um e-mail inválido';
        $this->messages['it'][] = 'Il campo ^1 contiene un’e-mail non valida';
        $this->messages['de'][] = 'Das Feld ^1 enthält eine ungültige E-Mail';
        $this->messages['fr'][] = 'Le champ ^1 contient un e-mail invalide';
        
        $this->messages['en'][] = 'Permission denied';
        $this->messages['pt'][] = 'Permissão negada';
        $this->messages['es'][] = 'Permiso denegado';
        $this->messages['it'][] = 'Permesso negato';
        $this->messages['de'][] = 'Zugriff verweigert';
        $this->messages['fr'][] = 'Permission refusée';
        
        $this->messages['en'][] = 'Generate';
        $this->messages['pt'][] = 'Gerar';
        $this->messages['es'][] = 'Generar';
        $this->messages['it'][] = 'Genera';
        $this->messages['de'][] = 'Generieren';
        $this->messages['fr'][] = 'Générer';
        
        $this->messages['en'][] = 'List';
        $this->messages['pt'][] = 'Listar';
        $this->messages['es'][] = 'Listar';
        $this->messages['it'][] = 'Elenco';
        $this->messages['de'][] = 'Liste';
        $this->messages['fr'][] = 'Liste';
        
        

        $this->messages['en'][] = 'Wrong password';
        $this->messages['pt'][] = 'Senha errada';
        $this->messages['es'][] = 'Contraseña incorrecta';
        $this->messages['it'][] = 'Password errata';
        $this->messages['de'][] = 'Falsches Passwort';
        $this->messages['fr'][] = 'Mot de passe incorrect';

        $this->messages['en'][] = 'User not found';
        $this->messages['pt'][] = 'Usuário não encontrado';
        $this->messages['es'][] = 'Usuário no encontrado';
        $this->messages['it'][] = 'Utente non trovato';
        $this->messages['de'][] = 'Benutzer nicht gefunden';
        $this->messages['fr'][] = 'Utilisateur non trouvé';

        $this->messages['en'][] = 'User';
        $this->messages['pt'][] = 'Usuário';
        $this->messages['es'][] = 'Usuário';
        $this->messages['it'][] = 'Utente';
        $this->messages['de'][] = 'Benutzer';
        $this->messages['fr'][] = 'Utilisateur';

        $this->messages['en'][] = 'Users';
        $this->messages['pt'][] = 'Usuários';
        $this->messages['es'][] = 'Usuários';
        $this->messages['it'][] = 'Utenti';
        $this->messages['de'][] = 'Benutzer';
        $this->messages['fr'][] = 'Utilisateurs';

        $this->messages['en'][] = 'Password';
        $this->messages['pt'][] = 'Senha';
        $this->messages['es'][] = 'Contraseña';
        $this->messages['it'][] = 'Password';
        $this->messages['de'][] = 'Passwort';
        $this->messages['fr'][] = 'Mot de passe';

        $this->messages['en'][] = 'Login';
        $this->messages['pt'][] = 'Login';
        $this->messages['es'][] = 'Login';
        $this->messages['it'][] = 'Login';
        $this->messages['de'][] = 'Login';
        $this->messages['fr'][] = 'Connexion';

        $this->messages['en'][] = 'Name';
        $this->messages['pt'][] = 'Nome';
        $this->messages['es'][] = 'Nombre';
        $this->messages['it'][] = 'Nome';
        $this->messages['de'][] = 'Name';
        $this->messages['fr'][] = 'Nom';

        $this->messages['en'][] = 'Group';
        $this->messages['pt'][] = 'Grupo';
        $this->messages['es'][] = 'Grupo';
        $this->messages['it'][] = 'Gruppo';
        $this->messages['de'][] = 'Gruppe';
        $this->messages['fr'][] = 'Groupe';

        $this->messages['en'][] = 'Groups';
        $this->messages['pt'][] = 'Grupos';
        $this->messages['es'][] = 'Grupos';
        $this->messages['it'][] = 'Gruppi';
        $this->messages['de'][] = 'Gruppen';
        $this->messages['fr'][] = 'Groupes';

        $this->messages['en'][] = 'Program';
        $this->messages['pt'][] = 'Programa';
        $this->messages['es'][] = 'Programa';
        $this->messages['it'][] = 'Programma';
        $this->messages['de'][] = 'Programm';
        $this->messages['fr'][] = 'Programme';

        $this->messages['en'][] = 'Programs';
        $this->messages['pt'][] = 'Programas';
        $this->messages['es'][] = 'Programas';
        $this->messages['it'][] = 'Programmi';
        $this->messages['de'][] = 'Programme';
        $this->messages['fr'][] = 'Programmes';

        $this->messages['en'][] = 'Back to the listing';
        $this->messages['pt'][] = 'Voltar para a listagem';
        $this->messages['es'][] = 'Volver al listado';
        $this->messages['it'][] = 'Torna all’elenco';
        $this->messages['de'][] = 'Zurück zur Liste';
        $this->messages['fr'][] = 'Retour à la liste';

        $this->messages['en'][] = 'Controller';
        $this->messages['pt'][] = 'Classe de controle';
        $this->messages['es'][] = 'Classe de control';
        $this->messages['it'][] = 'Classe di controllo';
        $this->messages['de'][] = 'Steuerklasse';
        $this->messages['fr'][] = 'Classe de contrôle';

        $this->messages['en'][] = 'Email';
        $this->messages['pt'][] = 'Email';
        $this->messages['es'][] = 'Email';
        $this->messages['it'][] = 'Email';
        $this->messages['de'][] = 'E-Mail';
        $this->messages['fr'][] = 'Email';

        $this->messages['en'][] = 'Record Updated';
        $this->messages['pt'][] = 'Registro atualizado';
        $this->messages['es'][] = 'Registro actualizado';
        $this->messages['it'][] = 'Record aggiornato';
        $this->messages['de'][] = 'Datensatz aktualisiert';
        $this->messages['fr'][] = 'Enregistrement mis à jour';

        $this->messages['en'][] = 'Password confirmation';
        $this->messages['pt'][] = 'Confirma senha';
        $this->messages['es'][] = 'Confirme contraseña';
        $this->messages['it'][] = 'Conferma password';
        $this->messages['de'][] = 'Passwort bestätigen';
        $this->messages['fr'][] = 'Confirmation du mot de passe';

        $this->messages['en'][] = 'Front page';
        $this->messages['pt'][] = 'Tela inicial';
        $this->messages['es'][] = 'Pantalla inicial';
        $this->messages['it'][] = 'Pagina iniziale';
        $this->messages['de'][] = 'Startseite';
        $this->messages['fr'][] = 'Page d’accueil';

                
        $this->messages['en'][] = 'Page name';
        $this->messages['pt'][] = 'Nome da Tela';
        $this->messages['es'][] = 'Nombre da la Pantalla';
        $this->messages['it'][] = 'Nome della pagina';
        $this->messages['de'][] = 'Seitenname';
        $this->messages['fr'][] = 'Nom de la page';

        $this->messages['en'][] = 'The passwords do not match';
        $this->messages['pt'][] = 'As senhas não conferem';
        $this->messages['es'][] = 'Las contraseñas no conciden';
        $this->messages['it'][] = 'Le password non corrispondono';
        $this->messages['de'][] = 'Die Passwörter stimmen nicht überein';
        $this->messages['fr'][] = 'Les mots de passe ne correspondent pas';

        $this->messages['en'][] = 'Log in';
        $this->messages['pt'][] = 'Entrar';
        $this->messages['es'][] = 'Ingresar';
        $this->messages['it'][] = 'Accedi';
        $this->messages['de'][] = 'Anmelden';
        $this->messages['fr'][] = 'Se connecter';

        $this->messages['en'][] = 'Date';
        $this->messages['pt'][] = 'Data';
        $this->messages['es'][] = 'Fecha';
        $this->messages['it'][] = 'Data';
        $this->messages['de'][] = 'Datum';
        $this->messages['fr'][] = 'Date';

        $this->messages['en'][] = 'Columns';
        $this->messages['pt'][] = 'Colunas';
        $this->messages['es'][] = 'Columnas';
        $this->messages['it'][] = 'Colonne';
        $this->messages['de'][] = 'Spalten';
        $this->messages['fr'][] = 'Colonnes';

        $this->messages['en'][] = 'Column';
        $this->messages['pt'][] = 'Coluna';
        $this->messages['es'][] = 'Columna';
        $this->messages['it'][] = 'Colonna';
        $this->messages['de'][] = 'Spalte';
        $this->messages['fr'][] = 'Colonne';

        $this->messages['en'][] = 'Operation';
        $this->messages['pt'][] = 'Operação';
        $this->messages['es'][] = 'Operación';
        $this->messages['it'][] = 'Operazione';
        $this->messages['de'][] = 'Operation';
        $this->messages['fr'][] = 'Opération';

        $this->messages['en'][] = 'Old value';
        $this->messages['pt'][] = 'Valor antigo';
        $this->messages['es'][] = 'Valor anterior';
        $this->messages['it'][] = 'Valore precedente';
        $this->messages['de'][] = 'Alter Wert';
        $this->messages['fr'][] = 'Ancienne valeur';

        $this->messages['en'][] = 'New value';
        $this->messages['pt'][] = 'Valor novo';
        $this->messages['es'][] = 'Valor nuevo';
        $this->messages['it'][] = 'Nuovo valore';
        $this->messages['de'][] = 'Neuer Wert';
        $this->messages['fr'][] = 'Nouvelle valeur';

        $this->messages['en'][] = 'Database';
        $this->messages['pt'][] = 'Banco de dados';
        $this->messages['es'][] = 'Base de datos';
        $this->messages['it'][] = 'Database';
        $this->messages['de'][] = 'Datenbank';
        $this->messages['fr'][] = 'Base de données';

        $this->messages['en'][] = 'Profile';
        $this->messages['pt'][] = 'Perfil';
        $this->messages['es'][] = 'Perfil';
        $this->messages['it'][] = 'Profilo';
        $this->messages['de'][] = 'Profil';
        $this->messages['fr'][] = 'Profil';

        $this->messages['en'][] = 'Change password';
        $this->messages['pt'][] = 'Mudar senha';
        $this->messages['es'][] = 'Cambiar contraseña';
        $this->messages['it'][] = 'Cambia password';
        $this->messages['de'][] = 'Passwort ändern';
        $this->messages['fr'][] = 'Changer le mot de passe';

        $this->messages['en'][] = 'Results';
        $this->messages['pt'][] = 'Resultados';
        $this->messages['es'][] = 'Resultados';
        $this->messages['it'][] = 'Risultati';
        $this->messages['de'][] = 'Ergebnisse';
        $this->messages['fr'][] = 'Résultats';

        $this->messages['en'][] = 'Invalid command';
        $this->messages['pt'][] = 'Comando inválido';
        $this->messages['es'][] = 'Comando inválido';
        $this->messages['it'][] = 'Comando non valido';
        $this->messages['de'][] = 'Ungültiger Befehl';
        $this->messages['fr'][] = 'Commande invalide';

        $this->messages['en'][] = '^1 records shown';
        $this->messages['pt'][] = '^1 registros exibidos';
        $this->messages['es'][] = '^1 registros exhibidos';
        $this->messages['it'][] = '^1 record mostrati';
        $this->messages['de'][] = '^1 Datensätze angezeigt';
        $this->messages['fr'][] = '^1 enregistrements affichés';

        $this->messages['en'][] = 'Administration';
        $this->messages['pt'][] = 'Administração';
        $this->messages['es'][] = 'Administración';
        $this->messages['it'][] = 'Amministrazione';
        $this->messages['de'][] = 'Verwaltung';
        $this->messages['fr'][] = 'Administration';

        $this->messages['en'][] = 'SQL Panel';
        $this->messages['pt'][] = 'Painel SQL';
        $this->messages['es'][] = 'Panel SQL';
        $this->messages['it'][] = 'Pannello SQL';
        $this->messages['de'][] = 'SQL-Panel';
        $this->messages['fr'][] = 'Panneau SQL';

        $this->messages['en'][] = 'Access Log';
        $this->messages['pt'][] = 'Log de acesso';
        $this->messages['es'][] = 'Log de acceso';
        $this->messages['it'][] = 'Registro accessi';
        $this->messages['de'][] = 'Zugriffsprotokoll';
        $this->messages['fr'][] = 'Journal d’accès';

        $this->messages['en'][] = 'Change Log';
        $this->messages['pt'][] = 'Log de alterações';
        $this->messages['es'][] = 'Log de modificaciones';
        $this->messages['it'][] = 'Registro modifiche';
        $this->messages['de'][] = 'Änderungsprotokoll';
        $this->messages['fr'][] = 'Journal des modifications';

        $this->messages['en'][] = 'SQL Log';
        $this->messages['pt'][] = 'Log de SQL';
        $this->messages['es'][] = 'Log de SQL';
        $this->messages['it'][] = 'Registro SQL';
        $this->messages['de'][] = 'SQL-Protokoll';
        $this->messages['fr'][] = 'Journal SQL';

        $this->messages['en'][] = 'Clear form';
        $this->messages['pt'][] = 'Limpar formulário';
        $this->messages['es'][] = 'Limpiar formulário';
        $this->messages['it'][] = 'Pulisci modulo';
        $this->messages['de'][] = 'Formular löschen';
        $this->messages['fr'][] = 'Effacer le formulaire';

        $this->messages['en'][] = 'Send';
        $this->messages['pt'][] = 'Enviar';
        $this->messages['es'][] = 'Enviar';
        $this->messages['it'][] = 'Invia';
        $this->messages['de'][] = 'Senden';
        $this->messages['fr'][] = 'Envoyer';

        $this->messages['en'][] = 'Message';
        $this->messages['pt'][] = 'Mensagem';
        $this->messages['es'][] = 'Mensaje';
        $this->messages['it'][] = 'Messaggio';
        $this->messages['de'][] = 'Nachricht';
        $this->messages['fr'][] = 'Message';

        $this->messages['en'][] = 'Messages';
        $this->messages['pt'][] = 'Mensagens';
        $this->messages['es'][] = 'Mensajes';
        $this->messages['it'][] = 'Messaggi';
        $this->messages['de'][] = 'Nachrichten';
        $this->messages['fr'][] = 'Messages';

        $this->messages['en'][] = 'Subject';
        $this->messages['pt'][] = 'Assunto';
        $this->messages['es'][] = 'Asunto';
        $this->messages['it'][] = 'Oggetto';
        $this->messages['de'][] = 'Betreff';
        $this->messages['fr'][] = 'Sujet';


        $this->messages['en'][] = 'Message sent successfully';
        $this->messages['pt'][] = 'Mensagem enviada com sucesso';
        $this->messages['es'][] = 'Mensaje enviada exitosamente';
        $this->messages['it'][] = 'Messaggio inviato con successo';
        $this->messages['de'][] = 'Nachricht erfolgreich gesendet';
        $this->messages['fr'][] = 'Message envoyé avec succès';

        $this->messages['en'][] = 'Check as read';
        $this->messages['pt'][] = 'Marcar como lida';
        $this->messages['es'][] = 'Marcar como leído';
        $this->messages['it'][] = 'Segna come letto';
        $this->messages['de'][] = 'Als gelesen markieren';
        $this->messages['fr'][] = 'Marquer comme lu';

        $this->messages['en'][] = 'Check as unread';
        $this->messages['pt'][] = 'Marcar como não lida';
        $this->messages['es'][] = 'Marcar como no leído';
        $this->messages['it'][] = 'Segna come non letto';
        $this->messages['de'][] = 'Als ungelesen markieren';
        $this->messages['fr'][] = 'Marquer comme non lu';

        $this->messages['en'][] = 'Action';
        $this->messages['pt'][] = 'Ação';
        $this->messages['es'][] = 'Acción';
        $this->messages['it'][] = 'Azione';
        $this->messages['de'][] = 'Aktion';
        $this->messages['fr'][] = 'Action';

        $this->messages['en'][] = 'From';
        $this->messages['pt'][] = 'De';
        $this->messages['es'][] = 'Origen';
        $this->messages['it'][] = 'Da';
        $this->messages['de'][] = 'Von';
        $this->messages['fr'][] = 'De';

        $this->messages['en'][] = 'Checked';
        $this->messages['pt'][] = 'Verificado';
        $this->messages['es'][] = 'Verificado';
        $this->messages['it'][] = 'Verificato';
        $this->messages['de'][] = 'Geprüft';
        $this->messages['fr'][] = 'Vérifié';

        $this->messages['en'][] = 'Object ^1 not found in ^2';
        $this->messages['pt'][] = 'Objeto ^1 não encontrado em ^2';
        $this->messages['es'][] = 'Objeto ^1 no encontrado en ^2';
        $this->messages['it'][] = 'Oggetto ^1 non trovato in ^2';
        $this->messages['de'][] = 'Objekt ^1 nicht gefunden in ^2';
        $this->messages['fr'][] = 'Objet ^1 non trouvé dans ^2';

        $this->messages['en'][] = 'Notification';
        $this->messages['pt'][] = 'Notificação';
        $this->messages['es'][] = 'Notificación';
        $this->messages['it'][] = 'Notifica';
        $this->messages['de'][] = 'Benachrichtigung';
        $this->messages['fr'][] = 'Notification';

        $this->messages['en'][] = 'Notifications';
        $this->messages['pt'][] = 'Notificações';
        $this->messages['es'][] = 'Notificaciones';
        $this->messages['it'][] = 'Notifiche';
        $this->messages['de'][] = 'Benachrichtigungen';
        $this->messages['fr'][] = 'Notifications';

        $this->messages['en'][] = 'Categories';
        $this->messages['pt'][] = 'Categorias';
        $this->messages['es'][] = 'Categorias';
        $this->messages['it'][] = 'Categorie';
        $this->messages['de'][] = 'Kategorien';
        $this->messages['fr'][] = 'Catégories';

        $this->messages['en'][] = 'Send document';
        $this->messages['pt'][] = 'Enviar documentos';
        $this->messages['es'][] = 'Enviar documentos';
        $this->messages['it'][] = 'Invia documento';
        $this->messages['de'][] = 'Dokument senden';
        $this->messages['fr'][] = 'Envoyer le document';

        $this->messages['en'][] = 'My documents';
        $this->messages['pt'][] = 'Meus documentos';
        $this->messages['es'][] = 'Mis documentos';
        $this->messages['it'][] = 'I miei documenti';
        $this->messages['de'][] = 'Meine Dokumente';
        $this->messages['fr'][] = 'Mes documents';

        $this->messages['en'][] = 'Shared with me';
        $this->messages['pt'][] = 'Compartilhados comigo';
        $this->messages['es'][] = 'Compartidos conmigo';
        $this->messages['it'][] = 'Condivisi con me';
        $this->messages['de'][] = 'Mit mir geteilt';
        $this->messages['fr'][] = 'Partagés avec moi';

        $this->messages['en'][] = 'Document';
        $this->messages['pt'][] = 'Documento';
        $this->messages['es'][] = 'Documento';
        $this->messages['it'][] = 'Documento';
        $this->messages['de'][] = 'Dokument';
        $this->messages['fr'][] = 'Document';

        $this->messages['en'][] = 'File';
        $this->messages['pt'][] = 'Arquivo';
        $this->messages['es'][] = 'Archivo';
        $this->messages['it'][] = 'File';
        $this->messages['de'][] = 'Datei';
        $this->messages['fr'][] = 'Fichier';

        $this->messages['en'][] = 'Title';
        $this->messages['pt'][] = 'Tí­tulo';
        $this->messages['es'][] = 'Título';
        $this->messages['it'][] = 'Titolo';
        $this->messages['de'][] = 'Titel';
        $this->messages['fr'][] = 'Titre';

        $this->messages['en'][] = 'Description';
        $this->messages['pt'][] = 'Descrição';
        $this->messages['es'][] = 'Descripción';
        $this->messages['it'][] = 'Descrizione';
        $this->messages['de'][] = 'Beschreibung';
        $this->messages['fr'][] = 'Description';

        $this->messages['en'][] = 'Category';
        $this->messages['pt'][] = 'Categoria';
        $this->messages['es'][] = 'Categoria';
        $this->messages['it'][] = 'Categoria';
        $this->messages['de'][] = 'Kategorie';
        $this->messages['fr'][] = 'Catégorie';

        $this->messages['en'][] = 'Submission date';
        $this->messages['pt'][] = 'Data de submissão';
        $this->messages['es'][] = 'Fecha de envio';
        $this->messages['it'][] = 'Data di invio';
        $this->messages['de'][] = 'Einreichungsdatum';
        $this->messages['fr'][] = 'Date de soumission';

        $this->messages['en'][] = 'Archive date';
        $this->messages['pt'][] = 'Data de arquivamento';
        $this->messages['es'][] = 'Fecha de archivamiento';
        $this->messages['it'][] = 'Data di archiviazione';
        $this->messages['de'][] = 'Archivierungsdatum';
        $this->messages['fr'][] = 'Date d’archivage';

        $this->messages['en'][] = 'Upload';
        $this->messages['pt'][] = 'Upload';
        $this->messages['es'][] = 'Upload';
        $this->messages['it'][] = 'Carica';
        $this->messages['de'][] = 'Hochladen';
        $this->messages['fr'][] = 'Téléverser';

        $this->messages['en'][] = 'Download';
        $this->messages['pt'][] = 'Download';
        $this->messages['es'][] = 'Download';
        $this->messages['it'][] = 'Scarica';
        $this->messages['de'][] = 'Herunterladen';
        $this->messages['fr'][] = 'Télécharger';

        $this->messages['en'][] = 'Next';
        $this->messages['pt'][] = 'Próximo';
        $this->messages['es'][] = 'Siguiente';
        $this->messages['it'][] = 'Avanti';
        $this->messages['de'][] = 'Weiter';
        $this->messages['fr'][] = 'Suivant';

        $this->messages['en'][] = 'Documents';
        $this->messages['pt'][] = 'Documentos';
        $this->messages['es'][] = 'Documentos';
        $this->messages['it'][] = 'Documenti';
        $this->messages['de'][] = 'Dokumente';
        $this->messages['fr'][] = 'Documents';

        $this->messages['en'][] = 'Permission';
        $this->messages['pt'][] = 'Permissão';
        $this->messages['es'][] = 'Permiso';
        $this->messages['it'][] = 'Permesso';
        $this->messages['de'][] = 'Berechtigung';
        $this->messages['fr'][] = 'Permission';

        $this->messages['en'][] = 'Unit';
        $this->messages['pt'][] = 'Unidade';
        $this->messages['es'][] = 'Unidad';
        $this->messages['it'][] = 'Unità';
        $this->messages['de'][] = 'Einheit';
        $this->messages['fr'][] = 'Unité';

        $this->messages['en'][] = 'Units';
        $this->messages['pt'][] = 'Unidades';
        $this->messages['es'][] = 'Unidades';
        $this->messages['it'][] = 'Unità';
        $this->messages['de'][] = 'Einheiten';
        $this->messages['fr'][] = 'Unités';

        $this->messages['en'][] = 'Add';
        $this->messages['pt'][] = 'Adiciona';
        $this->messages['es'][] = 'Agrega';
        $this->messages['it'][] = 'Aggiungi';
        $this->messages['de'][] = 'Hinzufügen';
        $this->messages['fr'][] = 'Ajouter';

        $this->messages['en'][] = 'Active';
        $this->messages['pt'][] = 'Ativo';
        $this->messages['es'][] = 'Activo';
        $this->messages['it'][] = 'Attivo';
        $this->messages['de'][] = 'Aktiv';
        $this->messages['fr'][] = 'Actif';



        $this->messages['en'][] = 'Activate/Deactivate';
        $this->messages['pt'][] = 'Ativar/desativar';
        $this->messages['es'][] = 'Activar/desactivar';
        $this->messages['it'][] = 'Attiva/Disattiva';
        $this->messages['de'][] = 'Aktivieren/Deaktivieren';
        $this->messages['fr'][] = 'Activer/Désactiver';

        $this->messages['en'][] = 'Inactive user';
        $this->messages['pt'][] = 'Usuário inativo';
        $this->messages['es'][] = 'Usuário desactivado';
        $this->messages['it'][] = 'Utente inattivo';
        $this->messages['de'][] = 'Inaktiver Benutzer';
        $this->messages['fr'][] = 'Utilisateur inactif';

        $this->messages['en'][] = 'Send message';
        $this->messages['pt'][] = 'Envia mensagem';
        $this->messages['es'][] = 'Envia mensaje';
        $this->messages['it'][] = 'Invia messaggio';
        $this->messages['de'][] = 'Nachricht senden';
        $this->messages['fr'][] = 'Envoyer un message';

        $this->messages['en'][] = 'Read messages';
        $this->messages['pt'][] = 'Ler mensagens';
        $this->messages['es'][] = 'Leer mensaje';
        $this->messages['it'][] = 'Leggi messaggi';
        $this->messages['de'][] = 'Nachrichten lesen';
        $this->messages['fr'][] = 'Lire les messages';

        $this->messages['en'][] = 'An user with this login is already registered';
        $this->messages['pt'][] = 'Um usuário já está cadastrado com este login';
        $this->messages['es'][] = 'Un usuário ya está registrado con este login';
        $this->messages['it'][] = 'Un utente con questo login è già registrato';
        $this->messages['de'][] = 'Ein Benutzer mit diesem Login ist bereits registriert';
        $this->messages['fr'][] = 'Un utilisateur avec ce login est déjà enregistré';

        $this->messages['en'][] = 'Access Stats';
        $this->messages['pt'][] = 'Estatí­sticas de acesso';
        $this->messages['es'][] = 'Estadisticas de acceso';
        $this->messages['it'][] = 'Statistiche di accesso';
        $this->messages['de'][] = 'Zugriffsstatistiken';
        $this->messages['fr'][] = 'Statistiques d’accès';

        $this->messages['en'][] = 'Accesses';
        $this->messages['pt'][] = 'Acessos';
        $this->messages['es'][] = 'Accesos';
        $this->messages['it'][] = 'Accessi';
        $this->messages['de'][] = 'Zugriffe';
        $this->messages['fr'][] = 'Accès';

        $this->messages['en'][] = 'Preferences';
        $this->messages['pt'][] = 'Preferências';
        $this->messages['es'][] = 'Preferencias';
        $this->messages['it'][] = 'Preferenze';
        $this->messages['de'][] = 'Einstellungen';
        $this->messages['fr'][] = 'Préférences';

        $this->messages['en'][] = 'Mail from';
        $this->messages['pt'][] = 'E-mail de origem';
        $this->messages['es'][] = 'E-mail de origen';
        $this->messages['it'][] = 'Email da';
        $this->messages['de'][] = 'E-Mail von';
        $this->messages['fr'][] = 'Email de';

        $this->messages['en'][] = 'SMTP Auth';
        $this->messages['pt'][] = 'Autentica SMTP';
        $this->messages['es'][] = 'Autentica SMTP';
        $this->messages['it'][] = 'Autenticazione SMTP';
        $this->messages['de'][] = 'SMTP-Authentifizierung';
        $this->messages['fr'][] = 'Authentification SMTP';

        $this->messages['en'][] = 'SMTP Host';
        $this->messages['pt'][] = 'Host SMTP';
        $this->messages['es'][] = 'Host SMTP';
        $this->messages['it'][] = 'Host SMTP';
        $this->messages['de'][] = 'SMTP-Host';
        $this->messages['fr'][] = 'Hôte SMTP';

        $this->messages['en'][] = 'SMTP Port';
        $this->messages['pt'][] = 'Porta SMTP';
        $this->messages['es'][] = 'Puerta SMTP';
        $this->messages['it'][] = 'Porta SMTP';
        $this->messages['de'][] = 'SMTP-Port';
        $this->messages['fr'][] = 'Port SMTP';

        $this->messages['en'][] = 'SMTP User';
        $this->messages['pt'][] = 'Usuário SMTP';
        $this->messages['es'][] = 'Usuário SMTP';
        $this->messages['it'][] = 'Utente SMTP';
        $this->messages['de'][] = 'SMTP-Benutzer';
        $this->messages['fr'][] = 'Utilisateur SMTP';

        $this->messages['en'][] = 'SMTP Pass';
        $this->messages['pt'][] = 'Senha SMTP';
        $this->messages['es'][] = 'Contraseña SMTP';
        $this->messages['it'][] = 'Password SMTP';
        $this->messages['de'][] = 'SMTP-Passwort';
        $this->messages['fr'][] = 'Mot de passe SMTP';

        $this->messages['en'][] = 'Ticket';
        $this->messages['pt'][] = 'Ticket';
        $this->messages['es'][] = 'Ticket';
        $this->messages['it'][] = 'Ticket';
        $this->messages['de'][] = 'Ticket';
        $this->messages['fr'][] = 'Ticket';

        $this->messages['en'][] = 'Open ticket';
        $this->messages['pt'][] = 'Abrir ticket';
        $this->messages['es'][] = 'Abrir ticket';
        $this->messages['it'][] = 'Apri ticket';
        $this->messages['de'][] = 'Ticket öffnen';
        $this->messages['fr'][] = 'Ouvrir un ticket';


        $this->messages['en'][] = 'Support mail';
        $this->messages['pt'][] = 'Email de suporte';
        $this->messages['es'][] = 'Email de soporte';
        $this->messages['it'][] = 'Email di supporto';
        $this->messages['de'][] = 'Support-E-Mail';
        $this->messages['fr'][] = 'Email de support';

        $this->messages['en'][] = 'Day';
        $this->messages['pt'][] = 'Dia';
        $this->messages['es'][] = 'Dia';
        $this->messages['it'][] = 'Giorno';
        $this->messages['de'][] = 'Tag';
        $this->messages['fr'][] = 'Jour';

        $this->messages['en'][] = 'Folders';
        $this->messages['pt'][] = 'Pastas';
        $this->messages['es'][] = 'Carpetas';
        $this->messages['it'][] = 'Cartelle';
        $this->messages['de'][] = 'Ordner';
        $this->messages['fr'][] = 'Dossiers';

        $this->messages['en'][] = 'Compose';
        $this->messages['pt'][] = 'Escrever';
        $this->messages['es'][] = 'Componer';
        $this->messages['it'][] = 'Scrivi';
        $this->messages['de'][] = 'Verfassen';
        $this->messages['fr'][] = 'Composer';

        $this->messages['en'][] = 'Inbox';
        $this->messages['pt'][] = 'Entrada';
        $this->messages['es'][] = 'Entrada';
        $this->messages['it'][] = 'Posta in arrivo';
        $this->messages['de'][] = 'Posteingang';
        $this->messages['fr'][] = 'Boîte de réception';

        $this->messages['en'][] = 'Sent';
        $this->messages['pt'][] = 'Enviados';
        $this->messages['es'][] = 'Enviados';
        $this->messages['it'][] = 'Inviati';
        $this->messages['de'][] = 'Gesendet';
        $this->messages['fr'][] = 'Envoyés';

        $this->messages['en'][] = 'Archived';
        $this->messages['pt'][] = 'Arquivados';
        $this->messages['es'][] = 'Archivados';
        $this->messages['it'][] = 'Archiviati';
        $this->messages['de'][] = 'Archiviert';
        $this->messages['fr'][] = 'Archivés';

        $this->messages['en'][] = 'Archive';
        $this->messages['pt'][] = 'Arquivar';
        $this->messages['es'][] = 'Archivar';
        $this->messages['it'][] = 'Archivia';
        $this->messages['de'][] = 'Archivieren';
        $this->messages['fr'][] = 'Archiver';

        $this->messages['en'][] = 'Recover';
        $this->messages['pt'][] = 'Recuperar';
        $this->messages['es'][] = 'Recuperar';
        $this->messages['it'][] = 'Recupera';
        $this->messages['de'][] = 'Wiederherstellen';
        $this->messages['fr'][] = 'Récupérer';

        $this->messages['en'][] = 'Value';
        $this->messages['pt'][] = 'Valor';
        $this->messages['es'][] = 'Valor';
        $this->messages['it'][] = 'Valore';
        $this->messages['de'][] = 'Wert';
        $this->messages['fr'][] = 'Valeur';

        $this->messages['en'][] = 'View all';
        $this->messages['pt'][] = 'Ver todos';
        $this->messages['es'][] = 'Ver todos';
        $this->messages['it'][] = 'Vedi tutto';
        $this->messages['de'][] = 'Alle anzeigen';
        $this->messages['fr'][] = 'Voir tout';

        $this->messages['en'][] = 'Reload';
        $this->messages['pt'][] = 'Recarregar';
        $this->messages['es'][] = 'Recargar';
        $this->messages['it'][] = 'Ricarica';
        $this->messages['de'][] = 'Neu laden';
        $this->messages['fr'][] = 'Recharger';

        $this->messages['en'][] = 'Back';
        $this->messages['pt'][] = 'Voltar';
        $this->messages['es'][] = 'Volver';
        $this->messages['it'][] = 'Indietro';
        $this->messages['de'][] = 'Zurück';
        $this->messages['fr'][] = 'Retour';

        $this->messages['en'][] = 'Clear';
        $this->messages['pt'][] = 'Limpar';
        $this->messages['es'][] = 'Limpiar';
        $this->messages['it'][] = 'Pulisci';
        $this->messages['de'][] = 'Löschen';
        $this->messages['fr'][] = 'Effacer';

        $this->messages['en'][] = 'View';
        $this->messages['pt'][] = 'Visualizar';
        $this->messages['es'][] = 'Visualizar';
        $this->messages['it'][] = 'Visualizza';
        $this->messages['de'][] = 'Anzeigen';
        $this->messages['fr'][] = 'Voir';

        $this->messages['en'][] = 'No records found';
        $this->messages['pt'][] = 'Nenhum registro foi encontrado';
        $this->messages['es'][] = 'Ningun registro fue encontrado';
        $this->messages['it'][] = 'Nessun record trovato';
        $this->messages['de'][] = 'Keine Einträge gefunden';
        $this->messages['fr'][] = 'Aucun enregistrement trouvé';

        $this->messages['en'][] = 'Value';
        $this->messages['pt'][] = 'Valor';
        $this->messages['es'][] = 'Valor';
        $this->messages['it'][] = 'Valore';
        $this->messages['de'][] = 'Wert';
        $this->messages['fr'][] = 'Valeur';

        $this->messages['en'][] = 'User';
        $this->messages['pt'][] = 'Usuário';
        $this->messages['es'][] = 'Usuário';
        $this->messages['it'][] = 'Utente';
        $this->messages['de'][] = 'Benutzer';
        $this->messages['fr'][] = 'Utilisateur';

        $this->messages['en'][] = 'Password';
        $this->messages['pt'][] = 'Senha';
        $this->messages['es'][] = 'Contraseña';
        $this->messages['it'][] = 'Password';
        $this->messages['de'][] = 'Passwort';
        $this->messages['fr'][] = 'Mot de passe';

        $this->messages['en'][] = 'Port';
        $this->messages['pt'][] = 'Porta';
        $this->messages['es'][] = 'Puerta';
        $this->messages['it'][] = 'Porta';
        $this->messages['de'][] = 'Port';
        $this->messages['fr'][] = 'Port';

        $this->messages['en'][] = 'Main unit';
        $this->messages['pt'][] = 'Unidade principal';
        $this->messages['es'][] = 'Unidad principal';
        $this->messages['it'][] = 'Unità principale';
        $this->messages['de'][] = 'Haupteinheit';
        $this->messages['fr'][] = 'Unité principale';

        $this->messages['en'][] = 'Time';
        $this->messages['pt'][] = 'Hora';
        $this->messages['es'][] = 'Hora';
        $this->messages['it'][] = 'Ora';
        $this->messages['de'][] = 'Zeit';
        $this->messages['fr'][] = 'Heure';

        $this->messages['en'][] = 'Type';
        $this->messages['pt'][] = 'Tipo';
        $this->messages['es'][] = 'Tipo';
        $this->messages['it'][] = 'Tipo';
        $this->messages['de'][] = 'Typ';
        $this->messages['fr'][] = 'Type';


        $this->messages['en'][] = 'Failed to read error log (^1)';
        $this->messages['pt'][] = 'Falha ao ler o log de erros (^1)';
        $this->messages['es'][] = 'Falla al leer el log de errores (^1)';
        $this->messages['it'][] = 'Impossibile leggere il log degli errori (^1)';
        $this->messages['fr'][] = 'Échec de la lecture du journal des erreurs (^1)';
        $this->messages['de'][] = 'Fehler beim Lesen des Fehlerprotokolls (^1)';

        $this->messages['en'][] = 'Error log (^1) is not writable by web server user, so the messages may be incomplete';
        $this->messages['pt'][] = 'O log de erros (^1) não permite escrita pelo usuário web, assim as mensagens devem estar incompletas';
        $this->messages['es'][] = 'El log de errores (^1) no permite escritura por el usuário web, así­ que los mensajes deben estar incompletos';
        $this->messages['it'][] = 'Il log degli errori (^1) non è scrivibile dall’utente web, quindi i messaggi potrebbero essere incompleti';
        $this->messages['fr'][] = 'Le journal des erreurs (^1) n’est pas accessible en écriture par l’utilisateur web, les messages peuvent donc être incomplets';
        $this->messages['de'][] = 'Das Fehlerprotokoll (^1) ist für den Webserver-Benutzer nicht beschreibbar, daher können Nachrichten unvollständig sein';

        $this->messages['en'][] = 'Check the owner of the log file. He must be the same as the web user (usually www-data, www, etc)';
        $this->messages['pt'][] = 'Revise o proprietário do arquivo de log. Ele deve ser igual ao usuário web (geralmente www-data, www, etc)';
        $this->messages['es'][] = 'Revise el propietario del archivo de log. Debe ser igual al usuário web (generalmente www-data, www, etc)';
        $this->messages['it'][] = 'Controlla il proprietario del file di log. Deve essere lo stesso dell’utente web (di solito www-data, www, ecc.)';
        $this->messages['fr'][] = 'Vérifiez le propriétaire du fichier de log. Il doit être identique à l’utilisateur web (généralement www-data, www, etc.)';
        $this->messages['de'][] = 'Überprüfen Sie den Eigentümer der Protokolldatei. Er muss mit dem Webbenutzer identisch sein (normalerweise www-data, www usw.)';

        $this->messages['en'][] = 'Error log is empty or has not been configured correctly. Define the error log file, setting <b>error_log</b> at php.ini';
        $this->messages['pt'][] = 'Log de erros está vazio ou não foi configurado corretamente. Defina o arquivo de log de erros, configurando <b>error_log</b> no php.ini';
        $this->messages['es'][] = 'Log de errores está vacio o no fue configurado correctamente. Defina el archivo de log de errores, configurando <b>error_log</b> en el php.ini';
        $this->messages['it'][] = 'Il log degli errori è vuoto o non è stato configurato correttamente. Definire il file di log degli errori impostando <b>error_log</b> in php.ini';
        $this->messages['fr'][] = 'Le journal des erreurs est vide ou mal configuré. Définissez le fichier de log des erreurs avec <b>error_log</b> dans php.ini';
        $this->messages['de'][] = 'Das Fehlerprotokoll ist leer oder wurde nicht korrekt konfiguriert. Definieren Sie die Fehlerprotokolldatei mit <b>error_log</b> in php.ini';

        $this->messages['en'][] = 'Errors are not being logged. Please turn <b>log_errors = On</b> at php.ini';
        $this->messages['pt'][] = 'Erros não estão sendo registrados. Por favor, mude <b>log_errors = On</b> no php.ini';
        $this->messages['es'][] = 'Errores no estan siendo registrados. Por favor, modifique <b>log_errors = On</b> en el php.ini';
        $this->messages['it'][] = 'Gli errori non vengono registrati. Attivare <b>log_errors = On</b> in php.ini';
        $this->messages['fr'][] = 'Les erreurs ne sont pas enregistrées. Veuillez activer <b>log_errors = On</b> dans php.ini';
        $this->messages['de'][] = 'Fehler werden nicht protokolliert. Bitte aktivieren Sie <b>log_errors = On</b> in php.ini';

        $this->messages['en'][] = 'Errors are not currently being displayd because the <b>display_errors</b> is set to Off in php.ini';
        $this->messages['pt'][] = 'Erros não estão atualmente sendo exibidos por que <b>display_errors</b> está configurado para Off no php.ini';
        $this->messages['es'][] = 'Errores no estan actualmente siendo mostrados porque <b>display_errors</b> está configurado para Off en el php.ini';
        $this->messages['it'][] = 'Gli errori non vengono attualmente visualizzati perché <b>display_errors</b> è impostato su Off in php.ini';
        $this->messages['fr'][] = 'Les erreurs ne sont pas affichées actuellement car <b>display_errors</b> est désactivé dans php.ini';
        $this->messages['de'][] = 'Fehler werden derzeit nicht angezeigt, da <b>display_errors</b> in php.ini auf Off gesetzt ist';

        $this->messages['en'][] = 'This configuration is usually recommended for production, not development purposes';
        $this->messages['pt'][] = 'Esta configuração normalmente é recomendada para produção, não para o propósito de desenvolvimento';
        $this->messages['es'][] = 'Esta configuración normalmente es recomendada para producción, no para el propósito de desarrollo';
        $this->messages['it'][] = 'Questa configurazione è solitamente consigliata per la produzione, non per lo sviluppo';
        $this->messages['fr'][] = 'Cette configuration est généralement recommandée pour la production, pas pour le développement';
        $this->messages['de'][] = 'Diese Konfiguration wird normalerweise für die Produktion empfohlen, nicht für die Entwicklung';

        $this->messages['en'][] = 'The php.ini current location is <b>^1</b>';
        $this->messages['pt'][] = 'A localização atual do php.ini é <b>^1</b>';
        $this->messages['es'][] = 'La ubicación actual del php.ini es <b>^1</b>';
        $this->messages['it'][] = 'La posizione attuale di php.ini è <b>^1</b>';
        $this->messages['fr'][] = 'L’emplacement actuel de php.ini est <b>^1</b>';
        $this->messages['de'][] = 'Der aktuelle Speicherort von php.ini ist <b>^1</b>';

        $this->messages['en'][] = 'The error log current location is <b>^1</b>';
        $this->messages['pt'][] = 'A localização atual do log de erros é <b>^1</b>';
        $this->messages['es'][] = 'La ubicación actual del log de errores es <b>^1</b>';
        $this->messages['it'][] = 'La posizione attuale del log degli errori è <b>^1</b>';
        $this->messages['fr'][] = 'L’emplacement actuel du journal des erreurs est <b>^1</b>';
        $this->messages['de'][] = 'Der aktuelle Speicherort des Fehlerprotokolls ist <b>^1</b>';

        $this->messages['en'][] = 'PHP Log';
        $this->messages['pt'][] = 'Log do PHP';
        $this->messages['es'][] = 'Log del PHP';
        $this->messages['it'][] = 'Log PHP';
        $this->messages['fr'][] = 'Journal PHP';
        $this->messages['de'][] = 'PHP-Protokoll';

        $this->messages['en'][] = 'Database explorer';
        $this->messages['pt'][] = 'Database explorer';
        $this->messages['es'][] = 'Database explorer';
        $this->messages['it'][] = 'Esploratore database';
        $this->messages['fr'][] = 'Explorateur de base de données';
        $this->messages['de'][] = 'Datenbank-Explorer';

        $this->messages['en'][] = 'Tables';
        $this->messages['pt'][] = 'Tabelas';
        $this->messages['es'][] = 'Tablas';
        $this->messages['it'][] = 'Tabelle';
        $this->messages['fr'][] = 'Tables';
        $this->messages['de'][] = 'Tabellen';

        $this->messages['en'][] = 'Module';
        $this->messages['pt'][] = 'Módulo';
        $this->messages['es'][] = 'Módulo';
        $this->messages['it'][] = 'Modulo';
        $this->messages['fr'][] = 'Module';
        $this->messages['de'][] = 'Modul';

        $this->messages['en'][] = 'Directory';
        $this->messages['pt'][] = 'Diretório';
        $this->messages['es'][] = 'Directório';
        $this->messages['it'][] = 'Directory';
        $this->messages['fr'][] = 'Répertoire';
        $this->messages['de'][] = 'Verzeichnis';


        $this->messages['en'][] = 'Source code';
        $this->messages['pt'][] = 'Código-fonte';
        $this->messages['es'][] = 'Código-fuente';
        $this->messages['it'][] = 'Codice sorgente';
        $this->messages['fr'][] = 'Code source';
        $this->messages['de'][] = 'Quellcode';

        $this->messages['en'][] = 'Invalid return';
        $this->messages['pt'][] = 'Retorno inválido';
        $this->messages['es'][] = 'Retorno inválido';
        $this->messages['it'][] = 'Risposta non valida';
        $this->messages['fr'][] = 'Retour invalide';
        $this->messages['de'][] = 'Ungültige Rückgabe';

        $this->messages['en'][] = 'Page';
        $this->messages['pt'][] = 'Página';
        $this->messages['es'][] = 'Página';
        $this->messages['it'][] = 'Pagina';
        $this->messages['fr'][] = 'Page';
        $this->messages['de'][] = 'Seite';

        $this->messages['en'][] = 'Path';
        $this->messages['pt'][] = 'Diretório';
        $this->messages['es'][] = 'Directório';
        $this->messages['it'][] = 'Percorso';
        $this->messages['fr'][] = 'Chemin';
        $this->messages['de'][] = 'Pfad';

        $this->messages['en'][] = 'File';
        $this->messages['pt'][] = 'Arquivo';
        $this->messages['es'][] = 'Archivo';
        $this->messages['it'][] = 'File';
        $this->messages['fr'][] = 'Fichier';
        $this->messages['de'][] = 'Datei';

        $this->messages['en'][] = 'Photo';
        $this->messages['pt'][] = 'Foto';
        $this->messages['es'][] = 'Foto';
        $this->messages['it'][] = 'Foto';
        $this->messages['fr'][] = 'Photo';
        $this->messages['de'][] = 'Foto';

        $this->messages['en'][] = 'Reset password';
        $this->messages['pt'][] = 'Redefinir senha';
        $this->messages['es'][] = 'Cambiar contraseña';
        $this->messages['it'][] = 'Reimposta password';
        $this->messages['fr'][] = 'Réinitialiser le mot de passe';
        $this->messages['de'][] = 'Passwort zurücksetzen';

        $this->messages['en'][] = 'A new seed is required in the application.ini for security reasons';
        $this->messages['pt'][] = 'Uma nova seed é necessária no application.ini por motivos de segurança';
        $this->messages['es'][] = 'Una nueva seed es necesaria en application.ini por motivos de seguridad';
        $this->messages['it'][] = 'Un nuovo seed è richiesto in application.ini per motivi di sicurezza';
        $this->messages['fr'][] = 'Une nouvelle seed est requise dans application.ini pour des raisons de sécurité';
        $this->messages['de'][] = 'Ein neuer Seed ist in der application.ini aus Sicherheitsgründen erforderlich';

        $this->messages['en'][] = 'Password reset';
        $this->messages['pt'][] = 'Troca de senha';
        $this->messages['es'][] = 'Cambiar la contraseña';
        $this->messages['it'][] = 'Reimpostazione password';
        $this->messages['fr'][] = 'Réinitialisation du mot de passe';
        $this->messages['de'][] = 'Passwort zurücksetzen';

        $this->messages['en'][] = 'Token expired. This operation is not allowed';
        $this->messages['pt'][] = 'Token expirado. Esta operação não é permitida';
        $this->messages['es'][] = 'Token expirado. Esta operación no está permitida';
        $this->messages['it'][] = 'Token scaduto. Questa operazione non è consentita';
        $this->messages['fr'][] = 'Jeton expiré. Cette opération n’est pas autorisée';
        $this->messages['de'][] = 'Token abgelaufen. Diese Aktion ist nicht erlaubt';

        $this->messages['en'][] = 'The password has been changed';
        $this->messages['pt'][] = 'A senha foi alterada';
        $this->messages['es'][] = 'La contraseña fue modificada';
        $this->messages['it'][] = 'La password è stata modificata';
        $this->messages['fr'][] = 'Le mot de passe a été changé';
        $this->messages['de'][] = 'Das Passwort wurde geändert';

        $this->messages['en'][] = 'An user with this e-mail is already registered';
        $this->messages['pt'][] = 'Um usuário já está cadastrado com este e-mail';
        $this->messages['es'][] = 'Un usuário ya está registrado con este e-mail';
        $this->messages['it'][] = 'Un utente con questa e-mail è già registrato';
        $this->messages['fr'][] = 'Un utilisateur avec cet e-mail est déjà enregistré';
        $this->messages['de'][] = 'Ein Benutzer mit dieser E-Mail ist bereits registriert';

        $this->messages['en'][] = 'Invalid LDAP credentials';
        $this->messages['pt'][] = 'Credenciais LDAP erradas';
        $this->messages['es'][] = 'Credenciales LDAP incorrectas';
        $this->messages['it'][] = 'Credenziali LDAP non valide';
        $this->messages['fr'][] = 'Identifiants LDAP invalides';
        $this->messages['de'][] = 'Ungültige LDAP-Zugangsdaten';

        $this->messages['en'][] = 'Menu path';
        $this->messages['pt'][] = 'Caminho menu';
        $this->messages['es'][] = 'Dirección del menu';
        $this->messages['it'][] = 'Percorso del menu';
        $this->messages['fr'][] = 'Chemin du menu';
        $this->messages['de'][] = 'Menüpfad';

        $this->messages['en'][] = 'Icon';
        $this->messages['pt'][] = 'Ícone';
        $this->messages['es'][] = 'Icono';
        $this->messages['it'][] = 'Icona';
        $this->messages['fr'][] = 'Icône';
        $this->messages['de'][] = 'Symbol';


        $this->messages['en'][] = 'User registration';
        $this->messages['pt'][] = 'Cadastro de usuário';
        $this->messages['es'][] = 'Registro de usuário';
        $this->messages['it'][] = 'Registrazione utente';
        $this->messages['fr'][] = 'Inscription utilisateur';
        $this->messages['de'][] = 'Benutzerregistrierung';

        $this->messages['en'][] = 'The user registration is disabled';
        $this->messages['pt'][] = 'O cadastro de usuários está desabilitado';
        $this->messages['es'][] = 'El registro de usuários está desactivado';
        $this->messages['it'][] = 'La registrazione utente è disabilitata';
        $this->messages['fr'][] = 'L’inscription utilisateur est désactivée';
        $this->messages['de'][] = 'Die Benutzerregistrierung ist deaktiviert';

        $this->messages['en'][] = 'The password reset is disabled';
        $this->messages['pt'][] = 'A recuperação de senhas está desabilitada';
        $this->messages['es'][] = 'La recuperación de contraseña está desactivada';
        $this->messages['it'][] = 'Il ripristino della password è disabilitato';
        $this->messages['fr'][] = 'La réinitialisation du mot de passe est désactivée';
        $this->messages['de'][] = 'Das Zurücksetzen des Passworts ist deaktiviert';

        $this->messages['en'][] = 'Account created';
        $this->messages['pt'][] = 'Conta criada';
        $this->messages['es'][] = 'Cuenta creada';
        $this->messages['it'][] = 'Account creato';
        $this->messages['fr'][] = 'Compte créé';
        $this->messages['de'][] = 'Konto erstellt';

        $this->messages['en'][] = 'Create account';
        $this->messages['pt'][] = 'Criar conta';
        $this->messages['es'][] = 'Crear cuenta';
        $this->messages['it'][] = 'Crea account';
        $this->messages['fr'][] = 'Créer un compte';
        $this->messages['de'][] = 'Konto erstellen';

        $this->messages['en'][] = 'Authorization error';
        $this->messages['pt'][] = 'Erro de autorização';
        $this->messages['es'][] = 'Error de autorización';
        $this->messages['it'][] = 'Errore di autorizzazione';
        $this->messages['fr'][] = 'Erreur d’autorisation';
        $this->messages['de'][] = 'Autorisierungsfehler';

        $this->messages['en'][] = 'Exit';
        $this->messages['pt'][] = 'Sair';
        $this->messages['es'][] = 'Salir';
        $this->messages['it'][] = 'Esci';
        $this->messages['fr'][] = 'Quitter';
        $this->messages['de'][] = 'Beenden';

        $this->messages['en'][] = 'REST key not defined';
        $this->messages['pt'][] = 'Chave REST não definida';
        $this->messages['es'][] = 'Clave REST no definida';
        $this->messages['it'][] = 'Chiave REST non definita';
        $this->messages['fr'][] = 'Clé REST non définie';
        $this->messages['de'][] = 'REST-Schlüssel nicht definiert';

        $this->messages['en'][] = 'Local';
        $this->messages['pt'][] = 'Local';
        $this->messages['es'][] = 'Local';
        $this->messages['it'][] = 'Locale';
        $this->messages['fr'][] = 'Local';
        $this->messages['de'][] = 'Lokal';

        $this->messages['en'][] = 'Remote';
        $this->messages['pt'][] = 'Remoto';
        $this->messages['es'][] = 'Remoto';
        $this->messages['it'][] = 'Remoto';
        $this->messages['fr'][] = 'Distant';
        $this->messages['de'][] = 'Remote';

        $this->messages['en'][] = 'Success';
        $this->messages['pt'][] = 'Sucesso';
        $this->messages['es'][] = 'Éxito';
        $this->messages['it'][] = 'Successo';
        $this->messages['fr'][] = 'Succès';
        $this->messages['de'][] = 'Erfolg';

        $this->messages['en'][] = 'Error';
        $this->messages['pt'][] = 'Erro';
        $this->messages['es'][] = 'Error';
        $this->messages['it'][] = 'Errore';
        $this->messages['fr'][] = 'Erreur';
        $this->messages['de'][] = 'Fehler';

        $this->messages['en'][] = 'Status';
        $this->messages['pt'][] = 'Status';
        $this->messages['es'][] = 'Estado';
        $this->messages['it'][] = 'Stato';
        $this->messages['fr'][] = 'Statut';
        $this->messages['de'][] = 'Status';

        $this->messages['en'][] = 'Order';
        $this->messages['pt'][] = 'Ordenação';
        $this->messages['es'][] = 'Ordenación';
        $this->messages['it'][] = 'Ordinamento';
        $this->messages['fr'][] = 'Ordre';
        $this->messages['de'][] = 'Sortierung';

        $this->messages['en'][] = 'Label';
        $this->messages['pt'][] = 'Rótulo';
        $this->messages['es'][] = 'Etiqueta';
        $this->messages['it'][] = 'Etichetta';
        $this->messages['fr'][] = 'Libellé';
        $this->messages['de'][] = 'Bezeichnung';


        $this->messages['en'][] = 'Color';
        $this->messages['pt'][] = 'Cor';
        $this->messages['es'][] = 'Color';
        $this->messages['it'][] = 'Colore';
        $this->messages['fr'][] = 'Couleur';
        $this->messages['de'][] = 'Farbe';

        $this->messages['en'][] = 'Clone';
        $this->messages['pt'][] = 'Clonar';
        $this->messages['es'][] = 'Clonar';
        $this->messages['it'][] = 'Clona';
        $this->messages['fr'][] = 'Cloner';
        $this->messages['de'][] = 'Klonen';

        $this->messages['en'][] = 'Impersonation';
        $this->messages['pt'][] = 'Personificar';
        $this->messages['es'][] = 'Personificar';
        $this->messages['it'][] = 'Impersonificazione';
        $this->messages['fr'][] = 'Usurpation d’identité';
        $this->messages['de'][] = 'Identitätswechsel';

        $this->messages['en'][] = 'Impersonated';
        $this->messages['pt'][] = 'Personificado';
        $this->messages['es'][] = 'Personificado';
        $this->messages['it'][] = 'Impersonificato';
        $this->messages['fr'][] = 'Usurpé';
        $this->messages['de'][] = 'Imitiert';

        $this->messages['en'][] = 'Execution trace';
        $this->messages['pt'][] = 'Rastreamento da execução';
        $this->messages['es'][] = 'Rastreo de ejecución';
        $this->messages['it'][] = 'Traccia di esecuzione';
        $this->messages['fr'][] = 'Trace d’exécution';
        $this->messages['de'][] = 'Ausführungsverfolgung';

        $this->messages['en'][] = 'Session';
        $this->messages['pt'][] = 'Sessão';
        $this->messages['es'][] = 'Sesión';
        $this->messages['it'][] = 'Sessione';
        $this->messages['fr'][] = 'Session';
        $this->messages['de'][] = 'Sitzung';

        $this->messages['en'][] = 'Request Log';
        $this->messages['pt'][] = 'Log de request';
        $this->messages['es'][] = 'Log de request';
        $this->messages['it'][] = 'Log delle richieste';
        $this->messages['fr'][] = 'Journal des requêtes';
        $this->messages['de'][] = 'Anforderungsprotokoll';

        $this->messages['en'][] = 'Method';
        $this->messages['pt'][] = 'Método';
        $this->messages['es'][] = 'Método';
        $this->messages['it'][] = 'Metodo';
        $this->messages['fr'][] = 'Méthode';
        $this->messages['de'][] = 'Methode';

        $this->messages['en'][] = 'Request';
        $this->messages['pt'][] = 'Requisição';
        $this->messages['es'][] = 'Request';
        $this->messages['it'][] = 'Richiesta';
        $this->messages['fr'][] = 'Requête';
        $this->messages['de'][] = 'Anfrage';

        $this->messages['en'][] = 'Users by group';
        $this->messages['pt'][] = 'Usuários por grupo';
        $this->messages['es'][] = 'Usuarios por grupo';
        $this->messages['it'][] = 'Utenti per gruppo';
        $this->messages['fr'][] = 'Utilisateurs par groupe';
        $this->messages['de'][] = 'Benutzer nach Gruppe';

        $this->messages['en'][] = 'Count';
        $this->messages['pt'][] = 'Quantidade';
        $this->messages['es'][] = 'Cantidad';
        $this->messages['it'][] = 'Conteggio';
        $this->messages['fr'][] = 'Nombre';
        $this->messages['de'][] = 'Anzahl';

        $this->messages['en'][] = 'Users by unit';
        $this->messages['pt'][] = 'Usuários por unidade';
        $this->messages['es'][] = 'Usuarios por unidad';
        $this->messages['it'][] = 'Utenti per unità';
        $this->messages['fr'][] = 'Utilisateurs par unité';
        $this->messages['de'][] = 'Benutzer nach Einheit';

        $this->messages['en'][] = 'Save as PDF';
        $this->messages['pt'][] = 'Salvar como PDF';
        $this->messages['es'][] = 'Guardar como PDF';
        $this->messages['it'][] = 'Salva come PDF';
        $this->messages['fr'][] = 'Enregistrer en PDF';
        $this->messages['de'][] = 'Als PDF speichern';

        $this->messages['en'][] = 'Save as CSV';
        $this->messages['pt'][] = 'Salvar como CSV';
        $this->messages['es'][] = 'Guardar como CSV';
        $this->messages['it'][] = 'Salva come CSV';
        $this->messages['fr'][] = 'Enregistrer en CSV';
        $this->messages['de'][] = 'Als CSV speichern';

        $this->messages['en'][] = 'Save as XML';
        $this->messages['pt'][] = 'Salvar como XML';
        $this->messages['es'][] = 'Guardar como XML';
        $this->messages['it'][] = 'Salva come XML';
        $this->messages['fr'][] = 'Enregistrer en XML';
        $this->messages['de'][] = 'Als XML speichern';

        $this->messages['en'][] = 'Export';
        $this->messages['pt'][] = 'Exportar';
        $this->messages['es'][] = 'Exportar';
        $this->messages['it'][] = 'Esporta';
        $this->messages['fr'][] = 'Exporter';
        $this->messages['de'][] = 'Exportieren';

        $this->messages['en'][] = 'System information';
        $this->messages['pt'][] = 'Informações do sistema';
        $this->messages['es'][] = 'Informaciones del sistema';
        $this->messages['it'][] = 'Informazioni di sistema';
        $this->messages['fr'][] = 'Informations système';
        $this->messages['de'][] = 'Systeminformationen';

        $this->messages['en'][] = 'RAM Memory';
        $this->messages['pt'][] = 'Memória RAM';
        $this->messages['es'][] = 'Memória RAM';
        $this->messages['it'][] = 'Memoria RAM';
        $this->messages['fr'][] = 'Mémoire RAM';
        $this->messages['de'][] = 'RAM-Speicher';

        $this->messages['en'][] = 'Using/Total';
        $this->messages['pt'][] = 'Usando/Total';
        $this->messages['es'][] = 'Utilizando/Total';
        $this->messages['it'][] = 'Utilizzo/Totale';
        $this->messages['fr'][] = 'Utilisé/Total';
        $this->messages['de'][] = 'Verwendet/Insgesamt';


        $this->messages['en'][] = 'Free';
        $this->messages['pt'][] = 'Disponí­vel';
        $this->messages['es'][] = 'Disponible';
        $this->messages['it'][] = 'Disponibile';
        $this->messages['fr'][] = 'Disponible';
        $this->messages['de'][] = 'Frei';

        $this->messages['en'][] = 'Percentage used';
        $this->messages['pt'][] = 'Percentual usado';
        $this->messages['es'][] = 'Porcentaje utilizado';
        $this->messages['it'][] = 'Percentuale utilizzata';
        $this->messages['fr'][] = 'Pourcentage utilisé';
        $this->messages['de'][] = 'Prozentual verwendet';

        $this->messages['en'][] = 'CPU usage';
        $this->messages['pt'][] = 'Uso da CPU';
        $this->messages['es'][] = 'Uso de CPU';
        $this->messages['it'][] = 'Utilizzo CPU';
        $this->messages['fr'][] = 'Utilisation du CPU';
        $this->messages['de'][] = 'CPU-Auslastung';

        $this->messages['en'][] = 'Vendor';
        $this->messages['pt'][] = 'Fornecedor';
        $this->messages['es'][] = 'Proveedor';
        $this->messages['it'][] = 'Fornitore';
        $this->messages['fr'][] = 'Fournisseur';
        $this->messages['de'][] = 'Hersteller';

        $this->messages['en'][] = 'Model';
        $this->messages['pt'][] = 'Modelo';
        $this->messages['es'][] = 'Modelo';
        $this->messages['it'][] = 'Modello';
        $this->messages['fr'][] = 'Modèle';
        $this->messages['de'][] = 'Modell';

        $this->messages['en'][] = 'Current Frequency';
        $this->messages['pt'][] = 'Frequência atual';
        $this->messages['es'][] = 'Frecuencia actual';
        $this->messages['it'][] = 'Frequenza attuale';
        $this->messages['fr'][] = 'Fréquence actuelle';
        $this->messages['de'][] = 'Aktuelle Frequenz';

        $this->messages['en'][] = 'Webserver and Process';
        $this->messages['pt'][] = 'Servidor web e processos';
        $this->messages['es'][] = 'Servidor web y procesos';
        $this->messages['it'][] = 'Webserver e processi';
        $this->messages['fr'][] = 'Serveur web et processus';
        $this->messages['de'][] = 'Webserver und Prozesse';

        $this->messages['en'][] = 'Disk devices';
        $this->messages['pt'][] = 'Dispositivos de disco';
        $this->messages['es'][] = 'Dispositivos de disco';
        $this->messages['it'][] = 'Dispositivi disco';
        $this->messages['fr'][] = 'Périphériques disque';
        $this->messages['de'][] = 'Festplattengeräte';

        $this->messages['en'][] = 'Device';
        $this->messages['pt'][] = 'Dispositivo';
        $this->messages['es'][] = 'Dispositivo';
        $this->messages['it'][] = 'Dispositivo';
        $this->messages['fr'][] = 'Appareil';
        $this->messages['de'][] = 'Gerät';

        $this->messages['en'][] = 'Mount point';
        $this->messages['pt'][] = 'Ponto de montagem';
        $this->messages['es'][] = 'Punto de montaje';
        $this->messages['it'][] = 'Punto di montaggio';
        $this->messages['fr'][] = 'Point de montage';
        $this->messages['de'][] = 'Einhängepunkt';

        $this->messages['en'][] = 'Filesystem';
        $this->messages['pt'][] = 'Sistema de arquivos';
        $this->messages['es'][] = 'Sistema de archivos';
        $this->messages['it'][] = 'File system';
        $this->messages['fr'][] = 'Système de fichiers';
        $this->messages['de'][] = 'Dateisystem';

        $this->messages['en'][] = 'Network devices';
        $this->messages['pt'][] = 'Dispositivos de rede';
        $this->messages['es'][] = 'Dispositivos de red';
        $this->messages['it'][] = 'Dispositivi di rete';
        $this->messages['fr'][] = 'Périphériques réseau';
        $this->messages['de'][] = 'Netzwerkgeräte';

        $this->messages['en'][] = 'Device name';
        $this->messages['pt'][] = 'Nome do dispositivo';
        $this->messages['es'][] = 'Nombre del dispositivo';
        $this->messages['it'][] = 'Nome del dispositivo';
        $this->messages['fr'][] = 'Nom du périphérique';
        $this->messages['de'][] = 'Gerätename';

        $this->messages['en'][] = 'Port speed';
        $this->messages['pt'][] = 'Velocidade da porta';
        $this->messages['es'][] = 'Velocidad de la puerta';
        $this->messages['it'][] = 'Velocità della porta';
        $this->messages['fr'][] = 'Vitesse du port';
        $this->messages['de'][] = 'Portgeschwindigkeit';

        $this->messages['en'][] = 'Sent';
        $this->messages['pt'][] = 'Enviados';
        $this->messages['es'][] = 'Enviados';
        $this->messages['it'][] = 'Inviati';
        $this->messages['fr'][] = 'Envoyés';
        $this->messages['de'][] = 'Gesendet';

        $this->messages['en'][] = 'Recieved';
        $this->messages['pt'][] = 'Recebidos';
        $this->messages['es'][] = 'Recebidos';
        $this->messages['it'][] = 'Ricevuti';
        $this->messages['fr'][] = 'Reçus';
        $this->messages['de'][] = 'Empfangen';

        $this->messages['en'][] = 'Print';
        $this->messages['pt'][] = 'Imprimir';
        $this->messages['es'][] = 'Imprimir';
        $this->messages['it'][] = 'Stampa';
        $this->messages['fr'][] = 'Imprimer';
        $this->messages['de'][] = 'Drucken';

        $this->messages['en'][] = 'Delete session var';
        $this->messages['pt'][] = 'Exclui variável de sessão';
        $this->messages['es'][] = 'Eliminar variable de sesión';
        $this->messages['it'][] = 'Elimina variabile di sessione';
        $this->messages['fr'][] = 'Supprimer la variable de session';
        $this->messages['de'][] = 'Sitzungsvariable löschen';

        $this->messages['en'][] = 'Impersonated by';
        $this->messages['pt'][] = 'Personificado por';
        $this->messages['es'][] = 'Personificado por';
        $this->messages['it'][] = 'Impersonificato da';
        $this->messages['fr'][] = 'Usurpé par';
        $this->messages['de'][] = 'Imitiert von';

        $this->messages['en'][] = 'Unauthorized access to that unit';
        $this->messages['pt'][] = 'Acesso não autorizado à esta unidade';
        $this->messages['es'][] = 'Acceso prohibido a esta unidad';
        $this->messages['it'][] = 'Accesso non autorizzato a questa unità';
        $this->messages['fr'][] = 'Accès non autorisé à cette unité';
        $this->messages['de'][] = 'Unbefugter Zugriff auf diese Einheit';

        $this->messages['en'][] = 'Files diff';
        $this->messages['pt'][] = 'Diferença de arquivos';
        $this->messages['es'][] = 'Diferencia de archivo';
        $this->messages['it'][] = 'Differenza file';
        $this->messages['fr'][] = 'Différence de fichiers';
        $this->messages['de'][] = 'Dateiunterschiede';

        $this->messages['en'][] = 'Removed';
        $this->messages['pt'][] = 'Removido';
        $this->messages['es'][] = 'Remoto';
        $this->messages['it'][] = 'Rimosso';
        $this->messages['fr'][] = 'Supprimé';
        $this->messages['de'][] = 'Entfernt';

        $this->messages['en'][] = 'Equal';
        $this->messages['pt'][] = 'Igual';
        $this->messages['es'][] = 'Igual';
        $this->messages['it'][] = 'Uguale';
        $this->messages['fr'][] = 'Égal';
        $this->messages['de'][] = 'Gleich';

        $this->messages['en'][] = 'Modified';
        $this->messages['pt'][] = 'Modificado';
        $this->messages['es'][] = 'Cambiado';
        $this->messages['it'][] = 'Modificato';
        $this->messages['fr'][] = 'Modifié';
        $this->messages['de'][] = 'Geändert';

        $this->messages['en'][] = 'Terms of use and privacy policy';
        $this->messages['pt'][] = 'Termo de uso e polí­tica de privacidade';
        $this->messages['es'][] = 'Términos de uso y polí­tica de privacidad';
        $this->messages['it'][] = 'Termini di utilizzo e informativa sulla privacy';
        $this->messages['fr'][] = 'Conditions d’utilisation et politique de confidentialité';
        $this->messages['de'][] = 'Nutzungsbedingungen und Datenschutzrichtlinie';

        $this->messages['en'][] = 'Accept';
        $this->messages['pt'][] = 'Aceitar';
        $this->messages['es'][] = 'Aceptar';
        $this->messages['it'][] = 'Accetta';
        $this->messages['fr'][] = 'Accepter';
        $this->messages['de'][] = 'Akzeptieren';


        $this->messages['en'][] = 'I have read and agree to the terms of use and privacy policy';
        $this->messages['pt'][] = 'Eu li e concordo com os termos de uso e política de privacidade';
        $this->messages['es'][] = 'He leí­do y acepto los términos de uso y la política de privacidad';
        $this->messages['it'][] = 'Ho letto e accetto i termini di utilizzo e l’informativa sulla privacy';
        $this->messages['fr'][] = 'J’ai lu et j’accepte les conditions d’utilisation et la politique de confidentialité';
        $this->messages['de'][] = 'Ich habe die Nutzungsbedingungen und die Datenschutzrichtlinie gelesen und akzeptiere sie';

        $this->messages['en'][] = 'You need read and agree to the terms of use and privacy policy';
        $this->messages['pt'][] = 'Você precisa ler e concordar com os termos de uso e polí­tica de privacidade';
        $this->messages['es'][] = 'Necesita leer y aceptar los términos de uso y la política de privacidad';
        $this->messages['it'][] = 'È necessario leggere e accettare i termini di utilizzo e l’informativa sulla privacy';
        $this->messages['fr'][] = 'Vous devez lire et accepter les conditions d’utilisation et la politique de confidentialité';
        $this->messages['de'][] = 'Sie müssen die Nutzungsbedingungen und die Datenschutzrichtlinie lesen und akzeptieren';

        $this->messages['en'][] = 'Login to your account';
        $this->messages['pt'][] = 'Login realizado em sua conta';
        $this->messages['es'][] = 'Ingrese a su cuenta';
        $this->messages['it'][] = 'Accedi al tuo account';
        $this->messages['fr'][] = 'Connectez-vous à votre compte';
        $this->messages['de'][] = 'Melden Sie sich bei Ihrem Konto an';

        $this->messages['en'][] = 'You have just successfully logged in to ^1. If you do not recognize this login, contact technical support';
        $this->messages['pt'][] = 'Você acaba de efetuar login com sucesso no ^1. Se não reconhece esse login, entre em contato com o suporte técnico';
        $this->messages['es'][] = 'Acaba de iniciar sesión correctamente en ^1. Si no reconoce este inicio de sesión, comuníquese con el soporte técnico';
        $this->messages['it'][] = 'Hai appena effettuato l’accesso con successo a ^1. Se non riconosci questo accesso, contatta il supporto tecnico';
        $this->messages['fr'][] = 'Vous venez de vous connecter avec succès à ^1. Si vous ne reconnaissez pas cette connexion, contactez le support technique';
        $this->messages['de'][] = 'Sie haben sich gerade erfolgreich bei ^1 angemeldet. Wenn Sie diese Anmeldung nicht erkennen, wenden Sie sich an den technischen Support';

        $this->messages['en'][] = 'Click here for more information';
        $this->messages['pt'][] = 'Clique aqui para obter mais informações';
        $this->messages['es'][] = 'Haga clic aquí­ para más información';
        $this->messages['it'][] = 'Clicca qui per maggiori informazioni';
        $this->messages['fr'][] = 'Cliquez ici pour plus d’informations';
        $this->messages['de'][] = 'Klicken Sie hier für weitere Informationen';

        $this->messages['en'][] = 'You have already registered this password';
        $this->messages['pt'][] = 'Você já cadastrou essa senha';
        $this->messages['es'][] = 'Ya has registrado esta contraseña';
        $this->messages['it'][] = 'Hai già registrato questa password';
        $this->messages['fr'][] = 'Vous avez déjà enregistré ce mot de passe';
        $this->messages['de'][] = 'Sie haben dieses Passwort bereits registriert';

        $this->messages['en'][] = 'Renewal password';
        $this->messages['pt'][] = 'Renovação de senha';
        $this->messages['es'][] = 'Renovación de contraseña';
        $this->messages['it'][] = 'Rinnovo password';
        $this->messages['fr'][] = 'Renouvellement du mot de passe';
        $this->messages['de'][] = 'Passwort erneuern';

        $this->messages['en'][] = 'You need to renew your password, as we have identified that you have not changed it for more than ^1 days';
        $this->messages['pt'][] = 'Você precisa renovar sua senha, pois identificamos que você não a altera há mais de ^1 dias';
        $this->messages['es'][] = 'Debe renovar su contraseña, ya que hemos identificado que no la ha cambiado durante más de ^1 días';
        $this->messages['it'][] = 'Devi rinnovare la tua password, poiché abbiamo rilevato che non la cambi da più di ^1 giorni';
        $this->messages['fr'][] = 'Vous devez renouveler votre mot de passe, car nous avons identifié que vous ne l’avez pas changé depuis plus de ^1 jours';
        $this->messages['de'][] = 'Sie müssen Ihr Passwort erneuern, da wir festgestellt haben, dass Sie es seit mehr als ^1 Tagen nicht geändert haben';

        $this->messages['en'][] = 'Global search';
        $this->messages['pt'][] = 'Busca global';
        $this->messages['es'][] = 'Buscar global';
        $this->messages['it'][] = 'Ricerca globale';
        $this->messages['fr'][] = 'Recherche globale';
        $this->messages['de'][] = 'Globale Suche';

        $this->messages['en'][] = 'More';
        $this->messages['pt'][] = 'Mais';
        $this->messages['es'][] = 'Más';
        $this->messages['it'][] = 'Altro';
        $this->messages['fr'][] = 'Plus';
        $this->messages['de'][] = 'Mehr';

        $this->messages['en'][] = 'Upload file';
        $this->messages['pt'][] = 'Adicionar arquivo';
        $this->messages['es'][] = 'Subir archivo';
        $this->messages['it'][] = 'Carica file';
        $this->messages['fr'][] = 'Téléverser un fichier';
        $this->messages['de'][] = 'Datei hochladen';

        $this->messages['en'][] = 'New folder';
        $this->messages['pt'][] = 'Nova pasta';
        $this->messages['es'][] = 'Nueva carpeta';
        $this->messages['it'][] = 'Nuova cartella';
        $this->messages['fr'][] = 'Nouveau dossier';
        $this->messages['de'][] = 'Neuer Ordner';

        $this->messages['en'][] = 'Folder';
        $this->messages['pt'][] = 'Pasta';
        $this->messages['es'][] = 'Carpeta';
        $this->messages['it'][] = 'Cartella';
        $this->messages['fr'][] = 'Dossier';
        $this->messages['de'][] = 'Ordner';

        $this->messages['en'][] = 'This operation is not allowed';
        $this->messages['pt'][] = 'Esta operação não é permitida';
        $this->messages['es'][] = 'Esta operación no está permitida';
        $this->messages['it'][] = 'Questa operazione non è consentita';
        $this->messages['fr'][] = 'Cette opération n’est pas autorisée';
        $this->messages['de'][] = 'Diese Aktion ist nicht erlaubt';

        $this->messages['en'][] = 'Parent folder';
        $this->messages['pt'][] = 'Pasta pai';
        $this->messages['es'][] = 'Carpeta principal';
        $this->messages['it'][] = 'Cartella principale';
        $this->messages['fr'][] = 'Dossier parent';
        $this->messages['de'][] = 'Übergeordneter Ordner';

        $this->messages['en'][] = 'Sent out';
        $this->messages['pt'][] = 'Enviado para fora';
        $this->messages['es'][] = 'Enviado a fuera';
        $this->messages['it'][] = 'Inviato fuori';
        $this->messages['fr'][] = 'Envoyé à l’extérieur';
        $this->messages['de'][] = 'Nach draußen gesendet';

        $this->messages['en'][] = 'Sent to trash';
        $this->messages['pt'][] = 'Enviado para lixeira';
        $this->messages['es'][] = 'Enviado a la basura';
        $this->messages['it'][] = 'Inviato nel cestino';
        $this->messages['fr'][] = 'Envoyé à la corbeille';
        $this->messages['de'][] = 'In den Papierkorb verschoben';

        $this->messages['en'][] = 'Sent to ^1';
        $this->messages['pt'][] = 'Enviado para ^1';
        $this->messages['es'][] = 'Enviado a ^1';
        $this->messages['it'][] = 'Inviato a ^1';
        $this->messages['fr'][] = 'Envoyé à ^1';
        $this->messages['de'][] = 'Gesendet an ^1';

        $this->messages['en'][] = 'Bookmarked';
        $this->messages['pt'][] = 'Marcado como favorito';
        $this->messages['es'][] = 'Definido como favorito';
        $this->messages['it'][] = 'Aggiunto ai preferiti';
        $this->messages['fr'][] = 'Ajouté aux favoris';
        $this->messages['de'][] = 'Als Favorit markiert';

        $this->messages['en'][] = 'Bookmarks';
        $this->messages['pt'][] = 'Favoritos';
        $this->messages['es'][] = 'Favoritos';
        $this->messages['it'][] = 'Preferiti';
        $this->messages['fr'][] = 'Favoris';
        $this->messages['de'][] = 'Favoriten';


        $this->messages['en'][] = 'Trash';
        $this->messages['pt'][] = 'Lixeira';
        $this->messages['es'][] = 'Basura';
        $this->messages['it'][] = 'Cestino';
        $this->messages['fr'][] = 'Corbeille';
        $this->messages['de'][] = 'Papierkorb';

        $this->messages['en'][] = 'Send to trash';
        $this->messages['pt'][] = 'Enviar para lixeira';
        $this->messages['es'][] = 'Enviar a la basura';
        $this->messages['it'][] = 'Invia al cestino';
        $this->messages['fr'][] = 'Envoyer à la corbeille';
        $this->messages['de'][] = 'In den Papierkorb verschieben';

        $this->messages['en'][] = 'Set bookmark';
        $this->messages['pt'][] = 'Marcar como favorito';
        $this->messages['es'][] = 'Marcar como favorito';
        $this->messages['it'][] = 'Aggiungi ai preferiti';
        $this->messages['fr'][] = 'Ajouter aux favoris';
        $this->messages['de'][] = 'Als Favorit markieren';

        $this->messages['en'][] = 'Remove from bookmark';
        $this->messages['pt'][] = 'Remover dos favoritos';
        $this->messages['es'][] = 'Remover dos favoritos';
        $this->messages['it'][] = 'Rimuovi dai preferiti';
        $this->messages['fr'][] = 'Retirer des favoris';
        $this->messages['de'][] = 'Aus Favoriten entfernen';

        $this->messages['en'][] = 'Restore from trash';
        $this->messages['pt'][] = 'Restaurar da lixeira';
        $this->messages['es'][] = 'Recuperarse de la basura';
        $this->messages['it'][] = 'Ripristina dal cestino';
        $this->messages['fr'][] = 'Restaurer depuis la corbeille';
        $this->messages['de'][] = 'Aus dem Papierkorb wiederherstellen';

        $this->messages['en'][] = 'Restored';
        $this->messages['pt'][] = 'Restaurado';
        $this->messages['es'][] = 'Restaurado';
        $this->messages['it'][] = 'Ripristinato';
        $this->messages['fr'][] = 'Restauré';
        $this->messages['de'][] = 'Wiederhergestellt';

        $this->messages['en'][] = 'Share';
        $this->messages['pt'][] = 'Compartilhar';
        $this->messages['es'][] = 'Cuota';
        $this->messages['it'][] = 'Condividi';
        $this->messages['fr'][] = 'Partager';
        $this->messages['de'][] = 'Teilen';

        $this->messages['en'][] = 'Details';
        $this->messages['pt'][] = 'Detalhes';
        $this->messages['es'][] = 'Detalles';
        $this->messages['it'][] = 'Dettagli';
        $this->messages['fr'][] = 'Détails';
        $this->messages['de'][] = 'Details';

        $this->messages['en'][] = 'Permanently delete';
        $this->messages['pt'][] = 'Remover permanentemente';
        $this->messages['es'][] = 'Remover permanentemente';
        $this->messages['it'][] = 'Elimina definitivamente';
        $this->messages['fr'][] = 'Supprimer définitivement';
        $this->messages['de'][] = 'Dauerhaft löschen';

        $this->messages['en'][] = 'This will remove all the contents of the folder';
        $this->messages['pt'][] = 'Isso irá remover todos o conteudo da pasta';
        $this->messages['es'][] = 'Esto eliminará todo el contenido de la carpeta.';
        $this->messages['it'][] = 'Questo rimuoverà tutto il contenuto della cartella';
        $this->messages['fr'][] = 'Cela supprimera tout le contenu du dossier';
        $this->messages['de'][] = 'Dies entfernt den gesamten Inhalt des Ordners';

        $this->messages['en'][] = 'Created at';
        $this->messages['pt'][] = 'Criado em';
        $this->messages['es'][] = 'Creado el';
        $this->messages['it'][] = 'Creato il';
        $this->messages['fr'][] = 'Créé le';
        $this->messages['de'][] = 'Erstellt am';

        $this->messages['en'][] = 'Updated at';
        $this->messages['pt'][] = 'Atualizado em';
        $this->messages['es'][] = 'Actualizado el';
        $this->messages['it'][] = 'Aggiornato il';
        $this->messages['fr'][] = 'Mis à jour le';
        $this->messages['de'][] = 'Aktualisiert am';

        $this->messages['en'][] = 'Have posts with this tag, please inactive';
        $this->messages['pt'][] = 'Tem postagens com essa tag, por favor inative';
        $this->messages['es'][] = 'Tener publicaciones con esta etiqueta, por favor inactivo';
        $this->messages['it'][] = 'Ci sono post con questo tag, si prega di disattivare';
        $this->messages['fr'][] = 'Des publications utilisent ce tag, veuillez le désactiver';
        $this->messages['de'][] = 'Es gibt Beiträge mit diesem Tag, bitte deaktivieren';

        $this->messages['en'][] = 'Post';
        $this->messages['pt'][] = 'Publicação';
        $this->messages['es'][] = 'Publicacion';
        $this->messages['it'][] = 'Post';
        $this->messages['fr'][] = 'Publication';
        $this->messages['de'][] = 'Beitrag';

        $this->messages['en'][] = 'Posts';
        $this->messages['pt'][] = 'Publicações';
        $this->messages['es'][] = 'Publicaciones';
        $this->messages['it'][] = 'Post';
        $this->messages['fr'][] = 'Publications';
        $this->messages['de'][] = 'Beiträge';


        $this->messages['en'][] = 'Created by';
        $this->messages['pt'][] = 'Criado por';
        $this->messages['es'][] = 'Criado por';
        $this->messages['it'][] = 'Creato da';
        $this->messages['fr'][] = 'Créé par';
        $this->messages['de'][] = 'Erstellt von';

        $this->messages['en'][] = 'until';
        $this->messages['pt'][] = 'até';
        $this->messages['es'][] = 'asta';
        $this->messages['it'][] = 'fino a';
        $this->messages['fr'][] = 'jusqu’à';
        $this->messages['de'][] = 'bis';

        $this->messages['en'][] = 'Content';
        $this->messages['pt'][] = 'Conteúdo';
        $this->messages['es'][] = 'Contenido';
        $this->messages['it'][] = 'Contenuto';
        $this->messages['fr'][] = 'Contenu';
        $this->messages['de'][] = 'Inhalt';

        $this->messages['en'][] = 'Preview';
        $this->messages['pt'][] = 'Pré-visualização';
        $this->messages['es'][] = 'Avance';
        $this->messages['it'][] = 'Anteprima';
        $this->messages['fr'][] = 'Aperçu';
        $this->messages['de'][] = 'Vorschau';

        $this->messages['en'][] = 'News';
        $this->messages['pt'][] = 'Notí­cias';
        $this->messages['es'][] = 'Noticias';
        $this->messages['it'][] = 'Notizie';
        $this->messages['fr'][] = 'Actualités';
        $this->messages['de'][] = 'Nachrichten';

        $this->messages['en'][] = 'Like';
        $this->messages['pt'][] = 'Curtir';
        $this->messages['es'][] = 'Gusto';
        $this->messages['it'][] = 'Mi piace';
        $this->messages['fr'][] = 'J’aime';
        $this->messages['de'][] = 'Gefällt mir';

        $this->messages['en'][] = 'Comment';
        $this->messages['pt'][] = 'Comentar';
        $this->messages['es'][] = 'Comentar';
        $this->messages['it'][] = 'Commenta';
        $this->messages['fr'][] = 'Commenter';
        $this->messages['de'][] = 'Kommentieren';

        $this->messages['en'][] = 'Comments';
        $this->messages['pt'][] = 'Comentários';
        $this->messages['es'][] = 'Comentarios';
        $this->messages['it'][] = 'Commenti';
        $this->messages['fr'][] = 'Commentaires';
        $this->messages['de'][] = 'Kommentare';

        $this->messages['en'][] = 'Likes';
        $this->messages['pt'][] = 'Curtidas';
        $this->messages['es'][] = 'Gustos';
        $this->messages['it'][] = 'Mi piace';
        $this->messages['fr'][] = 'Mentions j’aime';
        $this->messages['de'][] = 'Gefällt mir-Angaben';

        $this->messages['en'][] = 'See more';
        $this->messages['pt'][] = 'Ver mais';
        $this->messages['es'][] = 'Ver más';
        $this->messages['it'][] = 'Vedi di più';
        $this->messages['fr'][] = 'Voir plus';
        $this->messages['de'][] = 'Mehr anzeigen';

        $this->messages['en'][] = 'Phone';
        $this->messages['pt'][] = 'Telefone';
        $this->messages['es'][] = 'Teléfono';
        $this->messages['it'][] = 'Telefono';
        $this->messages['fr'][] = 'Téléphone';
        $this->messages['de'][] = 'Telefon';

        $this->messages['en'][] = 'Address';
        $this->messages['pt'][] = 'Endereço';
        $this->messages['es'][] = 'Dirección';
        $this->messages['it'][] = 'Indirizzo';
        $this->messages['fr'][] = 'Adresse';
        $this->messages['de'][] = 'Adresse';

        $this->messages['en'][] = 'Function';
        $this->messages['pt'][] = 'Função';
        $this->messages['es'][] = 'Función';
        $this->messages['it'][] = 'Funzione';
        $this->messages['fr'][] = 'Fonction';
        $this->messages['de'][] = 'Funktion';

        $this->messages['en'][] = 'About';
        $this->messages['pt'][] = 'Sobre';
        $this->messages['es'][] = 'Sobre';
        $this->messages['it'][] = 'Informazioni';
        $this->messages['fr'][] = 'À propos';
        $this->messages['de'][] = 'Über';

        $this->messages['en'][] = 'Expand';
        $this->messages['pt'][] = 'Expandir';
        $this->messages['es'][] = 'Expandir';
        $this->messages['it'][] = 'Espandi';
        $this->messages['fr'][] = 'Développer';
        $this->messages['de'][] = 'Erweitern';

        $this->messages['en'][] = 'Contacts';
        $this->messages['pt'][] = 'Contatos';
        $this->messages['es'][] = 'Contactos';
        $this->messages['it'][] = 'Contatti';
        $this->messages['fr'][] = 'Contacts';
        $this->messages['de'][] = 'Kontakte';

        $this->messages['en'][] = 'Call';
        $this->messages['pt'][] = 'Ligar';
        $this->messages['es'][] = 'Llamada';
        $this->messages['it'][] = 'Chiama';
        $this->messages['fr'][] = 'Appeler';
        $this->messages['de'][] = 'Anrufen';

        $this->messages['en'][] = 'Searchable';
        $this->messages['pt'][] = 'Pesquisável';
        $this->messages['es'][] = 'Buscable';
        $this->messages['it'][] = 'Ricercabile';
        $this->messages['fr'][] = 'Recherchable';
        $this->messages['de'][] = 'Durchsuchbar';

        $this->messages['en'][] = 'Add wiki link';
        $this->messages['pt'][] = 'Adicionar link da wiki';
        $this->messages['es'][] = 'Agregar enlace wiki';
        $this->messages['it'][] = 'Aggiungi link wiki';
        $this->messages['fr'][] = 'Ajouter un lien wiki';
        $this->messages['de'][] = 'Wiki-Link hinzufügen';

        $this->messages['en'][] = 'Last modification';
        $this->messages['pt'][] = 'Última modificação';
        $this->messages['es'][] = 'Última modificación';
        $this->messages['it'][] = 'Ultima modifica';
        $this->messages['fr'][] = 'Dernière modification';
        $this->messages['de'][] = 'Letzte Änderung';

        $this->messages['en'][] = 'Page management';
        $this->messages['pt'][] = 'Gestão de páginas';
        $this->messages['es'][] = 'Gestión de páginas';
        $this->messages['it'][] = 'Gestione pagine';
        $this->messages['fr'][] = 'Gestion des pages';
        $this->messages['de'][] = 'Seitenverwaltung';

        $this->messages['en'][] = 'Search pages';
        $this->messages['pt'][] = 'Buscar páginas';
        $this->messages['es'][] = 'Buscar páginas';
        $this->messages['it'][] = 'Cerca pagine';
        $this->messages['fr'][] = 'Rechercher des pages';
        $this->messages['de'][] = 'Seiten suchen';

        $this->messages['en'][] = 'News management';
        $this->messages['pt'][] = 'Gestão de notícias';
        $this->messages['es'][] = 'Gestión de noticias';
        $this->messages['it'][] = 'Gestione notizie';
        $this->messages['fr'][] = 'Gestion des actualités';
        $this->messages['de'][] = 'Nachrichtenverwaltung';

        $this->messages['en'][] = 'List news';
        $this->messages['pt'][] = 'Listar notícias';
        $this->messages['es'][] = 'Lista de noticias';
        $this->messages['it'][] = 'Elenco notizie';
        $this->messages['fr'][] = 'Lister les actualités';
        $this->messages['de'][] = 'Nachrichten auflisten';

        $this->messages['en'][] = 'Properties';
        $this->messages['pt'][] = 'Propriedades';
        $this->messages['es'][] = 'Propiedades';
        $this->messages['it'][] = 'Proprietà';
        $this->messages['fr'][] = 'Propriétés';
        $this->messages['de'][] = 'Eigenschaften';

        $this->messages['en'][] = 'Custom code';
        $this->messages['pt'][] = 'Código personalizado';
        $this->messages['es'][] = 'Código personalizado';
        $this->messages['it'][] = 'Codice personalizzato';
        $this->messages['fr'][] = 'Code personnalisé';
        $this->messages['de'][] = 'Benutzerdefinierter Code';

        $this->messages['en'][] = 'Role';
        $this->messages['pt'][] = 'Papel';
        $this->messages['es'][] = 'Rol';
        $this->messages['it'][] = 'Ruolo';
        $this->messages['fr'][] = 'Rôle';
        $this->messages['de'][] = 'Rolle';

        $this->messages['en'][] = 'Roles';
        $this->messages['pt'][] = 'Papéis';
        $this->messages['es'][] = 'Roles';
        $this->messages['it'][] = 'Ruoli';
        $this->messages['fr'][] = 'Rôles';
        $this->messages['de'][] = 'Rollen';


        $this->messages['en'][] = 'Grant all methods';
        $this->messages['pt'][] = 'Conceder todos métodos';
        $this->messages['es'][] = 'Otorgar todos los métodos';
        $this->messages['it'][] = 'Concedi tutti i metodi';
        $this->messages['fr'][] = 'Accorder toutes les méthodes';
        $this->messages['de'][] = 'Alle Methoden gewähren';

        $this->messages['en'][] = 'All roles';
        $this->messages['pt'][] = 'Todos papéis';
        $this->messages['es'][] = 'Todos roles';
        $this->messages['it'][] = 'Tutti i ruoli';
        $this->messages['fr'][] = 'Tous les rôles';
        $this->messages['de'][] = 'Alle Rollen';

        $this->messages['en'][] = 'Methods';
        $this->messages['pt'][] = 'Métodos';
        $this->messages['es'][] = 'Métodos';
        $this->messages['it'][] = 'Metodi';
        $this->messages['fr'][] = 'Méthodes';
        $this->messages['de'][] = 'Methoden';

        $this->messages['en'][] = 'Restricted methods';
        $this->messages['pt'][] = 'Métodos restritos';
        $this->messages['es'][] = 'Métodos restringidos';
        $this->messages['it'][] = 'Metodi riservati';
        $this->messages['fr'][] = 'Méthodes restreintes';
        $this->messages['de'][] = 'Eingeschränkte Methoden';

        $this->messages['en'][] = 'User not found or wrong password';
        $this->messages['pt'][] = 'Usuário não encontrado ou senha incorreta';
        $this->messages['es'][] = 'Usuario no encontrada o contraseña incorrecta';
        $this->messages['it'][] = 'Utente non trovato o password errata';
        $this->messages['fr'][] = 'Utilisateur introuvable ou mot de passe incorrect';
        $this->messages['de'][] = 'Benutzer nicht gefunden oder falsches Passwort';

        $this->messages['en'][] = 'Password should be at least 6 characters and include at least one upper case letter, one number, and one special character';
        $this->messages['pt'][] = 'Senhas devem ter pelo menos 6 caracteres e incluir pelo menos uma letra maiúscula, um número, e um caracter especial';
        $this->messages['es'][] = 'La contraseña debe tener al menos 6 caracteres e incluir al menos una letra mayúscula, un número y un carácter especial';
        $this->messages['it'][] = 'La password deve contenere almeno 6 caratteri, una lettera maiuscola, un numero e un carattere speciale';
        $this->messages['fr'][] = 'Le mot de passe doit comporter au moins 6 caractères, une majuscule, un chiffre et un caractère spécial';
        $this->messages['de'][] = 'Das Passwort muss mindestens 6 Zeichen lang sein und mindestens einen Großbuchstaben, eine Zahl und ein Sonderzeichen enthalten';

        $this->messages['en'][] = 'My profile';
        $this->messages['pt'][] = 'Meu perfil';
        $this->messages['es'][] = 'Mi perfil';
        $this->messages['it'][] = 'Il mio profilo';
        $this->messages['fr'][] = 'Mon profil';
        $this->messages['de'][] = 'Mein Profil';

        $this->messages['en'][] = 'Enable 2FA';
        $this->messages['pt'][] = 'Habilitar 2FA';
        $this->messages['es'][] = 'Habilitar 2FA';
        $this->messages['it'][] = 'Abilita 2FA';
        $this->messages['fr'][] = 'Activer 2FA';
        $this->messages['de'][] = '2FA aktivieren';

        $this->messages['en'][] = 'Two factor authentication';
        $this->messages['pt'][] = 'Autenticação de dois fatores';
        $this->messages['es'][] = 'Autenticación de dos factores';
        $this->messages['it'][] = 'Autenticazione a due fattori';
        $this->messages['fr'][] = 'Authentification à deux facteurs';
        $this->messages['de'][] = 'Zwei-Faktor-Authentifizierung';

        $this->messages['en'][] = 'Enter the 6-digit code from your authenticator app';
        $this->messages['pt'][] = 'Digite os código de 6 dí­gitos a partir de seu aplicativo autenticador';
        $this->messages['es'][] = 'Ingrese el código de 6 dí­gitos de su aplicación de autenticación';
        $this->messages['it'][] = 'Inserisci il codice a 6 cifre dalla tua app di autenticazione';
        $this->messages['fr'][] = 'Entrez le code à 6 chiffres de votre application d’authentification';
        $this->messages['de'][] = 'Geben Sie den 6-stelligen Code aus Ihrer Authentifizierungs-App ein';

        $this->messages['en'][] = 'Authentication code';
        $this->messages['pt'][] = 'Código de autenticação';
        $this->messages['es'][] = 'Código de autenticación';
        $this->messages['it'][] = 'Codice di autenticazione';
        $this->messages['fr'][] = 'Code d’authentification';
        $this->messages['de'][] = 'Authentifizierungscode';

        $this->messages['en'][] = 'Authenticate';
        $this->messages['pt'][] = 'Autenticar';
        $this->messages['es'][] = 'Autenticar';
        $this->messages['it'][] = 'Autentica';
        $this->messages['fr'][] = 'Authentifier';
        $this->messages['de'][] = 'Authentifizieren';

        $this->messages['en'][] = 'Configure two-factor authentication';
        $this->messages['pt'][] = 'Configurar autenticação de dois fatores';
        $this->messages['es'][] = 'Configurar la autenticación de dos factores';
        $this->messages['it'][] = 'Configura l’autenticazione a due fattori';
        $this->messages['fr'][] = 'Configurer l’authentification à deux facteurs';
        $this->messages['de'][] = 'Zwei-Faktor-Authentifizierung konfigurieren';

        $this->messages['en'][] = 'Scan the QR code with your phone to get started';
        $this->messages['pt'][] = 'Digitalize o código QR com seu telefone para começar';
        $this->messages['es'][] = 'Escanea el código QR con tu teléfono para comenzar';
        $this->messages['it'][] = 'Scansiona il codice QR con il tuo telefono per iniziare';
        $this->messages['fr'][] = 'Scannez le code QR avec votre téléphone pour commencer';
        $this->messages['de'][] = 'Scannen Sie den QR-Code mit Ihrem Telefon, um zu starten';

        $this->messages['en'][] = 'Secret key';
        $this->messages['pt'][] = 'Chave secreta';
        $this->messages['es'][] = 'Llave secreta';
        $this->messages['it'][] = 'Chiave segreta';
        $this->messages['fr'][] = 'Clé secrète';
        $this->messages['de'][] = 'Geheimer Schlüssel';

        $this->messages['en'][] = 'Use authencator app like Google Authenticator or Authy';
        $this->messages['pt'][] = 'Use uma aplicação de autenticação como Google Authenticator ou Authy';
        $this->messages['es'][] = 'Utilice una aplicación de autenticación como Google Authenticator o Authy';
        $this->messages['it'][] = 'Usa un’app di autenticazione come Google Authenticator o Authy';
        $this->messages['fr'][] = 'Utilisez une application d’authentification comme Google Authenticator ou Authy';
        $this->messages['de'][] = 'Verwenden Sie eine Authentifizierungs-App wie Google Authenticator oder Authy';

        $this->messages['en'][] = 'Duration';
        $this->messages['pt'][] = 'Duração';
        $this->messages['es'][] = 'Duración';
        $this->messages['it'][] = 'Durata';
        $this->messages['fr'][] = 'Durée';
        $this->messages['de'][] = 'Dauer';

        $this->messages['en'][] = 'Accesses today';
        $this->messages['pt'][] = 'Accessos hoje';
        $this->messages['es'][] = 'Inicios de sesión hoy';
        $this->messages['it'][] = 'Accessi oggi';
        $this->messages['fr'][] = 'Accès aujourd’hui';
        $this->messages['de'][] = 'Zugriffe heute';

        $this->messages['en'][] = 'Requests today';
        $this->messages['pt'][] = 'Requests hoje';
        $this->messages['es'][] = 'Requests hoy';
        $this->messages['it'][] = 'Richieste oggi';
        $this->messages['fr'][] = 'Requêtes aujourd’hui';
        $this->messages['de'][] = 'Anfragen heute';

        $this->messages['en'][] = 'SQL DML Statements';
        $this->messages['pt'][] = 'Comandos SQL DML';
        $this->messages['es'][] = 'Comandos SQL DML';
        $this->messages['it'][] = 'Istruzioni SQL DML';
        $this->messages['fr'][] = 'Instructions SQL DML';
        $this->messages['de'][] = 'SQL-DML-Anweisungen';

        $this->messages['en'][] = 'Request time average';
        $this->messages['pt'][] = 'Tempo médio de requisição';
        $this->messages['es'][] = 'Tiempo promedio de solicitud';
        $this->messages['it'][] = 'Tempo medio di richiesta';
        $this->messages['fr'][] = 'Temps moyen de requête';
        $this->messages['de'][] = 'Durchschnittliche Anforderungszeit';


        $this->messages['en'][] = 'Accesses by day';
        $this->messages['pt'][] = 'Acessos por dia';
        $this->messages['es'][] = 'Accesos por día';
        $this->messages['it'][] = 'Accessi per giorno';
        $this->messages['fr'][] = 'Accès par jour';
        $this->messages['de'][] = 'Zugriffe pro Tag';

        $this->messages['en'][] = 'Requests by day';
        $this->messages['pt'][] = 'Requests por dia';
        $this->messages['es'][] = 'Requests por día';
        $this->messages['it'][] = 'Richieste per giorno';
        $this->messages['fr'][] = 'Requêtes par jour';
        $this->messages['de'][] = 'Anfragen pro Tag';

        $this->messages['en'][] = 'SQL statements by day';
        $this->messages['pt'][] = 'Comandos SQL por dia';
        $this->messages['es'][] = 'Comandos SQL por día';
        $this->messages['it'][] = 'Istruzioni SQL per giorno';
        $this->messages['fr'][] = 'Instructions SQL par jour';
        $this->messages['de'][] = 'SQL-Anweisungen pro Tag';

        $this->messages['en'][] = 'Sum';
        $this->messages['pt'][] = 'Soma';
        $this->messages['es'][] = 'Suma';
        $this->messages['it'][] = 'Somma';
        $this->messages['fr'][] = 'Somme';
        $this->messages['de'][] = 'Summe';

        $this->messages['en'][] = 'Slower pages';
        $this->messages['pt'][] = 'Páginas mais lentas';
        $this->messages['es'][] = 'Páginas más lentas';
        $this->messages['it'][] = 'Pagine più lente';
        $this->messages['fr'][] = 'Pages plus lentes';
        $this->messages['de'][] = 'Langsamere Seiten';

        $this->messages['en'][] = 'Slower methods';
        $this->messages['pt'][] = 'Métodos mais lentos';
        $this->messages['es'][] = 'Métodos más lentos';
        $this->messages['it'][] = 'Metodi più lenti';
        $this->messages['fr'][] = 'Méthodes plus lentes';
        $this->messages['de'][] = 'Langsamere Methoden';

        $this->messages['en'][] = 'Extension not found: ^1';
        $this->messages['pt'][] = 'Extensão não encontrada: ^1';
        $this->messages['es'][] = 'Extensión no encontrada: ^1';
        $this->messages['it'][] = 'Estensione non trovata: ^1';
        $this->messages['fr'][] = 'Extension non trouvée : ^1';
        $this->messages['de'][] = 'Erweiterung nicht gefunden: ^1';

        $this->messages['en'][] = 'Language';
        $this->messages['pt'][] = 'Idioma';
        $this->messages['es'][] = 'Idioma';
        $this->messages['it'][] = 'Lingua';
        $this->messages['fr'][] = 'Langue';
        $this->messages['de'][] = 'Sprache';

        $this->messages['en'][] = 'Undelete';
        $this->messages['pt'][] = 'Recuperar';
        $this->messages['es'][] = 'Recuperar';
        $this->messages['it'][] = 'Ripristina';
        $this->messages['fr'][] = 'Restaurer';
        $this->messages['de'][] = 'Wiederherstellen';

        $this->messages['en'][] = 'Move to inbox';
        $this->messages['pt'][] = 'Mover para a caixa de entrada';
        $this->messages['es'][] = 'Mover a la bandeja de entrada';
        $this->messages['it'][] = 'Sposta nella posta in arrivo';
        $this->messages['fr'][] = 'Déplacer vers la boîte de réception';
        $this->messages['de'][] = 'In den Posteingang verschieben';

        $this->messages['en'][] = 'Forward';
        $this->messages['pt'][] = 'Encaminhar';
        $this->messages['es'][] = 'Reenviar';
        $this->messages['it'][] = 'Inoltra';
        $this->messages['fr'][] = 'Transférer';
        $this->messages['de'][] = 'Weiterleiten';

        $this->messages['en'][] = 'Reply';
        $this->messages['pt'][] = 'Responder';
        $this->messages['es'][] = 'Responder';
        $this->messages['it'][] = 'Rispondi';
        $this->messages['fr'][] = 'Répondre';
        $this->messages['de'][] = 'Antworten';

        $this->messages['en'][] = 'To';
        $this->messages['pt'][] = 'Para';
        $this->messages['es'][] = 'Para';
        $this->messages['it'][] = 'A';
        $this->messages['fr'][] = 'À';
        $this->messages['de'][] = 'An';

        $this->messages['en'][] = 'No e-mail sender configured';
        $this->messages['pt'][] = 'E-mail de origem não configurado';
        $this->messages['es'][] = 'Correo electrónico de origen no configurado';
        $this->messages['it'][] = 'Mittente e-mail non configurato';
        $this->messages['fr'][] = 'Expéditeur e-mail non configuré';
        $this->messages['de'][] = 'E-Mail-Absender nicht konfiguriert';

        $this->messages['en'][] = 'No support e-mail configured';
        $this->messages['pt'][] = 'E-mail de suporte não configurado';
        $this->messages['es'][] = 'Correo electrónico de soporte no configurado';
        $this->messages['it'][] = 'E-mail di supporto non configurato';
        $this->messages['fr'][] = 'E-mail de support non configuré';
        $this->messages['de'][] = 'Support-E-Mail nicht konfiguriert';

        $this->messages['en'][] = 'Dark mode';
        $this->messages['pt'][] = 'Modo escuro';
        $this->messages['es'][] = 'Modo oscuro';
        $this->messages['it'][] = 'Modalità scura';
        $this->messages['fr'][] = 'Mode sombre';
        $this->messages['de'][] = 'Dunkelmodus';

        $this->messages['en'][] = 'Attachments';
        $this->messages['pt'][] = 'Anexos';
        $this->messages['es'][] = 'Archivos adjuntos';
        $this->messages['it'][] = 'Allegati';
        $this->messages['fr'][] = 'Pièces jointes';
        $this->messages['de'][] = 'Anhänge';

        $this->messages['en'][] = 'Filters';
        $this->messages['pt'][] = 'Filtros';
        $this->messages['es'][] = 'Filtros';
        $this->messages['it'][] = 'Filtri';
        $this->messages['fr'][] = 'Filtres';
        $this->messages['de'][] = 'Filter';

        $this->messages['en'][] = 'Filter';
        $this->messages['pt'][] = 'Filtro';
        $this->messages['es'][] = 'Filtro';
        $this->messages['it'][] = 'Filtro';
        $this->messages['fr'][] = 'Filtre';
        $this->messages['de'][] = 'Filter';

        $this->messages['en'][] = 'Informations and files';
        $this->messages['pt'][] = 'Informações e arquivos';
        $this->messages['es'][] = 'Información y archivos';
        $this->messages['it'][] = 'Informazioni e file';
        $this->messages['fr'][] = 'Informations et fichiers';
        $this->messages['de'][] = 'Informationen und Dateien';

        $this->messages['en'][] = 'Apply';
        $this->messages['pt'][] = 'Aplicar';
        $this->messages['es'][] = 'Aplicar';
        $this->messages['it'][] = 'Applica';
        $this->messages['fr'][] = 'Appliquer';
        $this->messages['de'][] = 'Anwenden';

        $this->messages['en'][] = 'Create';
        $this->messages['pt'][] = 'Criar';
        $this->messages['es'][] = 'Crear';
        $this->messages['it'][] = 'Crea';
        $this->messages['fr'][] = 'Créer';
        $this->messages['de'][] = 'Erstellen';

        $this->messages['en'][] = 'This folder already exists';
        $this->messages['pt'][] = 'Esta pasta já existe';
        $this->messages['es'][] = 'Esta carpeta ya existe';
        $this->messages['it'][] = 'Questa cartella esiste già';
        $this->messages['fr'][] = 'Ce dossier existe déjà';
        $this->messages['de'][] = 'Dieser Ordner existiert bereits';

        $this->messages['en'][] = 'Calendar';
        $this->messages['pt'][] = 'Calendário';
        $this->messages['es'][] = 'Calendario';
        $this->messages['it'][] = 'Calendario';
        $this->messages['fr'][] = 'Calendrier';
        $this->messages['de'][] = 'Kalender';


        $this->messages['en'][] = 'Start time';
        $this->messages['pt'][] = 'Horário inicial';
        $this->messages['es'][] = 'Hora de inicio';
        $this->messages['it'][] = 'Ora di inizio';
        $this->messages['fr'][] = 'Heure de début';
        $this->messages['de'][] = 'Startzeit';

        $this->messages['en'][] = 'End time';
        $this->messages['pt'][] = 'Horário final';
        $this->messages['es'][] = 'Hora de finalización';
        $this->messages['it'][] = 'Ora di fine';
        $this->messages['fr'][] = 'Heure de fin';
        $this->messages['de'][] = 'Endzeit';

        $this->messages['en'][] = 'Event';
        $this->messages['pt'][] = 'Evento';
        $this->messages['es'][] = 'Evento';
        $this->messages['it'][] = 'Evento';
        $this->messages['fr'][] = 'Événement';
        $this->messages['de'][] = 'Ereignis';

        $this->messages['en'][] = 'Shares';
        $this->messages['pt'][] = 'Compartilhamentos';
        $this->messages['es'][] = 'Comparte';
        $this->messages['it'][] = 'Condivisioni';
        $this->messages['fr'][] = 'Partages';
        $this->messages['de'][] = 'Freigaben';

        $this->messages['en'][] = 'New event';
        $this->messages['pt'][] = 'Novo evento';
        $this->messages['es'][] = 'Nuevo evento';
        $this->messages['it'][] = 'Nuovo evento';
        $this->messages['fr'][] = 'Nouvel événement';
        $this->messages['de'][] = 'Neues Ereignis';

        $this->messages['en'][] = 'Alert before';
        $this->messages['pt'][] = 'Alertar antes';
        $this->messages['es'][] = 'Alerta antes';
        $this->messages['it'][] = 'Avviso prima';
        $this->messages['fr'][] = 'Alerte avant';
        $this->messages['de'][] = 'Vorwarnung';

        $this->messages['en'][] = 'Leave empty to avoid alert';
        $this->messages['pt'][] = 'Deixe vazio para evitar alerta';
        $this->messages['es'][] = 'Dejar vacío para evitar alerta';
        $this->messages['it'][] = 'Lascia vuoto per evitare avviso';
        $this->messages['fr'][] = 'Laisser vide pour éviter l’alerte';
        $this->messages['de'][] = 'Leer lassen, um Warnung zu vermeiden';

        $this->messages['en'][] = 'Hours';
        $this->messages['pt'][] = 'Horas';
        $this->messages['es'][] = 'Horas';
        $this->messages['it'][] = 'Ore';
        $this->messages['fr'][] = 'Heures';
        $this->messages['de'][] = 'Stunden';

        $this->messages['en'][] = 'Until';
        $this->messages['pt'][] = 'Até';
        $this->messages['es'][] = 'Hasta';
        $this->messages['it'][] = 'Fino a';
        $this->messages['fr'][] = 'Jusqu’à';
        $this->messages['de'][] = 'Bis';

        $this->messages['en'][] = 'Recurring interval';
        $this->messages['pt'][] = 'Intervalo da recorrência';
        $this->messages['es'][] = 'Intervalo de recurrencia';
        $this->messages['it'][] = 'Intervallo ricorrente';
        $this->messages['fr'][] = 'Intervalle récurrent';
        $this->messages['de'][] = 'Wiederholungsintervall';

        $this->messages['en'][] = 'Frequency';
        $this->messages['pt'][] = 'Frequência';
        $this->messages['es'][] = 'Frecuencia';
        $this->messages['it'][] = 'Frequenza';
        $this->messages['fr'][] = 'Fréquence';
        $this->messages['de'][] = 'Häufigkeit';

        $this->messages['en'][] = 'Daily';
        $this->messages['pt'][] = 'Diário';
        $this->messages['es'][] = 'Diario';
        $this->messages['it'][] = 'Giornaliero';
        $this->messages['fr'][] = 'Quotidien';
        $this->messages['de'][] = 'Täglich';

        $this->messages['en'][] = 'Weekly';
        $this->messages['pt'][] = 'Semanal';
        $this->messages['es'][] = 'Semanalmente';
        $this->messages['it'][] = 'Settimanale';
        $this->messages['fr'][] = 'Hebdomadaire';
        $this->messages['de'][] = 'Wöchentlich';

        $this->messages['en'][] = 'Monthly';
        $this->messages['pt'][] = 'Mensal';
        $this->messages['es'][] = 'Mensual';
        $this->messages['it'][] = 'Mensile';
        $this->messages['fr'][] = 'Mensuel';
        $this->messages['de'][] = 'Monatlich';

        $this->messages['en'][] = 'Every days';
        $this->messages['pt'][] = 'Todos os dias';
        $this->messages['es'][] = 'Todos los días';
        $this->messages['it'][] = 'Tutti i giorni';
        $this->messages['fr'][] = 'Tous les jours';
        $this->messages['de'][] = 'Jeden Tag';

        $this->messages['en'][] = 'Each';
        $this->messages['pt'][] = 'Cada';
        $this->messages['es'][] = 'Cada';
        $this->messages['it'][] = 'Ogni';
        $this->messages['fr'][] = 'Chaque';
        $this->messages['de'][] = 'Jeder';

        $this->messages['en'][] = 'Sunday';
        $this->messages['pt'][] = 'Domingo';
        $this->messages['es'][] = 'Domingo';
        $this->messages['it'][] = 'Domenica';
        $this->messages['fr'][] = 'Dimanche';
        $this->messages['de'][] = 'Sonntag';

        $this->messages['en'][] = 'Monday';
        $this->messages['pt'][] = 'Segunda';
        $this->messages['es'][] = 'Lunes';
        $this->messages['it'][] = 'Lunedì';
        $this->messages['fr'][] = 'Lundi';
        $this->messages['de'][] = 'Montag';

        $this->messages['en'][] = 'Tuesday';
        $this->messages['pt'][] = 'Terça';
        $this->messages['es'][] = 'Martes';
        $this->messages['it'][] = 'Martedì';
        $this->messages['fr'][] = 'Mardi';
        $this->messages['de'][] = 'Dienstag';

        $this->messages['en'][] = 'Wednesday';
        $this->messages['pt'][] = 'Quarta';
        $this->messages['es'][] = 'Miércoles';
        $this->messages['it'][] = 'Mercoledì';
        $this->messages['fr'][] = 'Mercredi';
        $this->messages['de'][] = 'Mittwoch';

        $this->messages['en'][] = 'Thursday';
        $this->messages['pt'][] = 'Quinta';
        $this->messages['es'][] = 'Jueves';
        $this->messages['it'][] = 'Giovedì';
        $this->messages['fr'][] = 'Jeudi';
        $this->messages['de'][] = 'Donnerstag';

        $this->messages['en'][] = 'Friday';
        $this->messages['pt'][] = 'Sexta';
        $this->messages['es'][] = 'Viernes';
        $this->messages['it'][] = 'Venerdì';
        $this->messages['fr'][] = 'Vendredi';
        $this->messages['de'][] = 'Freitag';

        $this->messages['en'][] = 'Saturday';
        $this->messages['pt'][] = 'Sábado';
        $this->messages['es'][] = 'Sábado';
        $this->messages['it'][] = 'Sabato';
        $this->messages['fr'][] = 'Samedi';
        $this->messages['de'][] = 'Samstag';

        $this->messages['en'][] = 'Batch creation';
        $this->messages['pt'][] = 'Criação em lote';
        $this->messages['es'][] = 'Creación por lotes';
        $this->messages['it'][] = 'Creazione in batch';
        $this->messages['fr'][] = 'Création par lot';
        $this->messages['de'][] = 'Stapel-Erstellung';

        $this->messages['en'][] = 'Tasks';
        $this->messages['pt'][] = 'Tarefas';
        $this->messages['es'][] = 'Tareas';
        $this->messages['it'][] = 'Compiti';
        $this->messages['fr'][] = 'Tâches';
        $this->messages['de'][] = 'Aufgaben';

        $this->messages['en'][] = 'Task';
        $this->messages['pt'][] = 'Tarefa';
        $this->messages['es'][] = 'Tarea';
        $this->messages['it'][] = 'Compito';
        $this->messages['fr'][] = 'Tâche';
        $this->messages['de'][] = 'Aufgabe';

        $this->messages['en'][] = 'Due date';
        $this->messages['pt'][] = 'Data de vencimento';
        $this->messages['es'][] = 'Fecha de vencimiento';
        $this->messages['it'][] = 'Data di scadenza';
        $this->messages['fr'][] = 'Date d’échéance';
        $this->messages['de'][] = 'Fälligkeitsdatum';

        $this->messages['en'][] = 'Finished at';
        $this->messages['pt'][] = 'Terminou em';
        $this->messages['es'][] = 'Terminado en';
        $this->messages['it'][] = 'Terminato il';
        $this->messages['fr'][] = 'Terminé le';
        $this->messages['de'][] = 'Beendet am';


        $this->messages['en'][] = 'Progress';
        $this->messages['pt'][] = 'Progresso';
        $this->messages['es'][] = 'Progreso';
        $this->messages['it'][] = 'Progresso';
        $this->messages['fr'][] = 'Progression';
        $this->messages['de'][] = 'Fortschritt';

        $this->messages['en'][] = 'Priority';
        $this->messages['pt'][] = 'Prioridade';
        $this->messages['es'][] = 'Prioridad';
        $this->messages['it'][] = 'Priorità';
        $this->messages['fr'][] = 'Priorité';
        $this->messages['de'][] = 'Priorität';

        $this->messages['en'][] = 'Responsible';
        $this->messages['pt'][] = 'Responsável';
        $this->messages['es'][] = 'Responsable';
        $this->messages['it'][] = 'Responsabile';
        $this->messages['fr'][] = 'Responsable';
        $this->messages['de'][] = 'Verantwortlich';

        $this->messages['en'][] = 'Spent time';
        $this->messages['pt'][] = 'Tempo gasto';
        $this->messages['es'][] = 'Tiempo usado';
        $this->messages['it'][] = 'Tempo impiegato';
        $this->messages['fr'][] = 'Temps écoulé';
        $this->messages['de'][] = 'Verbrauchte Zeit';

        $this->messages['en'][] = 'Not started';
        $this->messages['pt'][] = 'não iniciado';
        $this->messages['es'][] = 'No empezado';
        $this->messages['it'][] = 'Non iniziato';
        $this->messages['fr'][] = 'Non commencé';
        $this->messages['de'][] = 'Nicht gestartet';

        $this->messages['en'][] = 'In progress';
        $this->messages['pt'][] = 'Em andamento';
        $this->messages['es'][] = 'En curso';
        $this->messages['it'][] = 'In corso';
        $this->messages['fr'][] = 'En cours';
        $this->messages['de'][] = 'In Bearbeitung';

        $this->messages['en'][] = 'Stopped';
        $this->messages['pt'][] = 'Parado';
        $this->messages['es'][] = 'Interrumpido';
        $this->messages['it'][] = 'Fermato';
        $this->messages['fr'][] = 'Arrêté';
        $this->messages['de'][] = 'Gestoppt';

        $this->messages['en'][] = 'Completed';
        $this->messages['pt'][] = 'Finalizado';
        $this->messages['es'][] = 'Completada';
        $this->messages['it'][] = 'Completato';
        $this->messages['fr'][] = 'Terminé';
        $this->messages['de'][] = 'Abgeschlossen';

        $this->messages['en'][] = 'Low';
        $this->messages['pt'][] = 'Baixa';
        $this->messages['es'][] = 'Baja';
        $this->messages['it'][] = 'Bassa';
        $this->messages['fr'][] = 'Faible';
        $this->messages['de'][] = 'Niedrig';

        $this->messages['en'][] = 'Normal';
        $this->messages['pt'][] = 'Normal';
        $this->messages['es'][] = 'Normal';
        $this->messages['it'][] = 'Normale';
        $this->messages['fr'][] = 'Normal';
        $this->messages['de'][] = 'Normal';

        $this->messages['en'][] = 'High';
        $this->messages['pt'][] = 'Alta';
        $this->messages['es'][] = 'Alta';
        $this->messages['it'][] = 'Alta';
        $this->messages['fr'][] = 'Élevée';
        $this->messages['de'][] = 'Hoch';

        $this->messages['en'][] = 'New task';
        $this->messages['pt'][] = 'Nova tarefa';
        $this->messages['es'][] = 'Nueva tarea';
        $this->messages['it'][] = 'Nuovo compito';
        $this->messages['fr'][] = 'Nouvelle tâche';
        $this->messages['de'][] = 'Neue Aufgabe';

        $this->messages['en'][] = 'Delete all recurrences';
        $this->messages['pt'][] = 'Excluir todas as recorrências';
        $this->messages['es'][] = 'Eliminar todas las recurrencias';
        $this->messages['it'][] = 'Elimina tutte le ricorrenze';
        $this->messages['fr'][] = 'Supprimer toutes les occurrences';
        $this->messages['de'][] = 'Alle Wiederholungen löschen';

        $this->messages['en'][] = 'Notes';
        $this->messages['pt'][] = 'Notas';
        $this->messages['es'][] = 'Notas';
        $this->messages['it'][] = 'Note';
        $this->messages['fr'][] = 'Notes';
        $this->messages['de'][] = 'Notizen';

        $this->messages['en'][] = 'Create text file';
        $this->messages['pt'][] = 'Criar arquivo texto';
        $this->messages['es'][] = 'Crear archivo de texto';
        $this->messages['it'][] = 'Crea file di testo';
        $this->messages['fr'][] = 'Créer un fichier texte';
        $this->messages['de'][] = 'Textdatei erstellen';

        $this->messages['en'][] = 'Start';
        $this->messages['pt'][] = 'Iní­cio';
        $this->messages['es'][] = 'Comenzar';
        $this->messages['it'][] = 'Inizio';
        $this->messages['fr'][] = 'Démarrer';
        $this->messages['de'][] = 'Start';

        $this->messages['en'][] = 'Manuals';
        $this->messages['pt'][] = 'Manuais';
        $this->messages['es'][] = 'Manuales';
        $this->messages['it'][] = 'Manuali';
        $this->messages['fr'][] = 'Manuels';
        $this->messages['de'][] = 'Handbücher';

        $this->messages['en'][] = 'Welcome';
        $this->messages['pt'][] = 'Bem-vindo';
        $this->messages['es'][] = 'Bienvenido';
        $this->messages['it'][] = 'Benvenuto';
        $this->messages['fr'][] = 'Bienvenue';
        $this->messages['de'][] = 'Willkommen';

        $this->messages['en'][] = 'Text file';
        $this->messages['pt'][] = 'Arquivo texto';
        $this->messages['es'][] = 'Archivo de texto';
        $this->messages['it'][] = 'File di testo';
        $this->messages['fr'][] = 'Fichier texte';
        $this->messages['de'][] = 'Textdatei';

        $this->messages['en'][] = 'HTML file';
        $this->messages['pt'][] = 'Arquivo HTML';
        $this->messages['es'][] = 'Archivo HTML';
        $this->messages['it'][] = 'File HTML';
        $this->messages['fr'][] = 'Fichier HTML';
        $this->messages['de'][] = 'HTML-Datei';

        $this->messages['en'][] = 'My tasks';
        $this->messages['pt'][] = 'Minhas tarefas';
        $this->messages['es'][] = 'Mis tareas';
        $this->messages['it'][] = 'I miei compiti';
        $this->messages['fr'][] = 'Mes tâches';
        $this->messages['de'][] = 'Meine Aufgaben';

        $this->messages['en'][] = 'All events';
        $this->messages['pt'][] = 'Todos eventos';
        $this->messages['es'][] = 'Todos los eventos';
        $this->messages['it'][] = 'Tutti gli eventi';
        $this->messages['fr'][] = 'Tous les événements';
        $this->messages['de'][] = 'Alle Ereignisse';

        $this->messages['en'][] = 'My events';
        $this->messages['pt'][] = 'Meus eventos';
        $this->messages['es'][] = 'Mis eventos';
        $this->messages['it'][] = 'I miei eventi';
        $this->messages['fr'][] = 'Mes événements';
        $this->messages['de'][] = 'Meine Ereignisse';

        $this->messages['en'][] = 'Month day';
        $this->messages['pt'][] = 'Dia do mês';
        $this->messages['es'][] = 'Dia del mes';
        $this->messages['it'][] = 'Giorno del mese';
        $this->messages['fr'][] = 'Jour du mois';
        $this->messages['de'][] = 'Tag des Monats';

        $this->messages['en'][] = 'Week day';
        $this->messages['pt'][] = 'Dia da semana';
        $this->messages['es'][] = 'Dia de la semana';
        $this->messages['it'][] = 'Giorno della settimana';
        $this->messages['fr'][] = 'Jour de la semaine';
        $this->messages['de'][] = 'Wochentag';

        $this->messages['en'][] = 'Hour';
        $this->messages['pt'][] = 'Hora';
        $this->messages['es'][] = 'Hora';
        $this->messages['it'][] = 'Ora';
        $this->messages['fr'][] = 'Heure';
        $this->messages['de'][] = 'Stunde';

        $this->messages['en'][] = 'Minute';
        $this->messages['pt'][] = 'Minuto';
        $this->messages['es'][] = 'Minuto';
        $this->messages['it'][] = 'Minuto';
        $this->messages['fr'][] = 'Minute';
        $this->messages['de'][] = 'Minute';

        $this->messages['en'][] = 'Once a month';
        $this->messages['pt'][] = 'Uma vez por mês';
        $this->messages['es'][] = 'Una vez al mes';
        $this->messages['it'][] = 'Una volta al mese';
        $this->messages['fr'][] = 'Une fois par mois';
        $this->messages['de'][] = 'Einmal im Monat';


        $this->messages['en'][] = 'Once a week';
        $this->messages['pt'][] = 'Uma vez por semana';
        $this->messages['es'][] = 'Una vez a la semana';
        $this->messages['it'][] = 'Una volta a settimana';
        $this->messages['fr'][] = 'Une fois par semaine';
        $this->messages['de'][] = 'Einmal pro Woche';

        $this->messages['en'][] = 'Once a day';
        $this->messages['pt'][] = 'Uma vez por dia';
        $this->messages['es'][] = 'Una vez al dia';
        $this->messages['it'][] = 'Una volta al giorno';
        $this->messages['fr'][] = 'Une fois par jour';
        $this->messages['de'][] = 'Einmal am Tag';

        $this->messages['en'][] = 'Each five minutes';
        $this->messages['pt'][] = 'A cada 5 minutos';
        $this->messages['es'][] = 'Cada 5 minutos';
        $this->messages['it'][] = 'Ogni 5 minuti';
        $this->messages['fr'][] = 'Toutes les 5 minutes';
        $this->messages['de'][] = 'Alle 5 Minuten';

        $this->messages['en'][] = 'Schedules';
        $this->messages['pt'][] = 'Agendamentos';
        $this->messages['es'][] = 'Trabajos programados';
        $this->messages['it'][] = 'Programmazioni';
        $this->messages['fr'][] = 'Plannings';
        $this->messages['de'][] = 'Zeitpläne';

        $this->messages['en'][] = 'Schedule';
        $this->messages['pt'][] = 'Agendamento';
        $this->messages['es'][] = 'Trabajo programado';
        $this->messages['it'][] = 'Programmazione';
        $this->messages['fr'][] = 'Planification';
        $this->messages['de'][] = 'Zeitplan';

        $this->messages['en'][] = 'Class';
        $this->messages['pt'][] = 'Classe';
        $this->messages['es'][] = 'Clase';
        $this->messages['it'][] = 'Classe';
        $this->messages['fr'][] = 'Classe';
        $this->messages['de'][] = 'Klasse';

        $this->messages['en'][] = 'Delegated to me';
        $this->messages['pt'][] = 'Delegado a mim';
        $this->messages['es'][] = 'Delegada a mi';
        $this->messages['it'][] = 'Delegato a me';
        $this->messages['fr'][] = 'Délégué à moi';
        $this->messages['de'][] = 'Mir zugewiesen';

        $this->messages['en'][] = 'Framework information';
        $this->messages['pt'][] = 'Informações do Framework';
        $this->messages['es'][] = 'Informaciónes del Framework';
        $this->messages['it'][] = 'Informazioni sul framework';
        $this->messages['fr'][] = 'Informations sur le framework';
        $this->messages['de'][] = 'Framework-Informationen';

        $this->messages['en'][] = 'System';
        $this->messages['pt'][] = 'Sistema';
        $this->messages['es'][] = 'Sistema';
        $this->messages['it'][] = 'Sistema';
        $this->messages['fr'][] = 'Système';
        $this->messages['de'][] = 'System';

        $this->messages['en'][] = 'Terms of use';
        $this->messages['pt'][] = 'Termos de uso';
        $this->messages['es'][] = 'Condiciones de uso';
        $this->messages['it'][] = 'Termini di utilizzo';
        $this->messages['fr'][] = 'Conditions d’utilisation';
        $this->messages['de'][] = 'Nutzungsbedingungen';

        $this->messages['en'][] = 'The following files have been modified from the original framework';
        $this->messages['pt'][] = 'Os seguintes arquivos foram modificados em relação ao framework original';
        $this->messages['es'][] = 'Los siguientes archivos han sido modificados desde el marco original';
        $this->messages['it'][] = 'I seguenti file sono stati modificati rispetto al framework originale';
        $this->messages['fr'][] = 'Les fichiers suivants ont été modifiés par rapport au framework original';
        $this->messages['de'][] = 'Die folgenden Dateien wurden gegenüber dem ursprünglichen Framework geändert';

        $this->messages['en'][] = 'All framework files are in their original state';
        $this->messages['pt'][] = 'Todos arquivos do framework encontram-se em seu estado original';
        $this->messages['es'][] = 'Todos los archivos del marco están en su estado original';
        $this->messages['it'][] = 'Tutti i file del framework sono nello stato originale';
        $this->messages['fr'][] = 'Tous les fichiers du framework sont dans leur état d’origine';
        $this->messages['de'][] = 'Alle Framework-Dateien befinden sich im Originalzustand';

        $this->messages['en'][] = 'Update';
        $this->messages['pt'][] = 'Atualizar';
        $this->messages['es'][] = 'Actualizar';
        $this->messages['it'][] = 'Aggiorna';
        $this->messages['fr'][] = 'Mettre à jour';
        $this->messages['de'][] = 'Aktualisieren';

        $this->messages['en'][] = 'Updater';
        $this->messages['pt'][] = 'Atualizador';
        $this->messages['es'][] = 'Actualizador';
        $this->messages['it'][] = 'Aggiornamento';
        $this->messages['fr'][] = 'Mise à jour';
        $this->messages['de'][] = 'Updater';

        $this->messages['en'][] = 'Current';
        $this->messages['pt'][] = 'Atual';
        $this->messages['es'][] = 'Actual';
        $this->messages['it'][] = 'Attuale';
        $this->messages['fr'][] = 'Actuel';
        $this->messages['de'][] = 'Aktuell';

        $this->messages['en'][] = 'New';
        $this->messages['pt'][] = 'Novo';
        $this->messages['es'][] = 'Nuevo';
        $this->messages['it'][] = 'Nuovo';
        $this->messages['fr'][] = 'Nouveau';
        $this->messages['de'][] = 'Neu';

        $this->messages['en'][] = 'Only apply updates to a development server, never update directly to production. Always make backups before updates and test them thoroughly before promoting them to production';
        $this->messages['pt'][] = 'Somente aplique atualizações em servidor de desenvolvimento, jamais faça atualização diretamente em produção. Sempre faça backups antes das atualizações e teste-as exaustivamente antes de promovê-las à produção';
        $this->messages['es'][] = 'Aplique actualizaciones únicamente en el servidor de desarrollo, nunca actualice directamente en producción. Realice siempre copias de seguridad antes de las actualizaciones y pruébelas minuciosamente antes de pasarlas a producción';
        $this->messages['it'][] = 'Applica gli aggiornamenti solo su un server di sviluppo, mai direttamente in produzione. Esegui sempre backup prima degli aggiornamenti e testali accuratamente prima di passarli in produzione';
        $this->messages['fr'][] = 'Appliquez les mises à jour uniquement sur un serveur de développement, jamais directement en production. Effectuez toujours des sauvegardes avant les mises à jour et testez-les minutieusement avant de les déployer en production';
        $this->messages['de'][] = 'Updates nur auf einem Entwicklungsserver anwenden, niemals direkt in der Produktion. Immer Backups vor Updates erstellen und gründlich testen, bevor sie in die Produktion übernommen werden';

        $this->messages['en'][] = 'If you select a directory, all its files will automatically be updated, even if they are not selected';
        $this->messages['pt'][] = 'Se você selecionar um diretório, automaticamente todos seus arquivos serão atualizados, mesmo que eles não estejam selecionados';
        $this->messages['es'][] = 'Si selecciona un directorio, automáticamente se actualizarán todos sus archivos, incluso si no están seleccionados';
        $this->messages['it'][] = 'Se selezioni una directory, tutti i suoi file verranno aggiornati automaticamente, anche se non sono selezionati';
        $this->messages['fr'][] = 'Si vous sélectionnez un répertoire, tous ses fichiers seront automatiquement mis à jour, même s’ils ne sont pas sélectionnés';
        $this->messages['de'][] = 'Wenn Sie ein Verzeichnis auswählen, werden alle darin enthaltenen Dateien automatisch aktualisiert, auch wenn sie nicht ausgewählt sind';

        $this->messages['en'][] = 'Third party packages';
        $this->messages['pt'][] = 'Pacotes de terceiros';
        $this->messages['es'][] = 'Paquetes de terceros';
        $this->messages['it'][] = 'Pacchetti di terze parti';
        $this->messages['fr'][] = 'Packages tiers';
        $this->messages['de'][] = 'Pakete von Drittanbietern';


        $this->messages['en'][] = 'If the application does not load after a Framework update, it will be necessary to download it completely again';
        $this->messages['pt'][] = 'Caso a aplicação não carregue após uma atualização de Framework, será necessário realizar novamente o download completo da mesma';
        $this->messages['es'][] = 'Si la aplicación no se carga después de una actualización de Framework, será necesario descargarla completamente nuevamente';
        $this->messages['it'][] = 'Se l\'applicazione non si carica dopo un aggiornamento del Framework, sarà necessario scaricarla completamente di nuovo';
        $this->messages['fr'][] = 'Si l\'application ne se charge pas après une mise à jour du Framework, il sera nécessaire de la télécharger à nouveau complètement';
        $this->messages['de'][] = 'Wenn die Anwendung nach einem Framework-Update nicht geladen wird, muss sie vollständig neu heruntergeladen werden';

        $this->messages['en'][] = 'Your browser does not support displaying this content';
        $this->messages['pt'][] = 'O navegador não suporta a exibição deste conteúdo';
        $this->messages['es'][] = 'El navegador no admite la visualización de este contenido';
        $this->messages['it'][] = 'Il tuo browser non supporta la visualizzazione di questo contenuto';
        $this->messages['fr'][] = 'Votre navigateur ne prend pas en charge l\'affichage de ce contenu';
        $this->messages['de'][] = 'Ihr Browser unterstützt die Anzeige dieses Inhalts nicht';

        $this->messages['en'][] = 'click here to download';
        $this->messages['pt'][] = 'clique aqui para baixar';
        $this->messages['es'][] = 'haga clic aquí para descargar';
        $this->messages['it'][] = 'clicca qui per scaricare';
        $this->messages['fr'][] = 'cliquez ici pour télécharger';
        $this->messages['de'][] = 'hier klicken zum Herunterladen';

        $this->messages['en'][] = '^1 not defined';
        $this->messages['pt'][] = '^1 não definido';
        $this->messages['es'][] = '^1 no definido';
        $this->messages['it'][] = '^1 non definito';
        $this->messages['fr'][] = '^1 non défini';
        $this->messages['de'][] = '^1 nicht definiert';

        $this->messages['en'][] = 'Invalid captcha';
        $this->messages['pt'][] = 'Captcha inválido';
        $this->messages['es'][] = 'Captcha inválido';
        $this->messages['it'][] = 'Captcha non valido';
        $this->messages['fr'][] = 'Captcha invalide';
        $this->messages['de'][] = 'Ungültiges Captcha';

        $this->messages['en'][] = 'Data added successfully';
        $this->messages['pt'][] = 'Dados adicionados com sucesso';
        $this->messages['es'][] = 'Datos añadidos con éxito';
        $this->messages['it'][] = 'Dati aggiunti con successo';
        $this->messages['fr'][] = 'Données ajoutées avec succès';
        $this->messages['de'][] = 'Daten erfolgreich hinzugefügt';

        $this->messages['en'][] = 'Data removed successfully';
        $this->messages['pt'][] = 'Dados removidos com sucesso';
        $this->messages['es'][] = 'Datos eliminados con éxito';
        $this->messages['it'][] = 'Dati rimossi con successo';
        $this->messages['fr'][] = 'Données supprimées avec succès';
        $this->messages['de'][] = 'Daten erfolgreich entfernt';

        $this->messages['en'][] = 'Composition not found between ^1 and ^2';
        $this->messages['pt'][] = 'Composição não encontrada entre ^1 e ^2';
        $this->messages['es'][] = 'Composición no encontrada entre ^1 y ^2';
        $this->messages['it'][] = 'Composizione non trovata tra ^1 e ^2';
        $this->messages['fr'][] = 'Composition non trouvée entre ^1 et ^2';
        $this->messages['de'][] = 'Komposition zwischen ^1 und ^2 nicht gefunden';

        $this->messages['en'][] = 'Association not found between ^1 and ^2';
        $this->messages['pt'][] = 'Associação não encontrada entre ^1 e ^2';
        $this->messages['es'][] = 'Asociación no encontrada entre ^1 y ^2';
        $this->messages['it'][] = 'Associazione non trovata tra ^1 e ^2';
        $this->messages['fr'][] = 'Association non trouvée entre ^1 et ^2';
        $this->messages['de'][] = 'Verknüpfung zwischen ^1 und ^2 nicht gefunden';

        $this->messages['en'][] = 'Aggregation not found between ^1 and ^2';
        $this->messages['pt'][] = 'Agregação não encontrada entre ^1 e ^2';
        $this->messages['es'][] = 'Agregación no encontrada entre ^1 y ^2';
        $this->messages['it'][] = 'Aggregazione non trovata tra ^1 e ^2';
        $this->messages['fr'][] = 'Agrégation non trouvée entre ^1 et ^2';
        $this->messages['de'][] = 'Aggregation zwischen ^1 und ^2 nicht gefunden';

        $this->messages['en'][] = 'Relationship (composition, dependency) not found between ^1 and ^2';
        $this->messages['pt'][] = 'Relacionamento (composição, dependência) não encontrado entre ^1 e ^2';
        $this->messages['es'][] = 'Relación (composición, dependencia) no encontrado entre ^1 y ^2';
        $this->messages['it'][] = 'Relazione (composizione, dipendenza) non trovata tra ^1 e ^2';
        $this->messages['fr'][] = 'Relation (composition, dépendance) non trouvée entre ^1 et ^2';
        $this->messages['de'][] = 'Beziehung (Komposition, Abhängigkeit) zwischen ^1 und ^2 nicht gefunden';

        $this->messages['en'][] = 'Review the relationships between classes';
        $this->messages['pt'][] = 'Reveja os relacionamentos entre as classes';
        $this->messages['es'][] = 'Revisar las relaciones entre clases';
        $this->messages['it'][] = 'Rivedi le relazioni tra le classi';
        $this->messages['fr'][] = 'Revoir les relations entre les classes';
        $this->messages['de'][] = 'Überprüfen Sie die Beziehungen zwischen den Klassen';

        $this->messages['en'][] = 'Model (stage)';
        $this->messages['pt'][] = 'Modelo (etapa)';
        $this->messages['es'][] = 'Modelo (etapa)';
        $this->messages['it'][] = 'Modello (fase)';
        $this->messages['fr'][] = 'Modèle (étape)';
        $this->messages['de'][] = 'Modell (Phase)';

        $this->messages['en'][] = 'Title (stage)';
        $this->messages['pt'][] = 'Título (etapa)';
        $this->messages['es'][] = 'Título (etapa)';
        $this->messages['it'][] = 'Titolo (fase)';
        $this->messages['fr'][] = 'Titre (étape)';
        $this->messages['de'][] = 'Titel (Phase)';

        $this->messages['en'][] = 'Order (stage)';
        $this->messages['pt'][] = 'Ordem (etapa)';
        $this->messages['es'][] = 'Ordem (etapa)';
        $this->messages['it'][] = 'Ordine (fase)';
        $this->messages['fr'][] = 'Ordre (étape)';
        $this->messages['de'][] = 'Reihenfolge (Phase)';

        $this->messages['en'][] = 'Start column';
        $this->messages['pt'][] = 'Coluna de início';
        $this->messages['es'][] = 'Columna de inicio';
        $this->messages['it'][] = 'Colonna di inizio';
        $this->messages['fr'][] = 'Colonne de départ';
        $this->messages['de'][] = 'Startspalte';

                
        $this->messages['en'][] = 'End column';
        $this->messages['pt'][] = 'Coluna de fim';
        $this->messages['es'][] = 'Columna final';
        $this->messages['it'][] = 'Colonna di fine';
        $this->messages['fr'][] = 'Colonne de fin';
        $this->messages['de'][] = 'Endspalte';

        $this->messages['en'][] = 'Color column';
        $this->messages['pt'][] = 'Coluna de cor';
        $this->messages['es'][] = 'Columna de color';
        $this->messages['it'][] = 'Colonna colore';
        $this->messages['fr'][] = 'Colonne de couleur';
        $this->messages['de'][] = 'Farbspalte';

        $this->messages['en'][] = 'Title column';
        $this->messages['pt'][] = 'Coluna de título';
        $this->messages['es'][] = 'Columna de título';
        $this->messages['it'][] = 'Colonna del titolo';
        $this->messages['fr'][] = 'Colonne de titre';
        $this->messages['de'][] = 'Titelsäule';

        $this->messages['en'][] = 'Connector';
        $this->messages['pt'][] = 'Conector';
        $this->messages['es'][] = 'Conector';
        $this->messages['it'][] = 'Connettore';
        $this->messages['fr'][] = 'Connecteur';
        $this->messages['de'][] = 'Verbinder';

        $this->messages['en'][] = 'Identification field';
        $this->messages['pt'][] = 'Campo de identificação';
        $this->messages['es'][] = 'Campo de identificación';
        $this->messages['it'][] = 'Campo di identificazione';
        $this->messages['fr'][] = 'Champ d’identification';
        $this->messages['de'][] = 'Identifikationsfeld';

        $this->messages['en'][] = 'Display field';
        $this->messages['pt'][] = 'Campo de apresentação';
        $this->messages['es'][] = 'Campo de presentación';
        $this->messages['it'][] = 'Campo di visualizzazione';
        $this->messages['fr'][] = 'Champ d’affichage';
        $this->messages['de'][] = 'Anzeigefeld';

        $this->messages['en'][] = 'Required properties';
        $this->messages['pt'][] = 'Propriedades necessárias';
        $this->messages['es'][] = 'Propiedades requeridas';
        $this->messages['it'][] = 'Proprietà richieste';
        $this->messages['fr'][] = 'Propriétés requises';
        $this->messages['de'][] = 'Erforderliche Eigenschaften';

        $this->messages['en'][] = 'shown just for administrator';
        $this->messages['pt'][] = 'exibido apenas para administrador';
        $this->messages['es'][] = 'se muestra solo para administrador';
        $this->messages['it'][] = 'mostrato solo all’amministratore';
        $this->messages['fr'][] = 'affiché uniquement pour l’administrateur';
        $this->messages['de'][] = 'nur für Administrator angezeigt';

        $this->messages['en'][] = 'The datagrid has no valid columns';
        $this->messages['pt'][] = 'A datagrid não tem colunas válidas';
        $this->messages['es'][] = 'La Datagrid no tiene columnas válidas';
        $this->messages['it'][] = 'La datagrid non ha colonne valide';
        $this->messages['fr'][] = 'La grille de données n’a pas de colonnes valides';
        $this->messages['de'][] = 'Das Datenraster hat keine gültigen Spalten';

        $this->messages['en'][] = 'Session Closed';
        $this->messages['pt'][] = 'Sessão Encerrada';
        $this->messages['es'][] = 'Sesión cerrada';
        $this->messages['it'][] = 'Sessione chiusa';
        $this->messages['fr'][] = 'Session fermée';
        $this->messages['de'][] = 'Sitzung geschlossen';

        $this->messages['en'][] = 'We have verified that your account was accessed in another session. Since our application does not allow concurrent logins, you were automatically logged out of this session. Please log in again to continue using our services';
        $this->messages['pt'][] = 'Notamos que sua conta foi acessada em outra sessão. Como nossa aplicação não permite logins concorrentes, você foi desconectado automaticamente desta sessão. Por favor, efetue o login novamente para continuar utilizando nossos serviços';
        $this->messages['es'][] = 'Hemos verificado que se accedió a su cuenta en otra sesión. Dado que nuestra aplicación no permite inicios de sesión simultáneos, se cerró automáticamente su sesión en esta. Inicie sesión nuevamente para continuar utilizando nuestros servicios';
        $this->messages['it'][] = 'Abbiamo verificato che il tuo account è stato utilizzato in un’altra sessione. Poiché la nostra applicazione non consente accessi simultanei, sei stato disconnesso automaticamente da questa sessione. Effettua nuovamente l’accesso per continuare a utilizzare i nostri servizi';
        $this->messages['fr'][] = 'Nous avons constaté que votre compte a été utilisé dans une autre session. Comme notre application ne permet pas les connexions simultanées, vous avez été automatiquement déconnecté de cette session. Veuillez vous reconnecter pour continuer à utiliser nos services';
        $this->messages['de'][] = 'Wir haben festgestellt, dass Ihr Konto in einer anderen Sitzung verwendet wurde. Da unsere Anwendung keine gleichzeitigen Anmeldungen erlaubt, wurden Sie automatisch von dieser Sitzung abgemeldet. Bitte melden Sie sich erneut an, um unsere Dienste weiter zu nutzen';

        $this->messages['en'][] = 'Select files and folders to be updated';
        $this->messages['pt'][] = 'Selecione os arquivos e pastas a serem atualizados';
        $this->messages['es'][] = 'Seleccionar archivos y carpetas que se actualizarán';
        $this->messages['it'][] = 'Seleziona i file e le cartelle da aggiornare';
        $this->messages['fr'][] = 'Sélectionnez les fichiers et dossiers à mettre à jour';
        $this->messages['de'][] = 'Wählen Sie Dateien und Ordner aus, die aktualisiert werden sollen';

        $this->messages['en'][] = 'Apply updates';
        $this->messages['pt'][] = 'Aplicar atualizações';
        $this->messages['es'][] = 'Aplicar actualizaciones';
        $this->messages['it'][] = 'Applica aggiornamenti';
        $this->messages['fr'][] = 'Appliquer les mises à jour';
        $this->messages['de'][] = 'Updates anwenden';

        $this->messages['en'][] = 'Local version (in use)';
        $this->messages['pt'][] = 'Versão local (em uso)';
        $this->messages['es'][] = 'Versión local (en uso)';
        $this->messages['it'][] = 'Versione locale (in uso)';
        $this->messages['fr'][] = 'Version locale (en cours d’utilisation)';
        $this->messages['de'][] = 'Lokale Version (in Verwendung)';

        $this->messages['en'][] = 'Updated version (new)';
        $this->messages['pt'][] = 'Versão atualizada (nova)';
        $this->messages['es'][] = 'Versión actualizada (nueva)';
        $this->messages['it'][] = 'Versione aggiornata (nuova)';
        $this->messages['fr'][] = 'Version mise à jour (nouvelle)';
        $this->messages['de'][] = 'Aktualisierte Version (neu)';

        $this->messages['en'][] = 'Duplicated';
        $this->messages['pt'][] = 'Duplicado';
        $this->messages['es'][] = 'Duplicado';
        $this->messages['it'][] = 'Duplicato';
        $this->messages['fr'][] = 'Dupliqué';
        $this->messages['de'][] = 'Dupliziert';

        $this->messages['en'][] = 'File tree';
        $this->messages['pt'][] = 'Árvore de arquivos';
        $this->messages['es'][] = 'Árbol de archivos';
        $this->messages['it'][] = 'Albero dei file';
        $this->messages['fr'][] = 'Arborescence des fichiers';
        $this->messages['de'][] = 'Dateibaum';

        $this->messages['en'][] = 'Problems found';
        $this->messages['pt'][] = 'Problemas encontrados';
        $this->messages['es'][] = 'Problemas encontrados';
        $this->messages['it'][] = 'Problemi trovati';
        $this->messages['fr'][] = 'Problèmes trouvés';
        $this->messages['de'][] = 'Gefundene Probleme';

        $this->messages['en'][] = 'No duplicates found';
        $this->messages['pt'][] = 'Não encontrou duplicados';
        $this->messages['es'][] = 'No se encontraron duplicados';
        $this->messages['it'][] = 'Nessun duplicato trovato';
        $this->messages['fr'][] = 'Aucun doublon trouvé';
        $this->messages['de'][] = 'Keine Duplikate gefunden';

                
        $this->messages['en'][] = 'Last backups from applied patches';
        $this->messages['pt'][] = 'Últimos backups de patches aplicados';
        $this->messages['es'][] = 'Últimas copias de seguridad a partir de parches aplicados';
        $this->messages['it'][] = 'Ultimi backup dalle patch applicate';
        $this->messages['fr'][] = 'Dernières sauvegardes des correctifs appliqués';
        $this->messages['de'][] = 'Letzte Backups aus angewendeten Patches';

        $this->messages['en'][] = 'Duplicate files cause conflicts in the class loader. You should not repeat the file name and class name';
        $this->messages['pt'][] = 'Arquivos duplicados causam conflitos no class loader. Você não deve repetir o nome do arquivo e o nome da classe';
        $this->messages['es'][] = 'Los archivos duplicados provocan conflictos en el cargador de clases. No debe repetir el nombre del archivo y el nombre de la clase';
        $this->messages['it'][] = 'I file duplicati causano conflitti nel caricatore di classi. Non dovresti ripetere il nome del file e della classe';
        $this->messages['fr'][] = 'Les fichiers en double provoquent des conflits dans le chargeur de classes. Vous ne devez pas répéter le nom du fichier et celui de la classe';
        $this->messages['de'][] = 'Doppelte Dateien verursachen Konflikte im Klassenlader. Dateiname und Klassenname sollten nicht wiederholt werden';

        $this->messages['en'][] = 'There must be a /backups folder with write permissions to apply the patch';
        $this->messages['pt'][] = 'Deve existir uma pasta /backups com permissão de escrita para poder aplicar o patch';
        $this->messages['es'][] = 'Debe haber una carpeta /backups con permisos de escritura para aplicar el parche';
        $this->messages['it'][] = 'Deve esserci una cartella /backups con permessi di scrittura per applicare la patch';
        $this->messages['fr'][] = 'Il doit y avoir un dossier /backups avec des droits d’écriture pour appliquer le correctif';
        $this->messages['de'][] = 'Es muss ein /backups-Ordner mit Schreibrechten vorhanden sein, um den Patch anzuwenden';

        $this->messages['en'][] = 'To enable scheduling, add the following line to crontab (Linux)';
        $this->messages['pt'][] = 'Para habilitar os agendamentos, adicione a seguinte linha na crontab (Linux)';
        $this->messages['es'][] = 'Para habilitar la programación, agregue la siguiente línea a crontab (Linux)';
        $this->messages['it'][] = 'Per abilitare la pianificazione, aggiungi la seguente riga a crontab (Linux)';
        $this->messages['fr'][] = 'Pour activer la planification, ajoutez la ligne suivante à crontab (Linux)';
        $this->messages['de'][] = 'Um die Planung zu aktivieren, fügen Sie die folgende Zeile zur Crontab hinzu (Linux)';

        $this->messages['en'][] = 'Execute';
        $this->messages['pt'][] = 'Executar';
        $this->messages['es'][] = 'Ejecutar';
        $this->messages['it'][] = 'Esegui';
        $this->messages['fr'][] = 'Exécuter';
        $this->messages['de'][] = 'Ausführen';

        $this->messages['en'][] = 'Records imported successfully';
        $this->messages['pt'][] = 'Registros importados com sucesso';
        $this->messages['es'][] = 'Registros importados exitosamente';
        $this->messages['it'][] = 'Record importati con successo';
        $this->messages['fr'][] = 'Enregistrements importés avec succès';
        $this->messages['de'][] = 'Datensätze erfolgreich importiert';

        $this->messages['en'][] = 'Import SQL';
        $this->messages['pt'][] = 'Importar SQL';
        $this->messages['es'][] = 'Importar SQL';
        $this->messages['it'][] = 'Importa SQL';
        $this->messages['fr'][] = 'Importer SQL';
        $this->messages['de'][] = 'SQL importieren';

        $this->messages['en'][] = 'Download as CSV';
        $this->messages['pt'][] = 'Baixar como CSV';
        $this->messages['es'][] = 'Descargar como CSV';
        $this->messages['it'][] = 'Scarica come CSV';
        $this->messages['fr'][] = 'Télécharger en CSV';
        $this->messages['de'][] = 'Als CSV herunterladen';

        $this->messages['en'][] = 'Download as SQL';
        $this->messages['pt'][] = 'Baixar como SQL';
        $this->messages['es'][] = 'Descargar como SQL';
        $this->messages['it'][] = 'Scarica come SQL';
        $this->messages['fr'][] = 'Télécharger en SQL';
        $this->messages['de'][] = 'Als SQL herunterladen';

        $this->messages['en'][] = 'Open in new tab';
        $this->messages['pt'][] = 'Abrir em nova aba';
        $this->messages['es'][] = 'Abrir en nueva pestaña';
        $this->messages['it'][] = 'Apri in una nuova scheda';
        $this->messages['fr'][] = 'Ouvrir dans un nouvel onglet';
        $this->messages['de'][] = 'In neuem Tab öffnen';

        $this->messages['en'][] = 'Close page';
        $this->messages['pt'][] = 'Fechar página';
        $this->messages['es'][] = 'Cerrar página';
        $this->messages['it'][] = 'Chiudi pagina';
        $this->messages['fr'][] = 'Fermer la page';
        $this->messages['de'][] = 'Seite schließen';

        $this->messages['en'][] = 'Field not found';
        $this->messages['pt'][] = 'Campo não encontrado';
        $this->messages['es'][] = 'Campo no encontrado';
        $this->messages['it'][] = 'Campo non trovato';
        $this->messages['fr'][] = 'Champ introuvable';
        $this->messages['de'][] = 'Feld nicht gefunden';

        $this->messages['en'][] = 'More options';
        $this->messages['pt'][] = 'Mais opções';
        $this->messages['es'][] = 'Más opciones';
        $this->messages['it'][] = 'Altre opzioni';
        $this->messages['fr'][] = 'Plus d’options';
        $this->messages['de'][] = 'Weitere Optionen';

        $this->messages['en'][] = 'Width';
        $this->messages['pt'][] = 'Largura';
        $this->messages['es'][] = 'Ancho';
        $this->messages['it'][] = 'Larghezza';
        $this->messages['fr'][] = 'Largeur';
        $this->messages['de'][] = 'Breite';

        $this->messages['en'][] = 'Align';
        $this->messages['pt'][] = 'Alinhamento';
        $this->messages['es'][] = 'Alineación';
        $this->messages['it'][] = 'Allineamento';
        $this->messages['fr'][] = 'Alignement';
        $this->messages['de'][] = 'Ausrichtung';

        $this->messages['en'][] = 'Visible';
        $this->messages['pt'][] = 'Visível';
        $this->messages['es'][] = 'Visible';
        $this->messages['it'][] = 'Visibile';
        $this->messages['fr'][] = 'Visible';
        $this->messages['de'][] = 'Sichtbar';

        $this->messages['en'][] = 'Left';
        $this->messages['pt'][] = 'Esquerda';
        $this->messages['es'][] = 'Izquierda';
        $this->messages['it'][] = 'Sinistra';
        $this->messages['fr'][] = 'Gauche';
        $this->messages['de'][] = 'Links';

        $this->messages['en'][] = 'Right';
        $this->messages['pt'][] = 'Direita';
        $this->messages['es'][] = 'Derecha';
        $this->messages['it'][] = 'Destra';
        $this->messages['fr'][] = 'Droite';
        $this->messages['de'][] = 'Rechts';

        $this->messages['en'][] = 'Center';
        $this->messages['pt'][] = 'Centro';
        $this->messages['es'][] = 'Centro';
        $this->messages['it'][] = 'Centro';
        $this->messages['fr'][] = 'Centre';
        $this->messages['de'][] = 'Zentriert';

        $this->messages['en'][] = 'Small';
        $this->messages['pt'][] = 'Pequeno';
        $this->messages['es'][] = 'Pequeño';
        $this->messages['it'][] = 'Piccolo';
        $this->messages['fr'][] = 'Petit';
        $this->messages['de'][] = 'Klein';

        $this->messages['en'][] = 'Medium';
        $this->messages['pt'][] = 'Médio';
        $this->messages['es'][] = 'Medio';
        $this->messages['it'][] = 'Medio';
        $this->messages['fr'][] = 'Moyen';
        $this->messages['de'][] = 'Mittel';

        $this->messages['en'][] = 'Large';
        $this->messages['pt'][] = 'Grande';
        $this->messages['es'][] = 'Grande';
        $this->messages['it'][] = 'Grande';
        $this->messages['fr'][] = 'Grand';
        $this->messages['de'][] = 'Groß';

        $this->messages['en'][] = 'Very small';
        $this->messages['pt'][] = 'Muito pequena';
        $this->messages['es'][] = 'Muy pequeña';
        $this->messages['it'][] = 'Molto piccola';
        $this->messages['fr'][] = 'Très petit';
        $this->messages['de'][] = 'Sehr klein';

                
        $this->messages['en'][] = 'Very large';
        $this->messages['pt'][] = 'Muito grande';
        $this->messages['es'][] = 'Muy grande';
        $this->messages['it'][] = 'Molto grande';
        $this->messages['fr'][] = 'Très grand';
        $this->messages['de'][] = 'Sehr groß';

        $this->messages['en'][] = 'Configure columns';
        $this->messages['pt'][] = 'Configurar colunas';
        $this->messages['es'][] = 'Configurar columnas';
        $this->messages['it'][] = 'Configura colonne';
        $this->messages['fr'][] = 'Configurer les colonnes';
        $this->messages['de'][] = 'Spalten konfigurieren';

        $this->messages['en'][] = 'There must be at least one visible column';
        $this->messages['pt'][] = 'Deve haver pelo menos uma coluna visível';
        $this->messages['es'][] = 'Debe haber al menos una columna visible';
        $this->messages['it'][] = 'Deve esserci almeno una colonna visibile';
        $this->messages['fr'][] = 'Il doit y avoir au moins une colonne visible';
        $this->messages['de'][] = 'Es muss mindestens eine sichtbare Spalte vorhanden sein';

        $this->messages['en'][] = 'Apply and close';
        $this->messages['pt'][] = 'Aplicar e fechar';
        $this->messages['es'][] = 'Aplicar y cerrar';
        $this->messages['it'][] = 'Applica e chiudi';
        $this->messages['fr'][] = 'Appliquer et fermer';
        $this->messages['de'][] = 'Anwenden und schließen';

        $this->messages['en'][] = 'More filters';
        $this->messages['pt'][] = 'Mais filtros';
        $this->messages['es'][] = 'Más filtros';
        $this->messages['it'][] = 'Altri filtri';
        $this->messages['fr'][] = 'Plus de filtres';
        $this->messages['de'][] = 'Weitere Filter';

        $this->messages['en'][] = 'Custom filters';
        $this->messages['pt'][] = 'Filtros personalizados';
        $this->messages['es'][] = 'Filtros personalizados';
        $this->messages['it'][] = 'Filtri personalizzati';
        $this->messages['fr'][] = 'Filtres personnalisés';
        $this->messages['de'][] = 'Benutzerdefinierte Filter';

        $this->messages['en'][] = 'Operator';
        $this->messages['pt'][] = 'Operador';
        $this->messages['es'][] = 'Operador';
        $this->messages['it'][] = 'Operatore';
        $this->messages['fr'][] = 'Opérateur';
        $this->messages['de'][] = 'Operator';

        $this->messages['en'][] = 'Is contained by';
        $this->messages['pt'][] = 'Está contido em';
        $this->messages['es'][] = 'Está contenido por';
        $this->messages['it'][] = 'È contenuto in';
        $this->messages['fr'][] = 'Est contenu dans';
        $this->messages['de'][] = 'Ist enthalten in';

        $this->messages['en'][] = 'Is not contained by';
        $this->messages['pt'][] = 'Não está contido em';
        $this->messages['es'][] = 'No está contenido por';
        $this->messages['it'][] = 'Non è contenuto in';
        $this->messages['fr'][] = 'N’est pas contenu dans';
        $this->messages['de'][] = 'Ist nicht enthalten in';

        $this->messages['en'][] = 'Contains the expression';
        $this->messages['pt'][] = 'Contém a expressão';
        $this->messages['es'][] = 'Contiene la expresión';
        $this->messages['it'][] = 'Contiene l’espressione';
        $this->messages['fr'][] = 'Contient l’expression';
        $this->messages['de'][] = 'Enthält den Ausdruck';

        $this->messages['en'][] = 'Contains';
        $this->messages['pt'][] = 'Contém';
        $this->messages['es'][] = 'Contiene';
        $this->messages['it'][] = 'Contiene';
        $this->messages['fr'][] = 'Contient';
        $this->messages['de'][] = 'Enthält';

        $this->messages['en'][] = 'Does not contains the expression';
        $this->messages['pt'][] = 'Não contém a expressão';
        $this->messages['es'][] = 'No contiene la expresión';
        $this->messages['it'][] = 'Non contiene l’espressione';
        $this->messages['fr'][] = 'Ne contient pas l’expression';
        $this->messages['de'][] = 'Enthält den Ausdruck nicht';

        $this->messages['en'][] = 'Your browser does not support displaying this content';
        $this->messages['pt'][] = 'O navegador não suporta a exibição deste conteúdo';
        $this->messages['es'][] = 'Su navegador no admite la visualización de este contenido';
        $this->messages['it'][] = 'Il tuo browser non supporta la visualizzazione di questo contenuto';
        $this->messages['fr'][] = 'Votre navigateur ne prend pas en charge l’affichage de ce contenu';
        $this->messages['de'][] = 'Ihr Browser unterstützt die Anzeige dieses Inhalts nicht';

        $this->messages['en'][] = 'Plugin not found';
        $this->messages['pt'][] = 'Plugin não encontrado';
        $this->messages['es'][] = 'Plugin no encontrado';
        $this->messages['it'][] = 'Plugin non trovato';
        $this->messages['fr'][] = 'Plugin introuvable';
        $this->messages['de'][] = 'Plugin nicht gefunden';

        $this->messages['en'][] = 'Empty plugin name';
        $this->messages['pt'][] = 'Nome do plugin vazio';
        $this->messages['es'][] = 'Nombre de plugin vacío';
        $this->messages['it'][] = 'Nome del plugin vuoto';
        $this->messages['fr'][] = 'Nom du plugin vide';
        $this->messages['de'][] = 'Plugin-Name leer';

        $this->messages['en'][] = 'Plugin already registered with this name';
        $this->messages['pt'][] = 'Plugin já registrado com este nome';
        $this->messages['es'][] = 'Plugin ya registrado con este nombre';
        $this->messages['it'][] = 'Plugin già registrato con questo nome';
        $this->messages['fr'][] = 'Plugin déjà enregistré avec ce nom';
        $this->messages['de'][] = 'Plugin bereits mit diesem Namen registriert';

        $this->messages['en'][] = 'Content field';
        $this->messages['pt'][] = 'Campo de conteúdo';
        $this->messages['es'][] = 'Campo de contenido';
        $this->messages['it'][] = 'Campo di contenuto';
        $this->messages['fr'][] = 'Champ de contenu';
        $this->messages['de'][] = 'Inhaltsfeld';

                
        $this->messages['en'][] = 'Created at field';
        $this->messages['pt'][] = 'Campo criado em';
        $this->messages['es'][] = 'Campo creado en';
        $this->messages['it'][] = 'Campo creato il';
        $this->messages['fr'][] = 'Champ créé le';
        $this->messages['de'][] = 'Feld erstellt am';

        $this->messages['en'][] = 'Created by field';
        $this->messages['pt'][] = 'Campo criado por';
        $this->messages['es'][] = 'Campo creado por';
        $this->messages['it'][] = 'Campo creato da';
        $this->messages['fr'][] = 'Champ créé par';
        $this->messages['de'][] = 'Feld erstellt von';

        $this->messages['en'][] = 'Recording model';
        $this->messages['pt'][] = 'Modelo de gravação';
        $this->messages['es'][] = 'Modelo de grabación';
        $this->messages['it'][] = 'Modello di registrazione';
        $this->messages['fr'][] = 'Modèle d’enregistrement';
        $this->messages['de'][] = 'Aufzeichnungsmodell';

        $this->messages['en'][] = 'File field';
        $this->messages['pt'][] = 'Campo de arquivo';
        $this->messages['es'][] = 'Campo de archivo';
        $this->messages['it'][] = 'Campo file';
        $this->messages['fr'][] = 'Champ de fichier';
        $this->messages['de'][] = 'Dateifeld';

        $this->messages['en'][] = 'New comment';
        $this->messages['pt'][] = 'Novo comentário';
        $this->messages['es'][] = 'Nuevo comentario';
        $this->messages['it'][] = 'Nuovo commento';
        $this->messages['fr'][] = 'Nouveau commentaire';
        $this->messages['de'][] = 'Neuer Kommentar';

        $this->messages['en'][] = 'Attach file';
        $this->messages['pt'][] = 'Anexar arquivo';
        $this->messages['es'][] = 'Adjuntar archivo';
        $this->messages['it'][] = 'Allega file';
        $this->messages['fr'][] = 'Joindre un fichier';
        $this->messages['de'][] = 'Datei anhängen';

        $this->messages['en'][] = 'Description field';
        $this->messages['pt'][] = 'Campo de descrição';
        $this->messages['es'][] = 'Campo de descripción';
        $this->messages['it'][] = 'Campo descrizione';
        $this->messages['fr'][] = 'Champ de description';
        $this->messages['de'][] = 'Beschreibungsfeld';

        $this->messages['en'][] = 'Minutes field';
        $this->messages['pt'][] = 'Campo para minutos';
        $this->messages['es'][] = 'Campo para minutos';
        $this->messages['it'][] = 'Campo minuti';
        $this->messages['fr'][] = 'Champ minutes';
        $this->messages['de'][] = 'Minutenfeld';

        $this->messages['en'][] = 'Register time';
        $this->messages['pt'][] = 'Registrar tempo';
        $this->messages['es'][] = 'Registrar el tiempo';
        $this->messages['it'][] = 'Registra tempo';
        $this->messages['fr'][] = 'Enregistrer le temps';
        $this->messages['de'][] = 'Zeit erfassen';

        $this->messages['en'][] = 'New item';
        $this->messages['pt'][] = 'Novo item';
        $this->messages['es'][] = 'Nuevo artículo';
        $this->messages['it'][] = 'Nuovo elemento';
        $this->messages['fr'][] = 'Nouvel élément';
        $this->messages['de'][] = 'Neues Element';

        $this->messages['en'][] = 'Checked field';
        $this->messages['pt'][] = 'Campo marcado';
        $this->messages['es'][] = 'Campo marcado';
        $this->messages['it'][] = 'Campo selezionato';
        $this->messages['fr'][] = 'Champ coché';
        $this->messages['de'][] = 'Markiertes Feld';

        $this->messages['en'][] = 'Save method';
        $this->messages['pt'][] = 'Método de gravação';
        $this->messages['es'][] = 'Método de grabación';
        $this->messages['it'][] = 'Metodo di salvataggio';
        $this->messages['fr'][] = 'Méthode d’enregistrement';
        $this->messages['de'][] = 'Speichermethode';

        $this->messages['en'][] = 'Delete method';
        $this->messages['pt'][] = 'Método de exclusão';
        $this->messages['es'][] = 'Método de eliminación';
        $this->messages['it'][] = 'Metodo di eliminazione';
        $this->messages['fr'][] = 'Méthode de suppression';
        $this->messages['de'][] = 'Löschmethode';

        $this->messages['en'][] = 'Update method';
        $this->messages['pt'][] = 'Método de alteração';
        $this->messages['es'][] = 'Método de actualización';
        $this->messages['it'][] = 'Metodo di aggiornamento';
        $this->messages['fr'][] = 'Méthode de mise à jour';
        $this->messages['de'][] = 'Aktualisierungsmethode';

        $this->messages['en'][] = 'Theme not supported';
        $this->messages['pt'][] = 'Tema não suportado';
        $this->messages['es'][] = 'Tema no compatible';
        $this->messages['it'][] = 'Tema non supportato';
        $this->messages['fr'][] = 'Thème non pris en charge';
        $this->messages['de'][] = 'Thema nicht unterstützt';

        $this->messages['en'][] = 'There are important updates in the following folders that have been pre-selected for update';
        $this->messages['pt'][] = 'Existem atualizações importantes nas seguintes pastas que foram pré-selecionadas para atualização';
        $this->messages['es'][] = 'Hay actualizaciones importantes en las siguientes carpetas que han sido preseleccionadas para actualizar';
        $this->messages['it'][] = 'Ci sono aggiornamenti importanti nelle seguenti cartelle che sono state preselezionate per l’aggiornamento';
        $this->messages['fr'][] = 'Des mises à jour importantes sont disponibles dans les dossiers suivants, pré-sélectionnés pour la mise à jour';
        $this->messages['de'][] = 'In den folgenden Ordnern gibt es wichtige Updates, die zur Aktualisierung vorausgewählt wurden';

        $this->messages['en'][] = 'Metric';
        $this->messages['pt'][] = 'Métrica';
        $this->messages['es'][] = 'Métrica';
        $this->messages['it'][] = 'Metrica';
        $this->messages['fr'][] = 'Métrique';
        $this->messages['de'][] = 'Metrik';

        $this->messages['en'][] = 'Association not found for ^1';
        $this->messages['pt'][] = 'Associação não encontrada para ^1';
        $this->messages['es'][] = 'Asociación no encontrada para ^1';
        $this->messages['it'][] = 'Associazione non trovata per ^1';
        $this->messages['fr'][] = 'Association non trouvée pour ^1';
        $this->messages['de'][] = 'Verknüpfung für ^1 nicht gefunden';

        $this->messages['en'][] = 'No dashboard controls found';
        $this->messages['pt'][] = 'Controles de dashboard não encontrados';
        $this->messages['es'][] = 'Controles del panel no encontrados';
        $this->messages['it'][] = 'Controlli dashboard non trovati';
        $this->messages['fr'][] = 'Contrôles du tableau de bord non trouvés';
        $this->messages['de'][] = 'Keine Dashboard-Steuerelemente gefunden';

        $this->messages['en'][] = '^1 must be unique';
        $this->messages['pt'][] = '^1 deve ser único';
        $this->messages['es'][] = '^1 debe ser único';
        $this->messages['it'][] = '^1 deve essere univoco';
        $this->messages['fr'][] = '^1 doit être unique';
        $this->messages['de'][] = '^1 muss eindeutig sein';

        $this->messages['en'][] = 'Name must finish with []';
        $this->messages['pt'][] = 'O nome deve terminar com []';
        $this->messages['es'][] = 'El nombre debe terminar con []';
        $this->messages['it'][] = 'Il nome deve terminare con []';
        $this->messages['fr'][] = 'Le nom doit se terminer par []';
        $this->messages['de'][] = 'Der Name muss mit [] enden';

        $this->messages['en'][] = 'Collapse';
        $this->messages['pt'][] = 'Recolher';
        $this->messages['es'][] = 'Contraer';
        $this->messages['it'][] = 'Comprimi';
        $this->messages['fr'][] = 'Réduire';
        $this->messages['de'][] = 'Einklappen';
        
        $this->messages['en'][] = 'Copy';
        $this->messages['pt'][] = 'Copiar';
        $this->messages['es'][] = 'Copiar';
        $this->messages['it'][] = 'Copia';
        $this->messages['fr'][] = 'Copier';
        $this->messages['de'][] = 'Kopieren';
        
        foreach ($this->messages as $lang => $messages)
        {
            $this->sourceMessages[$lang] = array_flip( $this->messages[ $lang ] );
        }
    }
    
    /**
     * Returns the singleton instance
     * @return  Instance of self
     */
    public static function getInstance()
    {
        // if there's no instance
        if (empty(self::$instance))
        {
            // creates a new object
            self::$instance = new self;
        }
        // returns the created instance
        return self::$instance;
    }
    
    /**
     * Define the target language
     * @param $lang Target language index
     */
    public static function setLanguage($lang)
    {
        $instance = self::getInstance();
        
        if (in_array($lang, array_keys($instance->messages)))
        {
            $instance->lang = $lang;
        }
    }
    
    /**
     * Returns the target language
     * @return Target language index
     */
    public static function getLanguage()
    {
        $instance = self::getInstance();
        return $instance->lang;
    }
    
    /**
     * Translate a word to the target language
     * @param $word     Word to be translated
     * @return          Translated word
     */
    public static function translate($word, $source_language, $param1 = NULL, $param2 = NULL, $param3 = NULL, $param4 = NULL)
    {
        // get the self unique instance
        $instance = self::getInstance();
        // search by the numeric index of the word
        
        if (isset($instance->sourceMessages[$source_language][$word]) and !is_null($instance->sourceMessages[$source_language][$word]))
        {
            $key = $instance->sourceMessages[$source_language][$word];
            
            // get the target language
            $language = self::getLanguage();
            // returns the translated word
            $message = $instance->messages[$language][$key];
            
            if (isset($param1))
            {
                $message = str_replace('^1', $param1, $message);
            }
            if (isset($param2))
            {
                $message = str_replace('^2', $param2, $message);
            }
            if (isset($param3))
            {
                $message = str_replace('^3', $param3, $message);
            }
            if (isset($param4))
            {
                $message = str_replace('^4', $param4, $message);
            }
            return $message;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Translate a template file
     */
    public static function translateTemplate($template)
    {
        // search by translated words
        if(preg_match_all( '!_t\{(.*?)\}!i', $template, $match ) > 0)
        {
            foreach($match[1] as $word)
            {
                $translated = _t($word);
                $template = str_replace('_t{'.$word.'}', $translated, $template);
            }
        }
        
        if(preg_match_all( '!_tf\{(.*?), (.*?)\}!i', $template, $matches ) > 0)
        {
            foreach($matches[0] as $key => $match)
            {
                $raw        = $matches[0][$key];
                $word       = $matches[1][$key];
                $from       = $matches[2][$key];
                $translated = _tf($word, $from);
                $template = str_replace($raw, $translated, $template);
            }
        }
        return $template;
    }
}
