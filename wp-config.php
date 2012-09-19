<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'wp_catalogo');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'krieger');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '5QJ~RD+y?+1H~3`Z.057woB+o@qL+^Q,_--yk<;_?~_<6|zGbkn!fR3|u@473+,a');
define('SECURE_AUTH_KEY',  'TDj}-o-~KL>XZ4P^.75}rAU&K<WIpxPg+XnyZHID_Z0:b$&wSlLFqA(?k$r_uIS#');
define('LOGGED_IN_KEY',    'Jryyb-1|@WfvC|`@sRCfu9^POi1,g472|LSbkpz`wd|qG+1zhJ6TJ7yX>]-w}`c+');
define('NONCE_KEY',        'sY7O/SKq.8|Xbt`r@cnSua>&merUfoxr7t.jDSHj2y,IorfUZIEkf8>-h8<[xa%#');
define('AUTH_SALT',        'i };[z^WGuZQW|lz_ZMq~!Y)hmlEX@;3Y-xoeru@~}:0w1~91#|]?i+Xm2 Ak^I<');
define('SECURE_AUTH_SALT', 'wZ>u1ITZUGBoA;^q,C@?6~~[sR3$<V~c3AwiCQPZKrlL&Sm?nU|=|fc$*@1Q/aj.');
define('LOGGED_IN_SALT',   '~B6>muTL{/)L-Va2k{NTn?dX]=auRL/:KoAwgL,snJ$_m4NCDvXK;oNH5w3/0HC8');
define('NONCE_SALT',       '!/es>@d!SH~?[Tz)CGJ+YfH:g_Gy<ZUt>r`O`_EIh:ns64_0,Lb=]YRg.h7NC%s|');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';

/**
 * O idioma localizado do WordPress é o inglês por padrão.
 *
 * Altere esta definição para localizar o WordPress. Um arquivo MO correspondente ao
 * idioma escolhido deve ser instalado em wp-content/languages. Por exemplo, instale
 * pt_BR.mo em wp-content/languages e altere WPLANG para 'pt_BR' para habilitar o suporte
 * ao português do Brasil.
 */
define('WPLANG', 'pt_BR');

/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
