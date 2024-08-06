<?php
   session_start();
   include_once("sys/conexao.php");
   include_once("sys/funcao.php");
   include_once("sys/crud.php");
   include_once("sys/CSRF_Protect.php");
   include_once("sys/pega-ip.php");
   include_once("sys/ip-crawler.php");
   $csrf = new CSRF_Protect();
   #captura refer =========================================================#
   if(isset($_GET['ref']) && !empty($_GET['ref'])){
      $refer = PHP_SEGURO($_GET['ref']);
      if(pegar_refer($refer) == 1){
         $_SESSION['CAP_REFER'] = $refer;
      }else{
         $_SESSION['CAP_REFER'] = $refer_padrao;  // conta master
      }
   }else{
       $_SESSION['CAP_REFER'] = $refer_padrao;  // conta master 
   }
   #==================================================================#
   if(isset($_GET['utm_ads']) && !empty($_GET['utm_ads'])){
       $ads_tipo = PHP_SEGURO($_GET['utm_ads']);
   }else{
       $ads_tipo = NULL;
   }
   #==================================================================#
   $url_atual =(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   #==================================================================#
   //INSERT DE VISITAS NAS LPS
	$data_hoje = date("Y-m-d");
    $hora_hoje = date("H:i:s");
	if(isset($_SERVER['HTTP_REFERER'])){
      $ref =  $_SERVER['HTTP_REFERER'];
	}else{
      $ref = $url_atual;
	} 
    #==================================================================#
    $data_us = ip_F($ip);
    #==================================================================#
    if($browser != "Unknown Browser	" AND $os != "Unknown OS Platform" AND $data_us['pais'] == "Brazil"){
        $id_user_ret = busca_id_por_refer($_SESSION['CAP_REFER']);
		$sql0 = $mysqli->prepare("SELECT ip_visita FROM visita_site WHERE data_cad=? AND ip_visita=?");
		$sql0->bind_param("ss", $data_hoje,$ip);
		$sql0->execute();
		$sql0->store_result();
		if($sql0->num_rows) {//JÁ EXISTE CAD 
		}else{
			$sql = $mysqli->prepare("INSERT INTO visita_site (nav_os,mac_os,ip_visita,refer_visita,data_cad,hora_cad,id_user,pais,cidade,estado,ads_tipo) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
			$sql->bind_param("sssssssssss",$browser,$os,$ip,$ref,$data_hoje,$hora_hoje,$id_user_ret,$data_us['pais'],$data_us['cidade'],$data_us['regiao'],$ads_tipo);
			$sql->execute(); 
		}
   }
   #===============================================================================#  
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
      <meta charset="utf-8"/>
      <title><?=$dataconfig['nome_site'];?></title>
      <meta content="<?=$dataconfig['nome_site'];?>" property="og:title"/>
      <meta content="<?=$dataconfig['nome_site'];?>" property="twitter:title"/>
      <meta property="og:description" content="<?=$dataconfig['descricao'];?>">
      <meta property="twitter:description" content="<?=$dataconfig['descricao'];?>">
      <meta content="width=device-width, initial-scale=1" name="viewport"/>
      <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
      
      <link rel="manifest" href="<?=$url_base;?>manifest.json">
      <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
      <link href="<?=$docs_uploads.$dataconfig['favicon'];?>" rel="shortcut icon" type="image/x-icon"/>
      <link href="<?=$docs_uploads.$dataconfig['favicon'];?>" rel="apple-touch-icon"/>
      <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
      <meta name="theme-color" content="#141417" />
      
      <link rel="stylesheet" href="<?=$docs_site;?>css/app.css"
    rel="stylesheet" type="text/css" />
  <script
    type="text/javascript">!function (o, c) { var n = c.documentElement, t = " w-mod-"; n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch") }(window, document);</script>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
  <meta name="theme-color" content="#010E24" />
  <link rel="stylesheet" href="https://cdn.nexus-casino.io/webflow-style-head-v2.css">
  <script async src="https://cdn.jsdelivr.net/npm/@finsweet/attributes-cmsfilter@1/cmsfilter.js"></script>
  <script async src="https://cdn.jsdelivr.net/npm/@finsweet/attributes-cmssort@1/cmssort.js"></script>
  <script async src="https://cdn.jsdelivr.net/npm/@finsweet/attributes-cmsload@1/cmsload.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@finsweet/attributes-scrolldisable@1/scrolldisable.js"></script>
	<!--<script src="https://cdn.jsdelivr.net/gh/brunoalbim/devtools-detect/index.js"></script>-->
 
<!--<script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool'></script>-->


      <style>
         :root{
            --default:<?=$dataconfig['cor_padrao'];?>;
            --black:#141417;
            --black-25:rgba(0,0,0,.25);
            --white-50:rgba(255,255,255,.3);
            --gray100:white;
            --yellow:<?=$dataconfig['cor_padrao'];?>;
            --white-5:rgba(218,209,177,.05);
            --white-10:rgba(218,209,177,.1);
            --orange:<?=$dataconfig['cor_padrao'];?>;
            --black-80:rgba(20,20,23,.9);
            --yellow10:rgba(227,45,71,.1);
            --slate-blue:rgba(227,45,71,.5);
            --white-2:rgba(218,209,177,.02);
            --black-50:rgba(0,0,0,.5);
            --green-yellow:#6cab36;
            --211b38:#1b1b1f;
            --yellow25:rgba(104, 0, 255, 0.25);
            --olive-drab:#3f7f41;
            --lime:rgba(227,45,71,.25)
        }
        .notify-popup {
             padding: 0 10px;
             display: flex;
             flex-direction: row;
             align-items: center;
             justify-content: space-between;
             z-index: 9999;
             position: absolute;
             top: 0;
             left: 0;
             min-height: 40px;
             max-height: 40px;
             width: 100%;
             background-color: <?=$dataconfig['cor_topheader'];?>;
             color: #fff;
         }
         @media (max-width: 767.98px)
         .notify-popup span {
             font-size: 12px;
         }
         
         .notify-popup button {
             padding: 2px 20px;
             background-color: #fff;
             color: #5f6af2;
             border: 2px solid transparent;
             text-decoration: none;
             transition: all 0.5s ease;
             font-size: 14px;
             font-weight: 600;
             border-radius: 4px;
             cursor: pointer;
             margin-left: 10px;
         }
        
      </style>
      <!-- META TAGS CP SOCIAL -->
      <meta property="og:title" content="<?=$dataconfig['nome_site'];?>" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="<?=$url_base;?>" />
      <meta property="og:image" content="<?=$docs_uploads.$dataconfig['img_seo'];?>" />
      <meta property="og:description" content="<?=$dataconfig['descricao'];?>" />

      
      <meta name="twitter:card" content="summary" />
      <meta name="twitter:creator" content="CODECASSINOS" />
      <meta name="twitter:title" content="<?=$dataconfig['nome_site'];?>" />
      <meta name="twitter:image" content="<?=$docs_uploads.$dataconfig['img_seo'];?>" /> 
      <meta name="twitter:description" content="<?=$dataconfig['descricao'];?>" />
      
   </head>

   <body>
   
      <div id="<?=$url_base;?>" class="base_url"></div>
      <section id="page-wrapper" class="page-wrapper">
         <?php if($dataconfig['status_topheader'] == 1){ ?>
          <div class="d-flex"><!---->
             <div class="notify-popup visible-popup">
               <div></div> 
               <div style="display: flex; flex-direction: row; align-items: center;">
                  <span>Ganhe R$25,00 por cada indicado!</span> <button data-toggle="modal" data-target="#cadastroModal">Convidar</button>
               </div> 
               <svg fill="none" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m6.29289 6.29289c.39053-.39052 1.02369-.39052 1.41422 0l3.93929 3.93931c.1953.1953.5119.1953.7072 0l3.9393-3.93931c.3905-.39052 1.0237-.39052 1.4142 0 .3905.39053.3905 1.02369 0 1.41422l-3.9393 3.93929c-.1953.1953-.1953.5119 0 .7072l3.9393 3.9393c.3905.3905.3905 1.0237 0 1.4142s-1.0237.3905-1.4142 0l-3.9393-3.9393c-.1953-.1953-.5119-.1953-.7072 0l-3.93929 3.9393c-.39053.3905-1.02369.3905-1.41422 0-.39052-.3905-.39052-1.0237 0-1.4142l3.93931-3.9393c.1953-.1953.1953-.5119 0-.7072l-3.93931-3.93929c-.39052-.39053-.39052-1.02369 0-1.41422z" fill="#fff" fill-rule="evenodd"></path></svg>
            </div>
         </div>
         <br/><br/><br/>
         <?php } ?>
         <div id="menu-nav" class="content-navbar">
      <div class="eng-head-menu">
        <div class="eng-social-menu-2"><a tenant-telegram="" href="#" target="_blank"
            class="link-social w-inline-block">
            <div class="icon-18-brand"></div>
          </a><a tenant-instagram="" href="#" target="_blank" class="link-social w-inline-block">
            <div class="icon-18-brand"></div>
          </a><a open-support-btn="" href="#" class="link-social w-inline-block">
            <div class="icon-18"></div>
          </a></div>
        <div class="content-navbar-buttons">
          <div logged-in="" class="navbar-logged">
            <div class="navbar-buttons-balance-wrapper"><a href="#" class="btn-small balance w-inline-block">
                <div class="color-1"><?=Reais2($saldo_data['saldo']);?></div>
                <div balance-w-bonus="">-</div>
              </a><a href="/deposit" class="btn-small btn-color-1 w-inline-block">
                <div class="txt-btn">Deposite agora!</div>
                <div class="content-anim-light">
                  <div class="eng-anim-light" data-ix="anim-light-btn">
                    <div class="anim-light"></div>
                  </div>
                </div>
              </a>
              <div data-hover="false" data-delay="0" class="drop-menu w-dropdown">
                <div class="btn-drop w-dropdown-toggle">
                  <div class="eng-letter-name">
                    <div class="icon-12"></div>
                    <div user-username="" class="name-user">...</div>
                  </div>
                </div>
                <nav class="drop-list-menu w-dropdown-list">
                  <div class="content-drop-list-menu"><a whenClicked="depositAction();" href="/account"
                      class="link-drop w-inline-block" data-ix="window-deposit">
                      <div class="icon-12 fixed-width-24 color-1"></div>
                      <div>Conta</div>
                    </a><a whenClicked="depositAction();" href="/deposit" class="link-drop w-inline-block"
                      data-ix="window-deposit">
                      <div class="icon-12 fixed-width-24 color-1"></div>
                      <div>Depositar</div>
                    </a><a fs-scrolldisable-element="disable" whenClicked="withdrawAction();" href="#"
                      class="link-drop w-inline-block" data-ix="window-wihdraw">
                      <div class="icon-12 fixed-width-24 color-1"></div>
                      <div>Sacar</div>
                    </a><a open-window-rollover="" href="#" class="link-drop w-inline-block" data-ix="display-none">
                      <div class="icon-12 fixed-width-24 color-1"></div>
                      <div>Histórico</div>
                    </a></div>
                  <div class="content-drop-list-menu"><a id="logout-btn" whenClicked="logout();" href="#"
                      class="link-drop active w-inline-block">
                      <div class="icon-16 fixed-width-24"></div>
                      <div>Sair</div>
                    </a></div>
                </nav>
              </div>
            </div>
          </div>
          <div not-logged-in="" class="navbar-not-logged">
          <div class="navbar-buttons-login-wrapper">
          <a fs-scrolldisable-element="disable" href="<?=$login;?>"
            class="btn-small btn-login w-button" ><span class="icon-16 txt-green" style="color: #fff;"></span>
            Entrar</a>
            <a href="<?=$registrar;?>" class="btn-small btn-color-1 btn-login w-inline-block">
            <div class="txt-btn"
                style="transform-style: preserve-3d; transition: transform 500ms ease 0s; transform: scaleX(1) scaleY(1) scaleZ(1);">
                <span class="icon-16"></span> Criar conta</div>
            <div class="content-anim-light">
                <div class="eng-anim-light" data-ix="anim-light-btn"
                    style="transform: translateX(120%) translateY(0px) translateZ(0px); opacity: 1; transition: transform 2500ms cubic-bezier(0.23, 1, 0.32, 1) 0s, opacity 500ms ease 0s;">
                    <div class="anim-light"></div>
                </div>
            </div>
        </a></div>
        </div>
        </div>
      </div>
      <div class="navbar-wrapper">
        <div class="navbar-left">
        <div class="w-dyn-list">
				  <div role="list" class="w-dyn-items">
					 <div role="listitem" class="w-dyn-item"><img loading="eager" tenant-logo="" alt="" src="<?=$docs_uploads.$dataconfig['logo'];?>" class="logo-window mb-19"></div>
				  </div>
			   </div>
        </div>
        <div class="eng-tags-menu"><a href="/allgames?category=Crash+Game" class="link-tag-menu w-inline-block">
            <div class="icon-16"><strong></strong></div>
            <div class="white no-wrap">Crash Games</div>
          </a><a href="/aovivo?category=Roleta" class="link-tag-menu w-inline-block">
            <div class="icon-16"><strong></strong></div>
            <div class="white no-wrap">Roletas</div>
          </a><a href="/cassino" class="link-tag-menu w-inline-block">
            <div class="icon-16"><strong></strong></div>
            <div class="white">Cassino</div>
          </a><a href="/aovivo" class="link-tag-menu w-inline-block">
            <div class="icon-16"><strong></strong></div>
            <div class="white no-wrap">Cassino ao vivo</div>
          </a><a href="/game/spribe-aviator" class="link-tag-menu w-inline-block">
            <div class="icon-16"><strong class="color-3"></strong></div>
            <div class="white no-wrap">Aviator</div>
            <div class="tag-new color-3">novo!</div>
          </a><a href="/game/pgsoft-fortune-tiger" class="link-tag-menu w-inline-block">
            <div class="icon-16"><strong class="color-2"></strong></div>
            <div class="white no-wrap">Fortune Tiger</div>
            <div class="tag-new color-2">novo!</div>
          </a></div>
        <div class="eng-btns-mobile">
          <div class="eng-btns-search">
            <div class="btn-search-one" data-ix="open-window-search-one"><a href="#search"
                class="btn-small w-inline-block">
                <div class="icon-16"></div>
              </a></div>
            <div class="btn-search-two" data-ix="open-window-search-two"><a href="#" class="btn-small w-inline-block">
                <div class="icon-16"></div>
              </a></div>
          </div>
          <div fs-scrolldisable-element="toggle" class="btn-small" data-ix="open-menu-resp">
            <div class="icon-24 color-1" style="color: #fff;"></div>
          </div>
        </div>
        <div class="pattern-menu"></div>
      </div>

      <div class="navbar-mobile"><a href="/" aria-current="page" class="link-menu-mobile w-inline-block w--current">
      <div class="icon-16 mb-4"></div>
      <div class="white">Início</div>
    </a>
    <link rel="prerender" href="/" /><a fs-scrolldisable-element="disable" href="#"
      class="link-menu-mobile-open-menu w-inline-block" data-ix="open-menu-resp">
      <div class="icon-16 mb-4"></div>
      <div class="white">Menu</div>
    </a><a fs-scrolldisable-element="enable" href="#" class="link-menu-mobile-close-menu w-inline-block"
      data-ix="close-menu-resp">
      <div class="icon-16 mb-4"></div>
      <div class="white">Menu</div>
    </a><a href="#search" class="link-menu-mobile-open-search w-inline-block" data-ix="open-window-search-one-lateral">
      <div class="icon-16 mb-4"></div>
      <div class="white">Buscar</div>
    </a><a href="/aovivo" class="link-menu-mobile w-inline-block">
      <div class="icon-16 mb-4"></div>
      <div class="white">Ao vivo</div>
    </a>
    <link rel="prerender" href="/aovivo" /><a href="/cassino" class="link-menu-mobile w-inline-block">
      <div class="icon-16 mb-4"></div>
      <div class="white">Cassino</div>
    </a>
  </div>
  </div>
  
  