# Sobre Adianti
Informações sobre o Adianti e seu criador veja  [pagina principal](../README.md)

# Changelog do Fork do Template do Adianti v7.5.1b2
* template/.gitignore - arquivo diferente do original
* 🔨- [#38 Incluir o botão de limpar pesquisa](https://github.com/bjverde/adianti-fork-template/issues/38)
* 🔨- [#37 Update dompdf from 2.0.3](https://github.com/bjverde/adianti-fork-template/issues/37)
* 🔨- [#34 Update firebase/php-jwt from 6.0](https://github.com/bjverde/adianti-fork-template/issues/34)


## 1 - Alterado modo de criptografia da senha
No Adianti Template 7.5.1 por padrão as senhas dos usuários são gravadas no banco de dados com uma criptografia MD5. Porém essa é uma pratica não recomendada. Para melhorar isso foi alterado para password_hash 

Para voltar para o padrão MD5 altere 
* SystemUser::passwordVerify descomentando a linhda MD5 e comentando password_verify
* SystemUser::getHashPassword descomentando a linhda MD5 e comentando password_verify

## 2 - Botão limpar pesquisa
Inclusão do botão de limpar pesquisa nas telas
* Adm > Programas
* Adm > Grupos
* Adm > Unidades
* Adm > Usuários

![Botão de limpar](img/template_71_limpar_pesquisa.png)

## 3 - Coluna com o nome classe do Programa
Inclusão da coluna com o nome da Classe de controle nas telas

* edição de Usuários
* edição de Grupos


# [Changelog do Fork do Template do Adianti v7.3.0](changelog_fork_v7.3.0.md)