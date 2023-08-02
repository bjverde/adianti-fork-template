# Sobre Adianti
* Informações sobre o Adianti e seu criador veja  [pagina principal](../README.md)
* [Lista de todas as mudanças](changelog_fork.md)


# Changelog do Fork do Template do Adianti 7.3.0

## 1 - Script para MySQL
O script sql do banco de permissões foi alterado para o MySQL 
Na pasta [`template/app/database/database_mysql/`](https://github.com/bjverde/adianti-template/tree/master/template/app/database/database_mysql) tem 3 arquivos

1. permission.mwb - arquivo do MER no formato do [MySQL Workbench 6.3 CE](https://www.mysql.com/products/workbench/)
1. permission.pdf - arquivo do MER no formato PDF
1. permission_mysql.sql -arquivo SQL


## 2 - Alterado modo de criptografia da senha
No Adianti Template 7.3.0 por padrão as senhas dos usuários são gravadas no banco de dados com uma criptografia MD5. Porém essa é uma pratica não recomendada. Para melhorar isso foi alterado para password_hash

* [Commit da alteração do PHP](https://github.com/bjverde/adianti-fork-template/commit/f9c69c40aa30d4d2d3413dd2bd4d90ede60d94d9)
* [Commit do update da senha no banco SqLite](https://github.com/bjverde/adianti-fork-template/commit/89f0b900d4752dcd7d4955a3e18fc5f9d342e1b8)
* [Commit do update da senha no banco SqLite](https://github.com/bjverde/adianti-fork-template/commit/89f0b900d4752dcd7d4955a3e18fc5f9d342e1b8)
* [Commit alterando modelo do banco](https://github.com/bjverde/adianti-fork-template/commit/9b3f1f5c1f4091e82116ca7f851b9b6554c0a544)
* [Commit alterando ](https://github.com/bjverde/adianti-fork-template/commit/b76c326f23e3ea3e87f90ad815e8db8754ee7d5d)

## 3 - Botão limpar pesquisa
Inclusão do botão de limpar pesquisa nas telas
* Adm > Programas
* Adm > Grupos
* Adm > Unidades
* Adm > Usuários

![Botão de limpar](img/template_71_limpar_pesquisa.png)


## 4 - Coluna com o nome classe do Programa
Inclusão da coluna com o nome da Classe de controle nas telas

* edição de Usuários
* edição de Grupos



## 5 - Inclusão do link para o Login
Nas telas de criar conta e redefiniar senha não tinha um link para voltar para o login

![Botão de login](img/template_71_senha.png)


## 6 - Novos temas

O tema padrão é o theme3_v4 para o template. 

Informações e alterações veja no link abaixo
https://github.com/bjverde/adianti-theme/blob/master/documents/template/bootstrap_theme3_v4.md

### 6.1 - outros temas
* [theme_formdin](https://github.com/bjverde/adianti-theme/blob/master/documents/template/bootstrap_theme_formdin.md)
* [theme3_h](https://github.com/bjverde/adianti-theme/blob/master/documents/template/bootstrap_theme3_h.md)

## 7 - Arquivos 
Todos os arquivos com EOL (end of line) no formato Unix (LF) e arquivos em UTF-8 sem BOM