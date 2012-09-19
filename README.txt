CEBRID - Catálogo de publicações

#Resumo: 
O sistema será desenvolvido sob forma de plugin para o wordpress. Permitirá ao usuário do site realizar buscas 
num catálogo de publicações filtrando por palavras-chave. 
O catálogo e as palavras-chave serão gerenciados pelo administrador do site via front-end.

#Especificações: 
***A interface de administração***
- Será acessada através de uma página do wordpress com o shortcode: 
-- [trajettoria-catalogo-adm];

- Deverá mostrar numa tela única uma tabela com todas as informações dos "trabalhos" (publicações) contidas no banco e outra com todas as palavras-chave cadastradas;

- A última coluna de ambas as tabelas, deverá ser nomeada "opções" e conter 2 botões em cada linha de tbody: "editar" e "excluir";

- No tfooter, deverá existir um input em cada coluna para que o administrador possa preencher e adicionar um novo trabalho clicando em outro botão da coluna "opções": "adiconar novo";

- Ao clicar em "editar", os campos da linha deverão se transformar em input's para que o administrador possa editá-los. Para que o administrador possa finalizar a edição da linha, o botão "editar" deverá se tranformar em "confirmar";

- Ao clicar em "excluir", um pop-up deverá ser mostrado para confirmação de exclusão;

- todas as informações deverão ser processadas via ajax;

- A tabela "trabalhos" deverá conter as colunas:
1- Autor
2- Título
3- Revista
4- Resumo
5- Volume
6- Número
7- Primeira página
8- Última página
9- Ano
10- Palavras-chave (controle com as palavras cadastradas)
11- Data de inclusão
12- Data da última modificação
13- Fotocópias
14- Arquivos (upload de scans)
15- Quantidade de downloads
16- Opções

***A interface de usuário***
- será acessada através de uma página do wordpress com o shortcode:
--[trajettoria-catalogo-user]

==========================================================================================================================
v0.2
18/09/12 - Renato
#descrição
Configurações iniciais do plugin.

#changelog
novo dump insere novos campos (vide especificações no topo desse README)
criados os shortcodes e demais necessidades do plugin
iniciada a programação do painel de administração

#known issues
#todo
implementações jQuery e ajax

==========================================================================================================================
v0.1
17/09/12 - Renato
#descrição
Configurações iniciais do plugin.

#changelog
gerado um dump das tabelas a serem usadas;
montado o esqueleto das funções necessárias para plugin wordpress.

#known issues


#todo
tudo :)