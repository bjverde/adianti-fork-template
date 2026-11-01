# Auditoria PHP 8.5 – adianti-fork-template

01/11/2026

## Resumo

O código do próprio projeto (app, lib, rest) já está praticamente pronto para o PHP 8.5: o lint não acusou nada, e só há 1 ponto pequeno a ajustar (`curl_close`). Os avisos de depreciação restantes vêm de componentes do vendor, que precisam ser atualizados.

Como foi verificado, com PHP 8.5.0 e `error_reporting=-1`:

1. `php -l` em todos os ~1.200 arquivos .php (projeto e vendor), o que pega depreciações de compilação (casts não canônicos, backtick, `case ...;`, parâmetro implicitamente nullable, `${}` em string).
2. Busca por funções e constantes depreciadas no 8.1 a 8.5 (`curl_close`, `imagedestroy`, `xml_parser_free`, `finfo_close`, constantes `PDO::MYSQL_*`, `utf8_encode`/`utf8_decode`, `strftime`, `E_USER_ERROR`, `setAccessible`, `$http_response_header` etc.).
3. Teste em execução: uma cópia do template no servidor embutido do PHP 8.5, login com o admin padrão e acesso às 78 telas de app/control (padrão, onReload e onEdit). O `init.php` sobe sem nenhum aviso.

A correção já feita do `PDO::MYSQL_ATTR_INIT_COMMAND` (#110) foi confirmada: não sobrou nenhuma constante PDO específica de driver do core. O `PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE` em `TConnection.php:226` vem do driver PECL da Microsoft e não é afetado.

## Achados no código do projeto

Não quebra a aplicação hoje; gera um aviso Deprecated quando o trecho for executado.

| Arquivo:linha | Problema | Depreciado em | Correção sugerida |
| --- | --- | --- | --- |
| template/rest/request.php:33 | `curl_close($ch)` | PHP 8.5 (sem efeito desde 8.0) | Remover a chamada; o handle é liberado sozinho |

Verificados e sem problema: `AdiantiStringConversion.php` (só chama `utf8_encode`/`utf8_decode` no PHP ≤ 8.1) e `SystemModulesCheckView.php` (`E_STRICT` já tratado como 2048).

## Componentes do vendor que precisam ser atualizados

| Componente (versão atual) | Motivo |
| --- | --- |
| linfo/linfo (v4.0.9) | Operador backtick (PHP 8.5), `curl_close` (PHP 8.5) e `continue` dentro de `switch`. O aviso do backtick aparece na tela Informações do Sistema (SystemInformationView) |
| pablodalloglio/phprtflite (dev-master) | `case ...;` com ponto e vírgula (PHP 8.5) e 8 parâmetros implicitamente nullable (PHP 8.4) |
| pablodalloglio/fpdf (dev-master) | `imagedestroy` (PHP 8.5) |
| dompdf/dompdf (v3.1.6) e dompdf/php-svg-lib (1.0.2) | `imagedestroy`, `xml_parser_free`, `curl_close` e `$http_response_header` (PHP 8.5); só ao gerar PDF |
| adianti/studio-forms | `utf8_decode` em `AdiantiUIBuilder.php:957` (PHP 8.2) |

## Outros avisos em execução

O teste nas telas também mostrou alguns Warnings que não são do PHP 8.5 (existem desde o PHP 8.0). Parte deles aparece porque as telas foram abertas sem os parâmetros que o menu normalmente passa, então são de baixa prioridade:

- `app/control/admin/LoginForm.php:167`: `Undefined property: stdClass::$unit_id` quando o login é enviado sem unidade.
- `app/model/admin/SystemUserOldPassword.php:38`: `Undefined array key "password_renewal_interval"` (a chave não existe em application.php); aparece na tela SystemPasswordRenewalForm.
- `app/control/communication/posts/SystemPostCommentList.php:41` e `65` e `SystemPostCommentForm.php:34`: `Undefined array key "system_post_id"` quando abertas sem o post.
- `app/control/communication/documents/SystemDriveDocumentCreateForm.php:41`: `Undefined array key "content_type"`.
