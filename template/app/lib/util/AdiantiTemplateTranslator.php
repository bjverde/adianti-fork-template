<?php
use Adianti\Core\AdiantiCoreTranslator;

/**
 * AdiantiTemplateTranslator
 *
 * @version    8.0
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
        
        $this->messages['en'][] = 'File not found';
        $this->messages['pt'][] = 'Arquivo não encontrado';
        $this->messages['es'][] = 'Archivo no encontrado';

        $this->messages['en'][] = 'Search';
        $this->messages['pt'][] = 'Buscar';
        $this->messages['es'][] = 'Buscar';

        $this->messages['en'][] = 'Register';
        $this->messages['pt'][] = 'Cadastrar';
        $this->messages['es'][] = 'Registrar';

        $this->messages['en'][] = 'Record saved';
        $this->messages['pt'][] = 'Registro salvo';
        $this->messages['es'][] = 'Registro guardado';

        $this->messages['en'][] = 'Do you really want to delete ?';
        $this->messages['pt'][] = 'Deseja realmente excluir ?';
        $this->messages['es'][] = 'Deseas realmente eliminar ?';

        $this->messages['en'][] = 'Record deleted';
        $this->messages['pt'][] = 'Registro excluído';
        $this->messages['es'][] = 'Registro eliminado';

        $this->messages['en'][] = 'Function';
        $this->messages['pt'][] = 'Função';
        $this->messages['es'][] = 'Función';

        $this->messages['en'][] = 'Table';
        $this->messages['pt'][] = 'Tabela';
        $this->messages['es'][] = 'Tabla';

        $this->messages['en'][] = 'Tool';
        $this->messages['pt'][] = 'Ferramenta';
        $this->messages['es'][] = 'Herramienta';

        $this->messages['en'][] = 'Data';
        $this->messages['pt'][] = 'Dados';
        $this->messages['es'][] = 'Datos';

        $this->messages['en'][] = 'Open';
        $this->messages['pt'][] = 'Abrir';
        $this->messages['es'][] = 'Abrir';

        $this->messages['en'][] = 'New';
        $this->messages['pt'][] = 'Novo';
        $this->messages['es'][] = 'Nuevo';

        $this->messages['en'][] = 'Save';
        $this->messages['pt'][] = 'Salvar';
        $this->messages['es'][] = 'Guardar';

        $this->messages['en'][] = 'Find';
        $this->messages['pt'][] = 'Buscar';
        $this->messages['es'][] = 'Buscar';

        $this->messages['en'][] = 'Edit';
        $this->messages['pt'][] = 'Editar';
        $this->messages['es'][] = 'Modificar';

        $this->messages['en'][] = 'Delete';
        $this->messages['pt'][] = 'Excluir';
        $this->messages['es'][] = 'Eliminar';

        $this->messages['en'][] = 'Cancel';
        $this->messages['pt'][] = 'Cancelar';
        $this->messages['es'][] = 'Cancelar';

        $this->messages['en'][] = 'Yes';
        $this->messages['pt'][] = 'Sim';
        $this->messages['es'][] = 'Sí';

        $this->messages['en'][] = 'No';
        $this->messages['pt'][] = 'Não';
        $this->messages['es'][] = 'No';

        $this->messages['en'][] = 'January';
        $this->messages['pt'][] = 'Janeiro';
        $this->messages['es'][] = 'Enero';

        $this->messages['en'][] = 'February';
        $this->messages['pt'][] = 'Fevereiro';
        $this->messages['es'][] = 'Febrero';

        $this->messages['en'][] = 'March';
        $this->messages['pt'][] = 'Março';
        $this->messages['es'][] = 'Marzo';

        $this->messages['en'][] = 'April';
        $this->messages['pt'][] = 'Abril';
        $this->messages['es'][] = 'Abril';

        $this->messages['en'][] = 'May';
        $this->messages['pt'][] = 'Maio';
        $this->messages['es'][] = 'Mayo';

        $this->messages['en'][] = 'June';
        $this->messages['pt'][] = 'Junho';
        $this->messages['es'][] = 'Junio';

        $this->messages['en'][] = 'July';
        $this->messages['pt'][] = 'Julho';
        $this->messages['es'][] = 'Julio';

        $this->messages['en'][] = 'August';
        $this->messages['pt'][] = 'Agosto';
        $this->messages['es'][] = 'Agosto';

        $this->messages['en'][] = 'September';
        $this->messages['pt'][] = 'Setembro';
        $this->messages['es'][] = 'Septiembre';

        $this->messages['en'][] = 'October';
        $this->messages['pt'][] = 'Outubro';
        $this->messages['es'][] = 'Octubre';

        $this->messages['en'][] = 'November';
        $this->messages['pt'][] = 'Novembro';
        $this->messages['es'][] = 'Noviembre';

        $this->messages['en'][] = 'December';
        $this->messages['pt'][] = 'Dezembro';
        $this->messages['es'][] = 'Diciembre';

        $this->messages['en'][] = 'Today';
        $this->messages['pt'][] = 'Hoje';
        $this->messages['es'][] = 'Hoy';

        $this->messages['en'][] = 'Close';
        $this->messages['pt'][] = 'Fechar';
        $this->messages['es'][] = 'Cerrar';

        $this->messages['en'][] = 'The field ^1 can not be less than ^2 characters';
        $this->messages['pt'][] = 'O campo ^1 não pode ter menos de ^2 caracteres';
        $this->messages['es'][] = 'El campo ^1 no puede tener menos de ^2 caracteres';

        $this->messages['en'][] = 'The field ^1 can not be greater than ^2 characters';
        $this->messages['pt'][] = 'O campo ^1 não pode ter mais de ^2 caracteres';
        $this->messages['es'][] = 'El campo ^1 no puede tener mas de ^2 caracteres';

        $this->messages['en'][] = 'The field ^1 can not be less than ^2';
        $this->messages['pt'][] = 'O campo ^1 não pode ser menor que ^2';
        $this->messages['es'][] = 'El campo ^1 no puede ser menor que ^2';

        $this->messages['en'][] = 'The field ^1 can not be greater than ^2';
        $this->messages['pt'][] = 'O campo ^1 não pode ser maior que ^2';
        $this->messages['es'][] = 'El campo ^1 no puede ser mayor que ^2';

        $this->messages['en'][] = 'The field ^1 is required';
        $this->messages['pt'][] = 'O campo ^1 é obrigatório';
        $this->messages['es'][] = 'El campo ^1 es obligatorio';

        $this->messages['en'][] = 'The field ^1 has not a valid CNPJ';
        $this->messages['pt'][] = 'O campo ^1 não contém um CNPJ válido';
        $this->messages['es'][] = 'El campo ^1 no contiene un CNPJ válido';

        $this->messages['en'][] = 'The field ^1 has not a valid CPF';
        $this->messages['pt'][] = 'O campo ^1 não contém um CPF válido';
        $this->messages['es'][] = 'El campo ^1 no contiene un CPF válido';

        $this->messages['en'][] = 'The field ^1 contains an invalid e-mail';
        $this->messages['pt'][] = 'O campo ^1 contém um e-mail inválido';
        $this->messages['es'][] = 'El campo ^1 contiene um e-mail inválido';

        $this->messages['en'][] = 'Permission denied';
        $this->messages['pt'][] = 'Permissão negada';
        $this->messages['es'][] = 'Permiso denegado';

        $this->messages['en'][] = 'Generate';
        $this->messages['pt'][] = 'Gerar';
        $this->messages['es'][] = 'Generar';

        $this->messages['en'][] = 'List';
        $this->messages['pt'][] = 'Listar';
        $this->messages['es'][] = 'Listar';

        $this->messages['en'][] = 'Wrong password';
        $this->messages['pt'][] = 'Senha errada';
        $this->messages['es'][] = 'Contraseña incorrecta';

        $this->messages['en'][] = 'User not found';
        $this->messages['pt'][] = 'Usuário não encontrado';
        $this->messages['es'][] = 'Usuário no encontrado';

        $this->messages['en'][] = 'User';
        $this->messages['pt'][] = 'Usuário';
        $this->messages['es'][] = 'Usuário';

        $this->messages['en'][] = 'Users';
        $this->messages['pt'][] = 'Usuários';
        $this->messages['es'][] = 'Usuários';

        $this->messages['en'][] = 'Password';
        $this->messages['pt'][] = 'Senha';
        $this->messages['es'][] = 'Contraseña';

        $this->messages['en'][] = 'Login';
        $this->messages['pt'][] = 'Login';
        $this->messages['es'][] = 'Login';

        $this->messages['en'][] = 'Name';
        $this->messages['pt'][] = 'Nome';
        $this->messages['es'][] = 'Nombre';

        $this->messages['en'][] = 'Group';
        $this->messages['pt'][] = 'Grupo';
        $this->messages['es'][] = 'Grupo';

        $this->messages['en'][] = 'Groups';
        $this->messages['pt'][] = 'Grupos';
        $this->messages['es'][] = 'Grupos';

        $this->messages['en'][] = 'Program';
        $this->messages['pt'][] = 'Programa';
        $this->messages['es'][] = 'Programa';

        $this->messages['en'][] = 'Programs';
        $this->messages['pt'][] = 'Programas';
        $this->messages['es'][] = 'Programas';

        $this->messages['en'][] = 'Back to the listing';
        $this->messages['pt'][] = 'Voltar para a listagem';
        $this->messages['es'][] = 'Volver al listado';

        $this->messages['en'][] = 'Controller';
        $this->messages['pt'][] = 'Classe de controle';
        $this->messages['es'][] = 'Classe de control';

        $this->messages['en'][] = 'Email';
        $this->messages['pt'][] = 'Email';
        $this->messages['es'][] = 'Email';

        $this->messages['en'][] = 'Record Updated';
        $this->messages['pt'][] = 'Registro atualizado';
        $this->messages['es'][] = 'Registro actualizado';

        $this->messages['en'][] = 'Password confirmation';
        $this->messages['pt'][] = 'Confirma senha';
        $this->messages['es'][] = 'Confirme contraseña';

        $this->messages['en'][] = 'Front page';
        $this->messages['pt'][] = 'Tela inicial';
        $this->messages['es'][] = 'Pantalla inicial';

        $this->messages['en'][] = 'Page name';
        $this->messages['pt'][] = 'Nome da Tela';
        $this->messages['es'][] = 'Nombre da la Pantalla';

        $this->messages['en'][] = 'The passwords do not match';
        $this->messages['pt'][] = 'As senhas não conferem';
        $this->messages['es'][] = 'Las contraseñas no conciden';

        $this->messages['en'][] = 'Log in';
        $this->messages['pt'][] = 'Entrar';
        $this->messages['es'][] = 'Ingresar';

        $this->messages['en'][] = 'Date';
        $this->messages['pt'][] = 'Data';
        $this->messages['es'][] = 'Fecha';

        $this->messages['en'][] = 'Column';
        $this->messages['pt'][] = 'Coluna';
        $this->messages['es'][] = 'Columna';

        $this->messages['en'][] = 'Operation';
        $this->messages['pt'][] = 'Operação';
        $this->messages['es'][] = 'Operación';

        $this->messages['en'][] = 'Old value';
        $this->messages['pt'][] = 'Valor antigo';
        $this->messages['es'][] = 'Valor anterior';

        $this->messages['en'][] = 'New value';
        $this->messages['pt'][] = 'Valor novo';
        $this->messages['es'][] = 'Valor nuevo';

        $this->messages['en'][] = 'Database';
        $this->messages['pt'][] = 'Banco de dados';
        $this->messages['es'][] = 'Base de datos';

        $this->messages['en'][] = 'Profile';
        $this->messages['pt'][] = 'Perfil';
        $this->messages['es'][] = 'Perfil';

        $this->messages['en'][] = 'Change password';
        $this->messages['pt'][] = 'Mudar senha';
        $this->messages['es'][] = 'Cambiar contraseña';

        $this->messages['en'][] = 'Results';
        $this->messages['pt'][] = 'Resultados';
        $this->messages['es'][] = 'Resultados';

        $this->messages['en'][] = 'Invalid command';
        $this->messages['pt'][] = 'Comando inválido';
        $this->messages['es'][] = 'Comando inválido';

        $this->messages['en'][] = '^1 records shown';
        $this->messages['pt'][] = '^1 registros exibidos';
        $this->messages['es'][] = '^1 registros  exhibidos';

        $this->messages['en'][] = 'Administration';
        $this->messages['pt'][] = 'Administração';
        $this->messages['es'][] = 'Administración';

        $this->messages['en'][] = 'SQL Panel';
        $this->messages['pt'][] = 'Painel SQL';
        $this->messages['es'][] = 'Panel SQL';

        $this->messages['en'][] = 'Access Log';
        $this->messages['pt'][] = 'Log de acesso';
        $this->messages['es'][] = 'Log de acceso';

        $this->messages['en'][] = 'Change Log';
        $this->messages['pt'][] = 'Log de alterações';
        $this->messages['es'][] = 'Log de modificaciones';

        $this->messages['en'][] = 'SQL Log';
        $this->messages['pt'][] = 'Log de SQL';
        $this->messages['es'][] = 'Log de SQL';

        $this->messages['en'][] = 'Clear form';
        $this->messages['pt'][] = 'Limpar formulário';
        $this->messages['es'][] = 'Limpiar formulário';

        $this->messages['en'][] = 'Send';
        $this->messages['pt'][] = 'Enviar';
        $this->messages['es'][] = 'Enviar';

        $this->messages['en'][] = 'Message';
        $this->messages['pt'][] = 'Mensagem';
        $this->messages['es'][] = 'Mensaje';

        $this->messages['en'][] = 'Messages';
        $this->messages['pt'][] = 'Mensagens';
        $this->messages['es'][] = 'Mensajes';

        $this->messages['en'][] = 'Subject';
        $this->messages['pt'][] = 'Assunto';
        $this->messages['es'][] = 'Asunto';

        $this->messages['en'][] = 'Message sent successfully';
        $this->messages['pt'][] = 'Mensagem enviada com sucesso';
        $this->messages['es'][] = 'Mensaje enviada exitosamente';

        $this->messages['en'][] = 'Check as read';
        $this->messages['pt'][] = 'Marcar como lida';
        $this->messages['es'][] = 'Marcar como leído';

        $this->messages['en'][] = 'Check as unread';
        $this->messages['pt'][] = 'Marcar como não lida';
        $this->messages['es'][] = 'Marcar como no leído';

        $this->messages['en'][] = 'Action';
        $this->messages['pt'][] = 'Ação';
        $this->messages['es'][] = 'Acción';

        $this->messages['en'][] = 'From';
        $this->messages['pt'][] = 'De';
        $this->messages['es'][] = 'Origen';

        $this->messages['en'][] = 'Checked';
        $this->messages['pt'][] = 'Verificado';
        $this->messages['es'][] = 'Verificado';

        $this->messages['en'][] = 'Object ^1 not found in ^2';
        $this->messages['pt'][] = 'Objeto ^1 não encontrado em ^2';
        $this->messages['es'][] = 'Objeto ^1 no encontrado en ^2';

        $this->messages['en'][] = 'Notification';
        $this->messages['pt'][] = 'Notificação';
        $this->messages['es'][] = 'Notificación';

        $this->messages['en'][] = 'Notifications';
        $this->messages['pt'][] = 'Notificações';
        $this->messages['es'][] = 'Notificaciones';

        $this->messages['en'][] = 'Categories';
        $this->messages['pt'][] = 'Categorias';
        $this->messages['es'][] = 'Categorias';

        $this->messages['en'][] = 'Send document';
        $this->messages['pt'][] = 'Enviar documentos';
        $this->messages['es'][] = 'Enviar documentos';

        $this->messages['en'][] = 'My documents';
        $this->messages['pt'][] = 'Meus documentos';
        $this->messages['es'][] = 'Mis documentos';

        $this->messages['en'][] = 'Shared with me';
        $this->messages['pt'][] = 'Compartilhados comigo';
        $this->messages['es'][] = 'Compartidos conmigo';

        $this->messages['en'][] = 'Document';
        $this->messages['pt'][] = 'Documento';
        $this->messages['es'][] = 'Documento';

        $this->messages['en'][] = 'File';
        $this->messages['pt'][] = 'Arquivo';
        $this->messages['es'][] = 'Archivo';

        $this->messages['en'][] = 'Title';
        $this->messages['pt'][] = 'Tí­tulo';
        $this->messages['es'][] = 'Título';

        $this->messages['en'][] = 'Description';
        $this->messages['pt'][] = 'Descrição';
        $this->messages['es'][] = 'Descripción';

        $this->messages['en'][] = 'Category';
        $this->messages['pt'][] = 'Categoria';
        $this->messages['es'][] = 'Categoria';

        $this->messages['en'][] = 'Submission date';
        $this->messages['pt'][] = 'Data de submissão';
        $this->messages['es'][] = 'Fecha de envio';

        $this->messages['en'][] = 'Archive date';
        $this->messages['pt'][] = 'Data de arquivamento';
        $this->messages['es'][] = 'Fecha de archivamiento';

        $this->messages['en'][] = 'Upload';
        $this->messages['pt'][] = 'Upload';
        $this->messages['es'][] = 'Upload';

        $this->messages['en'][] = 'Download';
        $this->messages['pt'][] = 'Download';
        $this->messages['es'][] = 'Download';

        $this->messages['en'][] = 'Next';
        $this->messages['pt'][] = 'Próximo';
        $this->messages['es'][] = 'Siguiente';

        $this->messages['en'][] = 'Documents';
        $this->messages['pt'][] = 'Documentos';
        $this->messages['es'][] = 'Documentos';

        $this->messages['en'][] = 'Permission';
        $this->messages['pt'][] = 'Permissão';
        $this->messages['es'][] = 'Permiso';

        $this->messages['en'][] = 'Unit';
        $this->messages['pt'][] = 'Unidade';
        $this->messages['es'][] = 'Unidad';

        $this->messages['en'][] = 'Units';
        $this->messages['pt'][] = 'Unidades';
        $this->messages['es'][] = 'Unidades';

        $this->messages['en'][] = 'Add';
        $this->messages['pt'][] = 'Adiciona';
        $this->messages['es'][] = 'Agrega';

        $this->messages['en'][] = 'Active';
        $this->messages['pt'][] = 'Ativo';
        $this->messages['es'][] = 'Activo';

        $this->messages['en'][] = 'Activate/Deactivate';
        $this->messages['pt'][] = 'Ativar/desativar';
        $this->messages['es'][] = 'Activar/desactivar';

        $this->messages['en'][] = 'Inactive user';
        $this->messages['pt'][] = 'Usuário inativo';
        $this->messages['es'][] = 'Usuário desactivado';

        $this->messages['en'][] = 'Send message';
        $this->messages['pt'][] = 'Envia mensagem';
        $this->messages['es'][] = 'Envia mensaje';

        $this->messages['en'][] = 'Read messages';
        $this->messages['pt'][] = 'Ler mensagens';
        $this->messages['es'][] = 'Leer mensaje';

        $this->messages['en'][] = 'An user with this login is already registered';
        $this->messages['pt'][] = 'Um usuário já está cadastrado com este login';
        $this->messages['es'][] = 'Un usuário ya está registrado con este login';

        $this->messages['en'][] = 'Access Stats';
        $this->messages['pt'][] = 'Estatí­sticas de acesso';
        $this->messages['es'][] = 'Estadisticas de acceso';

        $this->messages['en'][] = 'Accesses';
        $this->messages['pt'][] = 'Acessos';
        $this->messages['es'][] = 'Accesos';

        $this->messages['en'][] = 'Preferences';
        $this->messages['pt'][] = 'Preferências';
        $this->messages['es'][] = 'Preferencias';

        $this->messages['en'][] = 'Mail from';
        $this->messages['pt'][] = 'E-mail de origem';
        $this->messages['es'][] = 'E-mail de origen';

        $this->messages['en'][] = 'SMTP Auth';
        $this->messages['pt'][] = 'Autentica SMTP';
        $this->messages['es'][] = 'Autentica SMTP';

        $this->messages['en'][] = 'SMTP Host';
        $this->messages['pt'][] = 'Host SMTP';
        $this->messages['es'][] = 'Host SMTP';

        $this->messages['en'][] = 'SMTP Port';
        $this->messages['pt'][] = 'Porta SMTP';
        $this->messages['es'][] = 'Puerta SMTP';

        $this->messages['en'][] = 'SMTP User';
        $this->messages['pt'][] = 'Usuário SMTP';
        $this->messages['es'][] = 'Usuário SMTP';

        $this->messages['en'][] = 'SMTP Pass';
        $this->messages['pt'][] = 'Senha SMTP';
        $this->messages['es'][] = 'Contraseña SMTP';

        $this->messages['en'][] = 'Ticket';
        $this->messages['pt'][] = 'Ticket';
        $this->messages['es'][] = 'Ticket';

        $this->messages['en'][] = 'Open ticket';
        $this->messages['pt'][] = 'Abrir ticket';
        $this->messages['es'][] = 'Abrir ticket';

        $this->messages['en'][] = 'Support mail';
        $this->messages['pt'][] = 'Email de suporte';
        $this->messages['es'][] = 'Email de soporte';

        $this->messages['en'][] = 'Day';
        $this->messages['pt'][] = 'Dia';
        $this->messages['es'][] = 'Dia';

        $this->messages['en'][] = 'Folders';
        $this->messages['pt'][] = 'Pastas';
        $this->messages['es'][] = 'Carpetas';

        $this->messages['en'][] = 'Compose';
        $this->messages['pt'][] = 'Escrever';
        $this->messages['es'][] = 'Componer';

        $this->messages['en'][] = 'Inbox';
        $this->messages['pt'][] = 'Entrada';
        $this->messages['es'][] = 'Entrada';

        $this->messages['en'][] = 'Sent';
        $this->messages['pt'][] = 'Enviados';
        $this->messages['es'][] = 'Enviados';

        $this->messages['en'][] = 'Archived';
        $this->messages['pt'][] = 'Arquivados';
        $this->messages['es'][] = 'Archivados';

        $this->messages['en'][] = 'Archive';
        $this->messages['pt'][] = 'Arquivar';
        $this->messages['es'][] = 'Archivar';

        $this->messages['en'][] = 'Recover';
        $this->messages['pt'][] = 'Recuperar';
        $this->messages['es'][] = 'Recuperar';

        $this->messages['en'][] = 'Value';
        $this->messages['pt'][] = 'Valor';
        $this->messages['es'][] = 'Valor';

        $this->messages['en'][] = 'View all';
        $this->messages['pt'][] = 'Ver todos';
        $this->messages['es'][] = 'Ver todos';

        $this->messages['en'][] = 'Reload';
        $this->messages['pt'][] = 'Recarregar';
        $this->messages['es'][] = 'Recargar';

        $this->messages['en'][] = 'Back';
        $this->messages['pt'][] = 'Voltar';
        $this->messages['es'][] = 'Volver';

        $this->messages['en'][] = 'Clear';
        $this->messages['pt'][] = 'Limpar';
        $this->messages['es'][] = 'Limpiar';

        $this->messages['en'][] = 'View';
        $this->messages['pt'][] = 'Visualizar';
        $this->messages['es'][] = 'Visualizar';

        $this->messages['en'][] = 'No records found';
        $this->messages['pt'][] = 'Nenhum registro foi encontrado';
        $this->messages['es'][] = 'Ningun registro fue encontrado';

        $this->messages['en'][] = 'Value';
        $this->messages['pt'][] = 'Valor';
        $this->messages['es'][] = 'Valor';

        $this->messages['en'][] = 'User';
        $this->messages['pt'][] = 'Usuário';
        $this->messages['es'][] = 'Usuário';

        $this->messages['en'][] = 'Password';
        $this->messages['pt'][] = 'Senha';
        $this->messages['es'][] = 'Contraseña';

        $this->messages['en'][] = 'Port';
        $this->messages['pt'][] = 'Porta';
        $this->messages['es'][] = 'Puerta';
        
        $this->messages['en'][] = 'Main unit';
        $this->messages['pt'][] = 'Unidade principal';
        $this->messages['es'][] = 'Unidad principal';

        $this->messages['en'][] = 'Time';
        $this->messages['pt'][] = 'Hora';
        $this->messages['es'][] = 'Hora';

        $this->messages['en'][] = 'Type';
        $this->messages['pt'][] = 'Tipo';
        $this->messages['es'][] = 'Tipo';

        $this->messages['en'][] = 'Failed to read error log (^1)';
        $this->messages['pt'][] = 'Falha ao ler o log de erros (^1)';
        $this->messages['es'][] = 'Falla al leer el log de errores (^1)';

        $this->messages['en'][] = 'Error log (^1) is not writable by web server user, so the messages may be incomplete';
        $this->messages['pt'][] = 'O log de erros (^1) não permite escrita pelo usuário web, assim as mensagens devem estar incompletas';
        $this->messages['es'][] = 'El log de errores (^1) no permite escritura por el usuário web, así­ que los mensajes deben estar incompletos';

        $this->messages['en'][] = 'Check the owner of the log file. He must be the same as the web user (usually www-data, www, etc)';
        $this->messages['pt'][] = 'Revise o proprietário do arquivo de log. Ele deve ser igual ao usuário web (geralmente www-data, www, etc)';
        $this->messages['es'][] = 'Revise el propietario del archivo de log. Debe ser igual al usuário web (generalmente www-data, www, etc)';

        $this->messages['en'][] = 'Error log is empty or has not been configured correctly. Define the error log file, setting <b>error_log</b> at php.ini';
        $this->messages['pt'][] = 'Log de erros está vazio ou não foi configurado corretamente. Defina o arquivo de log de erros, configurando <b>error_log</b> no php.ini';
        $this->messages['es'][] = 'Log de errores está vacio o no fue configurado correctamente. Defina el archivo de log de errores, configurando <b>error_log</b> en el php.ini';

        $this->messages['en'][] = 'Errors are not being logged. Please turn <b>log_errors = On</b> at php.ini';
        $this->messages['pt'][] = 'Erros não estão sendo registrados. Por favor, mude <b>log_errors = On</b> no php.ini';
        $this->messages['es'][] = 'Errores no estan siendo registrados. Por favor, modifique <b>log_errors = On</b> en el php.ini';

        $this->messages['en'][] = 'Errors are not currently being displayd because the <b>display_errors</b> is set to Off in php.ini';
        $this->messages['pt'][] = 'Erros não estão atualmente sendo exibidos por que <b>display_errors</b> está configurado para Off no php.ini';
        $this->messages['es'][] = 'Errores no estan actualmente siendo mostrados porque <b>display_errors</b> está configurado para Off en el php.ini';

        $this->messages['en'][] = 'This configuration is usually recommended for production, not development purposes';
        $this->messages['pt'][] = 'Esta configuração normalmente é recomendada para produção, não para o propósito de desenvolvimento';
        $this->messages['es'][] = 'Esta configuración normalmente es recomendada para producción, no para el propósito de desarrollo';

        $this->messages['en'][] = 'The php.ini current location is <b>^1</b>';
        $this->messages['pt'][] = 'A localização atual do php.ini é <b>^1</b>';
        $this->messages['es'][] = 'La ubicación actual del php.ini es <b>^1</b>';

        $this->messages['en'][] = 'The error log current location is <b>^1</b>';
        $this->messages['pt'][] = 'A localização atual do log de erros é <b>^1</b>';
        $this->messages['es'][] = 'La ubicación actual del log de errores es <b>^1</b>';

        $this->messages['en'][] = 'PHP Log';
        $this->messages['pt'][] = 'Log do PHP';
        $this->messages['es'][] = 'Log del PHP';

        $this->messages['en'][] = 'Database explorer';
        $this->messages['pt'][] = 'Database explorer';
        $this->messages['es'][] = 'Database explorer';

        $this->messages['en'][] = 'Tables';
        $this->messages['pt'][] = 'Tabelas';
        $this->messages['es'][] = 'Tablas';
        
        $this->messages['en'][] = 'Module';
        $this->messages['pt'][] = 'Módulo';
        $this->messages['es'][] = 'Módulo';

        $this->messages['en'][] = 'Directory';
        $this->messages['pt'][] = 'Diretório';
        $this->messages['es'][] = 'Directório';

        $this->messages['en'][] = 'Source code';
        $this->messages['pt'][] = 'Código-fonte';
        $this->messages['es'][] = 'Código-fuente';

        $this->messages['en'][] = 'Invalid return';
        $this->messages['pt'][] = 'Retorno inválido';
        $this->messages['es'][] = 'Retorno inválido';

        $this->messages['en'][] = 'Page';
        $this->messages['pt'][] = 'Página';
        $this->messages['es'][] = 'Página';

        $this->messages['en'][] = 'Path';
        $this->messages['pt'][] = 'Diretório';
        $this->messages['es'][] = 'Directório';

        $this->messages['en'][] = 'File';
        $this->messages['pt'][] = 'Arquivo';
        $this->messages['es'][] = 'Archivo';

        $this->messages['en'][] = 'Photo';
        $this->messages['pt'][] = 'Foto';
        $this->messages['es'][] = 'Foto';

        $this->messages['en'][] = 'Reset password';
        $this->messages['pt'][] = 'Redefinir senha';
        $this->messages['es'][] = 'Cambiar contraseña';

        $this->messages['en'][] = 'A new seed is required in the application.ini for security reasons';
        $this->messages['pt'][] = 'Uma nova seed é necessária no application.ini por motivos de segurança';
        $this->messages['es'][] = 'Una nueva seed es necesaria en application.ini por motivos de seguridad';

        $this->messages['en'][] = 'Password reset';
        $this->messages['pt'][] = 'Troca de senha';
        $this->messages['es'][] = 'Cambiar la contraseña';

        $this->messages['en'][] = 'Token expired. This operation is not allowed';
        $this->messages['pt'][] = 'Token expirado. Esta operação não é permitida';
        $this->messages['es'][] = 'Token expirado. Esta operación no está permitida';

        $this->messages['en'][] = 'The password has been changed';
        $this->messages['pt'][] = 'A senha foi alterada';
        $this->messages['es'][] = 'La contraseña fue modificada';

        $this->messages['en'][] = 'An user with this e-mail is already registered';
        $this->messages['pt'][] = 'Um usuário já está cadastrado com este e-mail';
        $this->messages['es'][] = 'Un usuário ya está registrado con este e-mail';

        $this->messages['en'][] = 'Invalid LDAP credentials';
        $this->messages['pt'][] = 'Credenciais LDAP erradas';
        $this->messages['es'][] = 'Credenciales LDAP incorrectas';

        $this->messages['en'][] = 'Menu path';
        $this->messages['pt'][] = 'Caminho menu';
        $this->messages['es'][] = 'Dirección del menu';

        $this->messages['en'][] = 'Icon';
        $this->messages['pt'][] = 'Ícone';
        $this->messages['es'][] = 'Icono';

        $this->messages['en'][] = 'User registration';
        $this->messages['pt'][] = 'Cadastro de usuário';
        $this->messages['es'][] = 'Registro de usuário';

        $this->messages['en'][] = 'The user registration is disabled';
        $this->messages['pt'][] = 'O cadastro de usuários está desabilitado';
        $this->messages['es'][] = 'El registro de usuários está desactivado';

        $this->messages['en'][] = 'The password reset is disabled';
        $this->messages['pt'][] = 'A recuperação de senhas está desabilitada';
        $this->messages['es'][] = 'La recuperación de contraseña está desactivada';

        $this->messages['en'][] = 'Account created';
        $this->messages['pt'][] = 'Conta criada';
        $this->messages['es'][] = 'Cuenta creada';

        $this->messages['en'][] = 'Create account';
        $this->messages['pt'][] = 'Criar conta';
        $this->messages['es'][] = 'Crear cuenta';

        $this->messages['en'][] = 'Authorization error';
        $this->messages['pt'][] = 'Erro de autorização';
        $this->messages['es'][] = 'Error de autorización';

        $this->messages['en'][] = 'Exit';
        $this->messages['pt'][] = 'Sair';
        $this->messages['es'][] = 'Salir';

        $this->messages['en'][] = 'REST key not defined';
        $this->messages['pt'][] = 'Chave REST não definida';
        $this->messages['es'][] = 'Clave REST no definida';

        $this->messages['en'][] = 'Local';
        $this->messages['pt'][] = 'Local';
        $this->messages['es'][] = 'Local';

        $this->messages['en'][] = 'Remote';
        $this->messages['pt'][] = 'Remoto';
        $this->messages['es'][] = 'Remoto';

        $this->messages['en'][] = 'Success';
        $this->messages['pt'][] = 'Sucesso';
        $this->messages['es'][] = 'Éxito';

        $this->messages['en'][] = 'Error';
        $this->messages['pt'][] = 'Erro';
        $this->messages['es'][] = 'Error';

        $this->messages['en'][] = 'Status';
        $this->messages['pt'][] = 'Status';
        $this->messages['es'][] = 'Estado';

        $this->messages['en'][] = 'Order';
        $this->messages['pt'][] = 'Ordenação';
        $this->messages['es'][] = 'Ordenación';

        $this->messages['en'][] = 'Label';
        $this->messages['pt'][] = 'Rótulo';
        $this->messages['es'][] = 'Etiqueta';

        $this->messages['en'][] = 'Color';
        $this->messages['pt'][] = 'Cor';
        $this->messages['es'][] = 'Color';

        $this->messages['en'][] = 'Clone';
        $this->messages['pt'][] = 'Clonar';
        $this->messages['es'][] = 'Clonar';

        $this->messages['en'][] = 'Impersonation';
        $this->messages['pt'][] = 'Personificar';
        $this->messages['es'][] = 'Personificar';

        $this->messages['en'][] = 'Impersonated';
        $this->messages['pt'][] = 'Personificado';
        $this->messages['es'][] = 'Personificado';

        $this->messages['en'][] = 'Execution trace';
        $this->messages['pt'][] = 'Rastreamento da execução';
        $this->messages['es'][] = 'Rastreo de ejecución';

        $this->messages['en'][] = 'Session';
        $this->messages['pt'][] = 'Sessão';
        $this->messages['es'][] = 'Sesión';

        $this->messages['en'][] = 'Request Log';
        $this->messages['pt'][] = 'Log de request';
        $this->messages['es'][] = 'Log de request';

        $this->messages['en'][] = 'Method';
        $this->messages['pt'][] = 'Método';
        $this->messages['es'][] = 'Método';

        $this->messages['en'][] = 'Request';
        $this->messages['pt'][] = 'Requisição';
        $this->messages['es'][] = 'Request';

        $this->messages['en'][] = 'Users by group';
        $this->messages['pt'][] = 'Usuários por grupo';
        $this->messages['es'][] = 'Usuarios por grupo';

        $this->messages['en'][] = 'Count';
        $this->messages['pt'][] = 'Quantidade';
        $this->messages['es'][] = 'Cantidad';

        $this->messages['en'][] = 'Users by unit';
        $this->messages['pt'][] = 'Usuários por unidade';
        $this->messages['es'][] = 'Usuarios por unidad';

        $this->messages['en'][] = 'Save as PDF';
        $this->messages['pt'][] = 'Salvar como PDF';
        $this->messages['es'][] = 'Guardar como PDF';

        $this->messages['en'][] = 'Save as CSV';
        $this->messages['pt'][] = 'Salvar como CSV';
        $this->messages['es'][] = 'Guardar como CSV';

        $this->messages['en'][] = 'Save as XML';
        $this->messages['pt'][] = 'Salvar como XML';
        $this->messages['es'][] = 'Guardar como XML';

        $this->messages['en'][] = 'Export';
        $this->messages['pt'][] = 'Exportar';
        $this->messages['es'][] = 'Exportar';

        $this->messages['en'][] = 'System information';
        $this->messages['pt'][] = 'Informações do sistema';
        $this->messages['es'][] = 'Informaciones del sistema';

        $this->messages['en'][] = 'RAM Memory';
        $this->messages['pt'][] = 'Memória RAM';
        $this->messages['es'][] = 'Memória RAM';

        $this->messages['en'][] = 'Using/Total';
        $this->messages['pt'][] = 'Usando/Total';
        $this->messages['es'][] = 'Utilizando/Total';

        $this->messages['en'][] = 'Free';
        $this->messages['pt'][] = 'Disponí­vel';
        $this->messages['es'][] = 'Disponible';

        $this->messages['en'][] = 'Percentage used';
        $this->messages['pt'][] = 'Percentual usado';
        $this->messages['es'][] = 'Porcentaje utilizado';

        $this->messages['en'][] = 'CPU usage';
        $this->messages['pt'][] = 'Uso da CPU';
        $this->messages['es'][] = 'Uso de CPU';

        $this->messages['en'][] = 'Vendor';
        $this->messages['pt'][] = 'Fornecedor';
        $this->messages['es'][] = 'Proveedor';

        $this->messages['en'][] = 'Model';
        $this->messages['pt'][] = 'Modelo';
        $this->messages['es'][] = 'Modelo';

        $this->messages['en'][] = 'Current Frequency';
        $this->messages['pt'][] = 'Frequência atual';
        $this->messages['es'][] = 'Frecuencia actual';

        $this->messages['en'][] = 'Webserver and Process';
        $this->messages['pt'][] = 'Servidor web e processos';
        $this->messages['es'][] = 'Servidor web y procesos';

        $this->messages['en'][] = 'Disk devices';
        $this->messages['pt'][] = 'Dispositivos de disco';
        $this->messages['es'][] = 'Dispositivos de disco';

        $this->messages['en'][] = 'Device';
        $this->messages['pt'][] = 'Dispositivo';
        $this->messages['es'][] = 'Dispositivo';

        $this->messages['en'][] = 'Mount point';
        $this->messages['pt'][] = 'Ponto de montagem';
        $this->messages['es'][] = 'Punto de montaje';

        $this->messages['en'][] = 'Filesystem';
        $this->messages['pt'][] = 'Sistema de arquivos';
        $this->messages['es'][] = 'Sistema de archivos';

        $this->messages['en'][] = 'Network devices';
        $this->messages['pt'][] = 'Dispositivos de rede';
        $this->messages['es'][] = 'Dispositivos de red';

        $this->messages['en'][] = 'Device name';
        $this->messages['pt'][] = 'Nome do dispositivo';
        $this->messages['es'][] = 'Nombre del dispositivo';

        $this->messages['en'][] = 'Port speed';
        $this->messages['pt'][] = 'Velocidade da porta';
        $this->messages['es'][] = 'Velocidad de la puerta';

        $this->messages['en'][] = 'Sent';
        $this->messages['pt'][] = 'Enviados';
        $this->messages['es'][] = 'Enviados';

        $this->messages['en'][] = 'Recieved';
        $this->messages['pt'][] = 'Recebidos';
        $this->messages['es'][] = 'Recebidos';

        $this->messages['en'][] = 'Print';
        $this->messages['pt'][] = 'Imprimir';
        $this->messages['es'][] = 'Imprimir';

        $this->messages['en'][] = 'Delete session var';
        $this->messages['pt'][] = 'Exclui variável de sessão';
        $this->messages['es'][] = 'Eliminar variable de sesión';

        $this->messages['en'][] = 'Impersonated by';
        $this->messages['pt'][] = 'Personificado por';
        $this->messages['es'][] = 'Personificado por';

        $this->messages['en'][] = 'Unauthorized access to that unit';
        $this->messages['pt'][] = 'Acesso não autorizado à esta unidade';
        $this->messages['es'][] = 'Acceso prohibido a esta unidad';

        $this->messages['en'][] = 'Files diff';
        $this->messages['pt'][] = 'Diferença de arquivos';
        $this->messages['es'][] = 'Diferencia de archivo';

        $this->messages['en'][] = 'Removed';
        $this->messages['pt'][] = 'Removido';
        $this->messages['es'][] = 'Remoto';

        $this->messages['en'][] = 'Equal';
        $this->messages['pt'][] = 'Igual';
        $this->messages['es'][] = 'Igual';

        $this->messages['en'][] = 'Modified';
        $this->messages['pt'][] = 'Modificado';
        $this->messages['es'][] = 'Cambiado';

        $this->messages['en'][] = 'Terms of use and privacy policy';
        $this->messages['pt'][] = 'Termo de uso e polí­tica de privacidade';
        $this->messages['es'][] = 'Términos de uso y polí­tica de privacidad';

        $this->messages['en'][] = 'Accept';
        $this->messages['pt'][] = 'Aceitar';
        $this->messages['es'][] = 'Aceptar';

        $this->messages['en'][] = 'I have read and agree to the terms of use and privacy policy';
        $this->messages['pt'][] = 'Eu li e concordo com os termos de uso e política de privacidade';
        $this->messages['es'][] = 'He leí­do y acepto los términos de uso y la política de privacidad';

        $this->messages['en'][] = 'You need read and agree to the terms of use and privacy policy';
        $this->messages['pt'][] = 'Você precisa ler e concordar com os termos de uso e polí­tica de privacidade';
        $this->messages['es'][] = 'Necesita leer y aceptar los términos de uso y la política de privacidad';

        $this->messages['en'][] = 'Login to your account';
        $this->messages['pt'][] = 'Login realizado em sua conta';
        $this->messages['es'][] = 'Ingrese a su cuenta';

        $this->messages['en'][] = 'You have just successfully logged in to ^1. If you do not recognize this login, contact technical support';
        $this->messages['pt'][] = 'Você acaba de efetuar login com sucesso no ^1. Se não reconhece esse login, entre em contato com o suporte técnico';
        $this->messages['es'][] = 'Acaba de iniciar sesión correctamente en ^1. Si no reconoce este inicio de sesión, comuníquese con el soporte técnico';

        $this->messages['en'][] = 'Click here for more information';
        $this->messages['pt'][] = 'Clique aqui para obter mais informações';
        $this->messages['es'][] = 'Haga clic aquí­ para más información';

        $this->messages['en'][] = 'You have already registered this password';
        $this->messages['pt'][] = 'Você já cadastrou essa senha';
        $this->messages['es'][] = 'Ya has registrado esta contraseña';

        $this->messages['en'][] = 'Renewal password';
        $this->messages['pt'][] = 'Renovação de senha';
        $this->messages['es'][] = 'Renovación de contraseña';

        $this->messages['en'][] = 'You need to renew your password, as we have identified that you have not changed it for more than ^1 days';
        $this->messages['pt'][] = 'Você precisa renovar sua senha, pois identificamos que você não a altera há mais de ^1 dias';
        $this->messages['es'][] = 'Debe renovar su contraseña, ya que hemos identificado que no la ha cambiado durante más de ^1 días';

        $this->messages['en'][] = 'Global search';
        $this->messages['pt'][] = 'Busca global';
        $this->messages['es'][] = 'Buscar global';

        $this->messages['en'][] = 'More';
        $this->messages['pt'][] = 'Mais';
        $this->messages['es'][] = 'Más';

        $this->messages['en'][] = 'Upload file';
        $this->messages['pt'][] = 'Adicionar arquivo';
        $this->messages['es'][] = 'Subir archivo';

        $this->messages['en'][] = 'New folder';
        $this->messages['pt'][] = 'Nova pasta';
        $this->messages['es'][] = 'Nueva carpeta';

        $this->messages['en'][] = 'Folder';
        $this->messages['pt'][] = 'Pasta';
        $this->messages['es'][] = 'Carpeta';

        $this->messages['en'][] = 'This operation is not allowed';
        $this->messages['pt'][] = 'Esta operação não é permitida';
        $this->messages['es'][] = 'Esta operación no está permitida';

        $this->messages['en'][] = 'Parent folder';
        $this->messages['pt'][] = 'Pasta pai';
        $this->messages['es'][] = 'Carpeta principal';

        $this->messages['en'][] = 'Sent out';
        $this->messages['pt'][] = 'Enviado para fora';
        $this->messages['es'][] = 'Enviado a fuera';

        $this->messages['en'][] = 'Sent to trash';
        $this->messages['pt'][] = 'Enviado para lixeira';
        $this->messages['es'][] = 'Enviado a la basura';

        $this->messages['en'][] = 'Sent to ^1';
        $this->messages['pt'][] = 'Enviado para ^1';
        $this->messages['es'][] = 'Enviado a ^1';

        $this->messages['en'][] = 'Bookmarked';
        $this->messages['pt'][] = 'Marcado como favorito';
        $this->messages['es'][] = 'Definido como favorito';

        $this->messages['en'][] = 'Bookmarks';
        $this->messages['pt'][] = 'Favoritos';
        $this->messages['es'][] = 'Favoritos';

        $this->messages['en'][] = 'Trash';
        $this->messages['pt'][] = 'Lixeira';
        $this->messages['es'][] = 'Basura';

        $this->messages['en'][] = 'Send to trash';
        $this->messages['pt'][] = 'Enviar para lixeira';
        $this->messages['es'][] = 'Enviar a la basura';

        $this->messages['en'][] = 'Set bookmark';
        $this->messages['pt'][] = 'Marcar como favorito';
        $this->messages['es'][] = 'Marcar como favorito';

        $this->messages['en'][] = 'Remove from bookmark';
        $this->messages['pt'][] = 'Remover dos favoritos';
        $this->messages['es'][] = 'Remover dos favoritos';

        $this->messages['en'][] = 'Restore from trash';
        $this->messages['pt'][] = 'Restaurar da lixeira';
        $this->messages['es'][] = 'Recuperarse de la basura';

        $this->messages['en'][] = 'Restored';
        $this->messages['pt'][] = 'Restaurado';
        $this->messages['es'][] = 'Restaurado';

        $this->messages['en'][] = 'Share';
        $this->messages['pt'][] = 'Compartilhar';
        $this->messages['es'][] = 'Cuota';

        $this->messages['en'][] = 'Details';
        $this->messages['pt'][] = 'Detalhes';
        $this->messages['es'][] = 'Detalles';

        $this->messages['en'][] = 'Permanently delete';
        $this->messages['pt'][] = 'Remover permanentemente';
        $this->messages['es'][] = 'Remover permanentemente';

        $this->messages['en'][] = 'This will remove all the contents of the folder';
        $this->messages['pt'][] = 'Isso irá remover todos o conteudo da pasta';
        $this->messages['es'][] = 'Esto eliminará todo el contenido de la carpeta.';

        $this->messages['en'][] = 'Created at';
        $this->messages['pt'][] = 'Criado em';
        $this->messages['es'][] = 'Creado el';

        $this->messages['en'][] = 'Updated at';
        $this->messages['pt'][] = 'Atualizado em';
        $this->messages['es'][] = 'Actualizado el';

        $this->messages['en'][] = 'Have posts with this tag, please inactive';
        $this->messages['pt'][] = 'Tem postagens com essa tag, por favor inative';
        $this->messages['es'][] = 'Tener publicaciones con esta etiqueta, por favor inactivo';

        $this->messages['en'][] = 'Post';
        $this->messages['pt'][] = 'Publicação';
        $this->messages['es'][] = 'Publicacion';

        $this->messages['en'][] = 'Posts';
        $this->messages['pt'][] = 'Publicações';
        $this->messages['es'][] = 'Publicaciones';

        $this->messages['en'][] = 'Created by';
        $this->messages['pt'][] = 'Criado por';
        $this->messages['es'][] = 'Criado por';

        $this->messages['en'][] = 'until';
        $this->messages['pt'][] = 'até';
        $this->messages['es'][] = 'asta';

        $this->messages['en'][] = 'Content';
        $this->messages['pt'][] = 'Conteúdo';
        $this->messages['es'][] = 'Contenido';

        $this->messages['en'][] = 'Preview';
        $this->messages['pt'][] = 'Pré-visualização';
        $this->messages['es'][] = 'Avance';

        $this->messages['en'][] = 'News';
        $this->messages['pt'][] = 'Notí­cias';
        $this->messages['es'][] = 'Noticias';

        $this->messages['en'][] = 'Like';
        $this->messages['pt'][] = 'Curtir';
        $this->messages['es'][] = 'Gusto';

        $this->messages['en'][] = 'Comment';
        $this->messages['pt'][] = 'Comentar';
        $this->messages['es'][] = 'Comentar';

        $this->messages['en'][] = 'Comments';
        $this->messages['pt'][] = 'Comentários';
        $this->messages['es'][] = 'Comentarios';

        $this->messages['en'][] = 'Likes';
        $this->messages['pt'][] = 'Curtidas';
        $this->messages['es'][] = 'Gustos';

        $this->messages['en'][] = 'See more';
        $this->messages['pt'][] = 'Ver mais';
        $this->messages['es'][] = 'Ver más';

        $this->messages['en'][] = 'Phone';
        $this->messages['pt'][] = 'Telefone';
        $this->messages['es'][] = 'Teléfono';

        $this->messages['en'][] = 'Address';
        $this->messages['pt'][] = 'Endereço';
        $this->messages['es'][] = 'Dirección';

        $this->messages['en'][] = 'Function';
        $this->messages['pt'][] = 'Função';
        $this->messages['es'][] = 'Función';

        $this->messages['en'][] = 'About';
        $this->messages['pt'][] = 'Sobre';
        $this->messages['es'][] = 'Sobre';

        $this->messages['en'][] = 'Expand';
        $this->messages['pt'][] = 'Expandir';
        $this->messages['es'][] = 'Expandir';

        $this->messages['en'][] = 'Contacts';
        $this->messages['pt'][] = 'Contatos';
        $this->messages['es'][] = 'Contactos';

        $this->messages['en'][] = 'Call';
        $this->messages['pt'][] = 'Ligar';
        $this->messages['es'][] = 'Llamada';

        $this->messages['en'][] = 'Searchable';
        $this->messages['pt'][] = 'Pesquisável';
        $this->messages['es'][] = 'Buscable';

        $this->messages['en'][] = 'Add wiki link';
        $this->messages['pt'][] = 'Adicionar link da wiki';
        $this->messages['es'][] = 'Agregar enlace wiki';

        $this->messages['en'][] = 'Last modification';
        $this->messages['pt'][] = 'Última modificação';
        $this->messages['es'][] = 'Última modificación';

        $this->messages['en'][] = 'Page management';
        $this->messages['pt'][] = 'Gestão de páginas';
        $this->messages['es'][] = 'Gestión de páginas';

        $this->messages['en'][] = 'Search pages';
        $this->messages['pt'][] = 'Buscar páginas';
        $this->messages['es'][] = 'Buscar páginas';

        $this->messages['en'][] = 'News management';
        $this->messages['pt'][] = 'Gestão de notícias';
        $this->messages['es'][] = 'Gestión de noticias';

        $this->messages['en'][] = 'List news';
        $this->messages['pt'][] = 'Listar notícias';
        $this->messages['es'][] = 'Lista de noticias';

        $this->messages['en'][] = 'Properties';
        $this->messages['pt'][] = 'Propriedades';
        $this->messages['es'][] = 'Propiedades';

        $this->messages['en'][] = 'Custom code';
        $this->messages['pt'][] = 'Código personalizado';
        $this->messages['es'][] = 'Código personalizado';

        $this->messages['en'][] = 'Role';
        $this->messages['pt'][] = 'Papel';
        $this->messages['es'][] = 'Rol';

        $this->messages['en'][] = 'Roles';
        $this->messages['pt'][] = 'Papéis';
        $this->messages['es'][] = 'Roles';

        $this->messages['en'][] = 'Grant all methods';
        $this->messages['pt'][] = 'Conceder todos métodos';
        $this->messages['es'][] = 'Otorgar todos los métodos';

        $this->messages['en'][] = 'All roles';
        $this->messages['pt'][] = 'Todos papéis';
        $this->messages['es'][] = 'Todos roles';

        $this->messages['en'][] = 'Methods';
        $this->messages['pt'][] = 'Métodos';
        $this->messages['es'][] = 'Métodos';

        $this->messages['en'][] = 'Restricted methods';
        $this->messages['pt'][] = 'Métodos restritos';
        $this->messages['es'][] = 'Métodos restringidos';

        $this->messages['en'][] = 'User not found or wrong password';
        $this->messages['pt'][] = 'Usuário não encontrado ou senha incorreta';
        $this->messages['es'][] = 'Usuario no encontrada o contraseña incorrecta';

        $this->messages['en'][] = 'Password should be at least 6 characters and include at least one upper case letter, one number, and one special character';
        $this->messages['pt'][] = 'Senhas devem ter pelo menos 6 caracteres e incluir pelo menos uma letra maiúscula, um número, e um caracter especial';
        $this->messages['es'][] = 'La contraseña debe tener al menos 6 caracteres e incluir al menos una letra mayúscula, un número y un carácter especial';

        $this->messages['en'][] = 'My profile';
        $this->messages['pt'][] = 'Meu perfil';
        $this->messages['es'][] = 'Mi perfil';

        $this->messages['en'][] = 'Enable 2FA';
        $this->messages['pt'][] = 'Habilitar 2FA';
        $this->messages['es'][] = 'Habilitar 2FA';

        $this->messages['en'][] = 'Two factor authentication';
        $this->messages['pt'][] = 'Autenticação de dois fatores';
        $this->messages['es'][] = 'Autenticación de dos factores';

        $this->messages['en'][] = 'Enter the 6-digit code from your authenticator app';
        $this->messages['pt'][] = 'Digite os código de 6 dí­gitos a partir de seu aplicativo autenticador';
        $this->messages['es'][] = 'Ingrese el código de 6 dí­gitos de su aplicación de autenticación';

        $this->messages['en'][] = 'Authentication code';
        $this->messages['pt'][] = 'Código de autenticação';
        $this->messages['es'][] = 'Código de autenticación';

        $this->messages['en'][] = 'Authenticate';
        $this->messages['pt'][] = 'Autenticar';
        $this->messages['es'][] = 'Autenticar';

        $this->messages['en'][] = 'Configure two-factor authentication';
        $this->messages['pt'][] = 'Configurar autenticação de dois fatores';
        $this->messages['es'][] = 'Configurar la autenticación de dos factores';

        $this->messages['en'][] = 'Scan the QR code with your phone to get started';
        $this->messages['pt'][] = 'Digitalize o código QR com seu telefone para começar';
        $this->messages['es'][] = 'Escanea el código QR con tu teléfono para comenzar';

        $this->messages['en'][] = 'Secret key';
        $this->messages['pt'][] = 'Chave secreta';
        $this->messages['es'][] = 'Llave secreta';

        $this->messages['en'][] = 'Use authencator app like Google Authenticator or Authy';
        $this->messages['pt'][] = 'Use uma aplicação de autenticação como Google Authenticator ou Authy';
        $this->messages['es'][] = 'Utilice una aplicación de autenticación como Google Authenticator o Authy';

        $this->messages['en'][] = 'Duration';
        $this->messages['pt'][] = 'Duração';
        $this->messages['es'][] = 'Duración';

        $this->messages['en'][] = 'Accesses today';
        $this->messages['pt'][] = 'Accessos hoje';
        $this->messages['es'][] = 'Inicios de sesión hoy';

        $this->messages['en'][] = 'Requests today';
        $this->messages['pt'][] = 'Requests hoje';
        $this->messages['es'][] = 'Requests hoy';

        $this->messages['en'][] = 'SQL DML Statements';
        $this->messages['pt'][] = 'Comandos SQL DML';
        $this->messages['es'][] = 'Comandos SQL DML';

        $this->messages['en'][] = 'Request time average';
        $this->messages['pt'][] = 'Tempo médio de requisição';
        $this->messages['es'][] = 'Tiempo promedio de solicitud';

        $this->messages['en'][] = 'Accesses by day';
        $this->messages['pt'][] = 'Acessos por dia';
        $this->messages['es'][] = 'Accesos por día';

        $this->messages['en'][] = 'Requests by day';
        $this->messages['pt'][] = 'Requests por dia';
        $this->messages['es'][] = 'Requests por día';

        $this->messages['en'][] = 'SQL statements by day';
        $this->messages['pt'][] = 'Comandos SQL por dia';
        $this->messages['es'][] = 'Comandos SQL por día';

        $this->messages['en'][] = 'Sum';
        $this->messages['pt'][] = 'Soma';
        $this->messages['es'][] = 'Suma';

        $this->messages['en'][] = 'Slower pages';
        $this->messages['pt'][] = 'Páginas mais lentas';
        $this->messages['es'][] = 'Páginas más lentas';

        $this->messages['en'][] = 'Slower methods';
        $this->messages['pt'][] = 'Métodos mais lentos';
        $this->messages['es'][] = 'Métodos más lentos';

        $this->messages['en'][] = 'Extension not found: ^1';
        $this->messages['pt'][] = 'Extensão não encontrada: ^1';
        $this->messages['es'][] = 'Extensión no encontrada: ^1';

        $this->messages['en'][] = 'Language';
        $this->messages['pt'][] = 'Idioma';
        $this->messages['es'][] = 'Idioma';

        $this->messages['en'][] = 'Undelete';
        $this->messages['pt'][] = 'Recuperar';
        $this->messages['es'][] = 'Recuperar';

        $this->messages['en'][] = 'Move to inbox';
        $this->messages['pt'][] = 'Mover para a caixa de entrada';
        $this->messages['es'][] = 'Mover a la bandeja de entrada';

        $this->messages['en'][] = 'Forward';
        $this->messages['pt'][] = 'Encaminhar';
        $this->messages['es'][] = 'Reenviar';

        $this->messages['en'][] = 'Reply';
        $this->messages['pt'][] = 'Responder';
        $this->messages['es'][] = 'Responder';

        $this->messages['en'][] = 'To';
        $this->messages['pt'][] = 'Para';
        $this->messages['es'][] = 'Para';

        $this->messages['en'][] = 'No e-mail sender configured';
        $this->messages['pt'][] = 'E-mail de origem não configurado';
        $this->messages['es'][] = 'Correo electrónico de origen no configurado';

        $this->messages['en'][] = 'No support e-mail configured';
        $this->messages['pt'][] = 'E-mail de suporte não configurado';
        $this->messages['es'][] = 'Correo electrónico de soporte no configurado';

        $this->messages['en'][] = 'Dark mode';
        $this->messages['pt'][] = 'Modo escuro';
        $this->messages['es'][] = 'Modo oscuro';

        $this->messages['en'][] = 'Attachments';
        $this->messages['pt'][] = 'Anexos';
        $this->messages['es'][] = 'Archivos adjuntos';

        $this->messages['en'][] = 'Filters';
        $this->messages['pt'][] = 'Filtros';
        $this->messages['es'][] = 'Filtros';

        $this->messages['en'][] = 'Informations and files';
        $this->messages['pt'][] = 'Informações e arquivos';
        $this->messages['es'][] = 'Información y archivos';

        $this->messages['en'][] = 'Apply';
        $this->messages['pt'][] = 'Aplicar';
        $this->messages['es'][] = 'Aplicar';

        $this->messages['en'][] = 'Create';
        $this->messages['pt'][] = 'Criar';
        $this->messages['es'][] = 'Crear';

        $this->messages['en'][] = 'This folder already exists';
        $this->messages['pt'][] = 'Esta pasta já existe';
        $this->messages['es'][] = 'Esta carpeta ya existe';

        $this->messages['en'][] = 'Calendar';
        $this->messages['pt'][] = 'Calendário';
        $this->messages['es'][] = 'Calendario';

        $this->messages['en'][] = 'Start time';
        $this->messages['pt'][] = 'Horário inicial';
        $this->messages['es'][] = 'Hora de inicio';

        $this->messages['en'][] = 'End time';
        $this->messages['pt'][] = 'Horário final';
        $this->messages['es'][] = 'Hora de finalización';

        $this->messages['en'][] = 'Event';
        $this->messages['pt'][] = 'Evento';
        $this->messages['es'][] = 'Evento';

        $this->messages['en'][] = 'Shares';
        $this->messages['pt'][] = 'Compartilhamentos';
        $this->messages['es'][] = 'Comparte';

        $this->messages['en'][] = 'New event';
        $this->messages['pt'][] = 'Novo evento';
        $this->messages['es'][] = 'Nuevo evento';

        $this->messages['en'][] = 'Alert before';
        $this->messages['pt'][] = 'Alertar antes';
        $this->messages['es'][] = 'Alerta antes';

        $this->messages['en'][] = 'Leave empty to avoid alert';
        $this->messages['pt'][] = 'Deixe vazio para evitar alerta';
        $this->messages['es'][] = 'Dejar vacío para evitar alerta';

        $this->messages['en'][] = 'Hours';
        $this->messages['pt'][] = 'Horas';
        $this->messages['es'][] = 'Horas';

        $this->messages['en'][] = 'Until';
        $this->messages['pt'][] = 'Até';
        $this->messages['es'][] = 'Hasta';

        $this->messages['en'][] = 'Recurring interval';
        $this->messages['pt'][] = 'Intervalo da recorrência';
        $this->messages['es'][] = 'Intervalo de recurrencia';

        $this->messages['en'][] = 'Frequency';
        $this->messages['pt'][] = 'Frequência';
        $this->messages['es'][] = 'Frecuencia';

        $this->messages['en'][] = 'Daily';
        $this->messages['pt'][] = 'Diário';
        $this->messages['es'][] = 'Diario';

        $this->messages['en'][] = 'Weekly';
        $this->messages['pt'][] = 'Semanal';
        $this->messages['es'][] = 'Semanalmente';

        $this->messages['en'][] = 'Monthly';
        $this->messages['pt'][] = 'Mensal';
        $this->messages['es'][] = 'Mensual';

        $this->messages['en'][] = 'Every days';
        $this->messages['pt'][] = 'Todos os dias';
        $this->messages['es'][] = 'Todos los días';

        $this->messages['en'][] = 'Each';
        $this->messages['pt'][] = 'Cada';
        $this->messages['es'][] = 'Cada';

        $this->messages['en'][] = 'Sunday';
        $this->messages['pt'][] = 'Domingo';
        $this->messages['es'][] = 'Domingo';

        $this->messages['en'][] = 'Monday';
        $this->messages['pt'][] = 'Segunda';
        $this->messages['es'][] = 'Lunes';

        $this->messages['en'][] = 'Tuesday';
        $this->messages['pt'][] = 'Terça';
        $this->messages['es'][] = 'Martes';

        $this->messages['en'][] = 'Wednesday';
        $this->messages['pt'][] = 'Quarta';
        $this->messages['es'][] = 'Miércoles';

        $this->messages['en'][] = 'Thursday';
        $this->messages['pt'][] = 'Quinta';
        $this->messages['es'][] = 'Jueves';

        $this->messages['en'][] = 'Friday';
        $this->messages['pt'][] = 'Sexta';
        $this->messages['es'][] = 'Viernes';

        $this->messages['en'][] = 'Saturday';
        $this->messages['pt'][] = 'Sábado';
        $this->messages['es'][] = 'Sábado';

        $this->messages['en'][] = 'Batch creation';
        $this->messages['pt'][] = 'Criação em lote';
        $this->messages['es'][] = 'Creación por lotes';

        $this->messages['en'][] = 'Tasks';
        $this->messages['pt'][] = 'Tarefas';
        $this->messages['es'][] = 'Tareas';

        $this->messages['en'][] = 'Task';
        $this->messages['pt'][] = 'Tarefa';
        $this->messages['es'][] = 'Tarea';

        $this->messages['en'][] = 'Due date';
        $this->messages['pt'][] = 'Data de vencimento';
        $this->messages['es'][] = 'Fecha de vencimiento';

        $this->messages['en'][] = 'Finished at';
        $this->messages['pt'][] = 'Terminou em';
        $this->messages['es'][] = 'Terminado en';

        $this->messages['en'][] = 'Progress';
        $this->messages['pt'][] = 'Progresso';
        $this->messages['es'][] = 'Progreso';

        $this->messages['en'][] = 'Priority';
        $this->messages['pt'][] = 'Prioridade';
        $this->messages['es'][] = 'Prioridad';

        $this->messages['en'][] = 'Responsible';
        $this->messages['pt'][] = 'Responsável';
        $this->messages['es'][] = 'Responsable';

        $this->messages['en'][] = 'Spent time';
        $this->messages['pt'][] = 'Tempo gasto';
        $this->messages['es'][] = 'Tiempo usado';

        $this->messages['en'][] = 'Not started';
        $this->messages['pt'][] = 'não iniciado';
        $this->messages['es'][] = 'No empezado';

        $this->messages['en'][] = 'In progress';
        $this->messages['pt'][] = 'Em andamento';
        $this->messages['es'][] = 'En curso';

        $this->messages['en'][] = 'Stopped';
        $this->messages['pt'][] = 'Parado';
        $this->messages['es'][] = 'Interrumpido';

        $this->messages['en'][] = 'Completed';
        $this->messages['pt'][] = 'Finalizado';
        $this->messages['es'][] = 'Completada';

        $this->messages['en'][] = 'Low';
        $this->messages['pt'][] = 'Baixa';
        $this->messages['es'][] = 'Baja';

        $this->messages['en'][] = 'Normal';
        $this->messages['pt'][] = 'Normal';
        $this->messages['es'][] = 'Normal';

        $this->messages['en'][] = 'High';
        $this->messages['pt'][] = 'Alta';
        $this->messages['es'][] = 'Alta';

        $this->messages['en'][] = 'New task';
        $this->messages['pt'][] = 'Nova tarefa';
        $this->messages['es'][] = 'Nueva tarea';

        $this->messages['en'][] = 'Delete all recurrences';
        $this->messages['pt'][] = 'Excluir todas as recorrências';
        $this->messages['es'][] = 'Eliminar todas las recurrencias';

        $this->messages['en'][] = 'Notes';
        $this->messages['pt'][] = 'Notas';
        $this->messages['es'][] = 'Notas';

        $this->messages['en'][] = 'Create text file';
        $this->messages['pt'][] = 'Criar arquivo texto';
        $this->messages['es'][] = 'Crear archivo de texto';

        $this->messages['en'][] = 'Start';
        $this->messages['pt'][] = 'Iní­cio';
        $this->messages['es'][] = 'Comenzar';

        $this->messages['en'][] = 'Manuals';
        $this->messages['pt'][] = 'Manuais';
        $this->messages['es'][] = 'Manuales';

        $this->messages['en'][] = 'Welcome';
        $this->messages['pt'][] = 'Bem-vindo';
        $this->messages['es'][] = 'Bienvenido';

        $this->messages['en'][] = 'Text file';
        $this->messages['pt'][] = 'Arquivo texto';
        $this->messages['es'][] = 'Archivo de texto';

        $this->messages['en'][] = 'HTML file';
        $this->messages['pt'][] = 'Arquivo HTML';
        $this->messages['es'][] = 'Archivo HTML';

        $this->messages['en'][] = 'My tasks';
        $this->messages['pt'][] = 'Minhas tarefas';
        $this->messages['es'][] = 'Mis tareas';

        $this->messages['en'][] = 'All events';
        $this->messages['pt'][] = 'Todos eventos';
        $this->messages['es'][] = 'Todos los eventos';

        $this->messages['en'][] = 'My events';
        $this->messages['pt'][] = 'Meus eventos';
        $this->messages['es'][] = 'Mis eventos';

        $this->messages['en'][] = 'Month day';
        $this->messages['pt'][] = 'Dia do mês';
        $this->messages['es'][] = 'Dia del mes';

        $this->messages['en'][] = 'Week day';
        $this->messages['pt'][] = 'Dia da semana';
        $this->messages['es'][] = 'Dia de la semana';

        $this->messages['en'][] = 'Hour';
        $this->messages['pt'][] = 'Hora';
        $this->messages['es'][] = 'Hora';

        $this->messages['en'][] = 'Minute';
        $this->messages['pt'][] = 'Minuto';
        $this->messages['es'][] = 'Minuto';

        $this->messages['en'][] = 'Once a month';
        $this->messages['pt'][] = 'Uma vez por mês';
        $this->messages['es'][] = 'Una vez al mes';

        $this->messages['en'][] = 'Once a week';
        $this->messages['pt'][] = 'Uma vez por semana';
        $this->messages['es'][] = 'Una vez a la semana';

        $this->messages['en'][] = 'Once a day';
        $this->messages['pt'][] = 'Uma vez por dia';
        $this->messages['es'][] = 'Una vez al dia';

        $this->messages['en'][] = 'Each five minutes';
        $this->messages['pt'][] = 'A cada 5 minutos';
        $this->messages['es'][] = 'Cada 5 minutos';

        $this->messages['en'][] = 'Schedules';
        $this->messages['pt'][] = 'Agendamentos';
        $this->messages['es'][] = 'Trabajos programados';

        $this->messages['en'][] = 'Schedule';
        $this->messages['pt'][] = 'Agendamento';
        $this->messages['es'][] = 'Trabajo programado';

        $this->messages['en'][] = 'Class';
        $this->messages['pt'][] = 'Classe';
        $this->messages['es'][] = 'Clase';

        $this->messages['en'][] = 'Delegated to me';
        $this->messages['pt'][] = 'Delegado a mim';
        $this->messages['es'][] = 'Delegada a mi';

        $this->messages['en'][] = 'Framework information';
        $this->messages['pt'][] = 'Informações do Framework';
        $this->messages['es'][] = 'Informaciónes del Framework';

        $this->messages['en'][] = 'System';
        $this->messages['pt'][] = 'Sistema';
        $this->messages['es'][] = 'Sistema';

        $this->messages['en'][] = 'Terms of use';
        $this->messages['pt'][] = 'Termos de uso';
        $this->messages['es'][] = 'Condiciones de uso';

        $this->messages['en'][] = 'The following files have been modified from the original framework';
        $this->messages['pt'][] = 'Os seguintes arquivos foram modificados em relação ao framework original';
        $this->messages['es'][] = 'Los siguientes archivos han sido modificados desde el marco original';

        $this->messages['en'][] = 'All framework files are in their original state';
        $this->messages['pt'][] = 'Todos arquivos do framework encontram-se em seu estado original';
        $this->messages['es'][] = 'Todos los archivos del marco están en su estado original';

        $this->messages['en'][] = 'Update';
        $this->messages['pt'][] = 'Atualizar';
        $this->messages['es'][] = 'Actualizar';

        $this->messages['en'][] = 'Current';
        $this->messages['pt'][] = 'Atual';
        $this->messages['es'][] = 'Actual';

        $this->messages['en'][] = 'New';
        $this->messages['pt'][] = 'Novo';
        $this->messages['es'][] = 'Nuevo';

        $this->messages['en'][] = 'Only apply updates to a development server, never update directly to production. Always make backups before updates and test them thoroughly before promoting them to production';
        $this->messages['pt'][] = 'Somente aplique atualizações em servidor de desenvolvimento, jamais faça atualização diretamente em produção. Sempre faça backups antes das atualizações e teste-as exaustivamente antes de promovê-las à produção';
        $this->messages['es'][] = 'Aplique actualizaciones únicamente en el servidor de desarrollo, nunca actualice directamente en producción. Realice siempre copias de seguridad antes de las actualizaciones y pruébelas minuciosamente antes de pasarlas a producción';

        $this->messages['en'][] = 'If you select a directory, all its files will automatically be updated, even if they are not selected';
        $this->messages['pt'][] = 'Se você selecionar um diretório, automaticamente todos seus arquivos serão atualizados, mesmo que eles não estejam selecionados';
        $this->messages['es'][] = 'Si selecciona un directorio, automáticamente se actualizarán todos sus archivos, incluso si no están seleccionados';

        $this->messages['en'][] = 'Third party packages';
        $this->messages['pt'][] = 'Pacotes de terceiros';
        $this->messages['es'][] = 'Paquetes de terceros';

        $this->messages['en'][] = 'If the application does not load after a Framework update, it will be necessary to download it completely again';
        $this->messages['pt'][] = 'Caso a aplicação não carregue após uma atualização de Framework, será necessário realizar novamente o download completo da mesma';
        $this->messages['es'][] = 'Si la aplicación no se carga después de una actualización de Framework, será necesario descargarla completamente nuevamente';
        
        $this->messages['en'][] = 'Your browser does not support displaying this content';
        $this->messages['pt'][] = 'O navegador não suporta a exibição deste conteúdo';
        $this->messages['es'][] = 'El navegador no admite la visualización de este contenido';
        
        $this->messages['en'][] = 'click here to download';
        $this->messages['pt'][] = 'clique aqui para baixar';
        $this->messages['es'][] = 'haga clic aquí para descargar';
        
        $this->messages['en'][] = '^1 not defined';
        $this->messages['pt'][] = '^1 não definido';
        $this->messages['es'][] = '^1 no definido';
        
        $this->messages['en'][] = 'Invalid captcha';
        $this->messages['pt'][] = 'Captcha inválido';
        $this->messages['es'][] = 'Captcha inválido';
        
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
